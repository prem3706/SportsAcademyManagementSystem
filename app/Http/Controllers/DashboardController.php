<?php

namespace App\Http\Controllers;

use App\DataTables\UnpaidPlayersDataTable;
use App\Models\Batch;
use App\Models\Expense;
use App\Models\PlayerFee;
use App\Models\Sport;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(UnpaidPlayersDataTable $dataTable, Request $request)
    {
        abort_if(! $request->user()->can('dashboard_view'), 403);

        try {
            // 1. Monthly Revenue collections (Last 6 Months)
            $overallStartDate = now()->subMonths(5)->startOfMonth();
            $overallEndDate = now()->endOfMonth();

            // Fetch all paid player fees within the overall 6-month period
            $paidFees = PlayerFee::where('status', 'paid')
                ->where('end_date', '>=', $overallStartDate->toDateString())
                ->where('start_date', '<=', $overallEndDate->toDateString())
                ->get(['player_id', 'batch_id', 'start_date', 'end_date']);

            // Group by player_id and batch_id for fast in-memory lookup
            $paidFeesGrouped = $paidFees->groupBy(function ($fee) {
                return $fee->player_id . '_' . $fee->batch_id;
            });

            // Get all player-batch enrollments
            $allEnrollments = DB::table('batch_player')
                ->join('batches', 'batch_player.batch_id', '=', 'batches.id')
                ->join('sports_levels', function ($join) {
                    $join->on('batches.sport_id', '=', 'sports_levels.sport_id')
                        ->on('batches.level_id', '=', 'sports_levels.level_id');
                })
                ->select('batch_player.player_id', 'batch_player.batch_id', 'batch_player.joined_at', 'sports_levels.fees')
                ->get();

            $monthly_earnings = collect(range(5, 0))->map(function ($i) use ($allEnrollments, $paidFeesGrouped) {
                $startOfMonth = now()->subMonths($i)->startOfMonth();
                $endOfMonth = now()->subMonths($i)->endOfMonth();
                $monthName = $startOfMonth->format('M Y');

                $paid = 0;
                $pending = 0;

                $startOfMonthStr = $startOfMonth->toDateString();
                $endOfMonthStr = $endOfMonth->toDateString();

                // Filter enrollments active in this month
                $enrollments = $allEnrollments->filter(function ($enrollment) use ($endOfMonthStr) {
                    return is_null($enrollment->joined_at) || $enrollment->joined_at <= $endOfMonthStr;
                });

                foreach ($enrollments as $enrollment) {
                    // Check if player has paid for this batch for this month
                    $key = $enrollment->player_id . '_' . $enrollment->batch_id;
                    $playerBatchFees = $paidFeesGrouped->get($key);
                    $hasPaid = false;

                    if ($playerBatchFees) {
                        foreach ($playerBatchFees as $fee) {
                            $feeStartDate = $fee->start_date->toDateString();
                            $feeEndDate = $fee->end_date->toDateString();

                            if ($feeStartDate <= $endOfMonthStr && $feeEndDate >= $startOfMonthStr) {
                                $hasPaid = true;
                                break;
                            }
                        }
                    }

                    if ($hasPaid) {
                        $paid += floatval($enrollment->fees);
                    } else {
                        $pending += floatval($enrollment->fees);
                    }
                }

                return [
                    'month' => $monthName,
                    'month_date' => $startOfMonth->format('Y-m'),
                    'paid' => $paid,
                    'pending' => $pending,
                ];
            })->values()->toArray();
            // Log::info($monthly_earnings);

            // Calculate total pending outstanding collections for last 6 months
            $total_fees_pending = collect($monthly_earnings)->sum('pending');
            $currentMonth = now()->format('Y-m');

            $total_monthly_fees_pending = collect($monthly_earnings)
                ->firstWhere('month_date', $currentMonth)['pending'] ?? 0;

            // Log::info($total_monthly_fees_pending);

            $month_fees_collected = PlayerFee::where('status', 'paid')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_amt');
            $month_expenses = Expense::whereMonth('expense_date', now()->month)->whereYear('expense_date', now()->year)->sum('amount');
            $net_monthly_balance = $month_fees_collected - $month_expenses;
            $total_expenses = Expense::sum('amount');

            $stats = [
                'total_players' => User::role('player')->count(),
                'total_coaches' => User::role('coach')->count(),
                'total_sports' => Sport::count(),
                'total_batches' => Batch::count(),
                'month_fees_collected' => $month_fees_collected,
                'month_expenses' => $month_expenses,
                'net_monthly_balance' => $net_monthly_balance,
                'total_expenses' => $total_expenses,
                'total_fees_pending' => $total_fees_pending,
                'total_monthly_fees_pending' => $total_monthly_fees_pending,
                'recent_fees' => PlayerFee::with('player')->latest()->take(5)->get(),
                'recent_players' => User::role('player')->latest()->take(5)->get(),
                'recent_batches' => Batch::with(['sport', 'level', 'coaches'])->latest()->take(5)->get(),
            ];

            // 2. Unpaid players list filters for this month
            $unpaid_month = intval($request->query('unpaid_month', now()->month));
            $unpaid_year = intval($request->query('unpaid_year', now()->year));

            // 3. Sport-wise Revenue distribution
            $sports_earnings_data = DB::table('player_fees')
                ->join('batches', 'player_fees.batch_id', '=', 'batches.id')
                ->where('player_fees.status', 'paid')
                ->select('batches.sport_id', DB::raw('SUM(player_fees.total_amt) as total_earnings'))
                ->groupBy('batches.sport_id')
                ->pluck('total_earnings', 'sport_id');

            $sports_earnings = Sport::all()->map(function ($sport) use ($sports_earnings_data) {
                return [
                    'name' => $sport->name,
                    'earnings' => floatval($sports_earnings_data->get($sport->id, 0)),
                ];
            })->values()->toArray();

            return $dataTable->render('dashboard', array_merge($stats, [
                'monthly_earnings' => $monthly_earnings,
                'sports_earnings' => $sports_earnings,
                'unpaid_month' => $unpaid_month,
                'unpaid_year' => $unpaid_year,
            ]));
        } catch (Exception $e) {
            Log::error('Dashboard Index Error: '.$e->getMessage());

            return back()->with('error', 'Something went wrong while loading the dashboard.');
        }
    }
}
