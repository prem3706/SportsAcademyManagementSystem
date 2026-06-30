<?php

namespace App\Http\Controllers;

use App\DataTables\UnpaidPlayersDataTable;
use App\Models\Batch;
use App\Models\Expense;
use App\Models\PlayerFee;
use App\Models\Sport;
use App\Models\User;
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
            $monthly_earnings = collect(range(5, 0))->map(function ($i) {
                $startOfMonth = now()->subMonths($i)->startOfMonth();
                $endOfMonth = now()->subMonths($i)->endOfMonth();
                $monthName = $startOfMonth->format('M Y');

                $paid = 0;
                $pending = 0;

                // Get all player-batch enrollments active in this month
                $enrollments = DB::table('batch_player')
                    ->join('batches', 'batch_player.batch_id', '=', 'batches.id')
                    ->join('sports_levels', function ($join) {
                        $join->on('batches.sport_id', '=', 'sports_levels.sport_id')
                            ->on('batches.level_id', '=', 'sports_levels.level_id');
                    })
                    ->where(function ($q) use ($endOfMonth) {
                        $q->whereNull('batch_player.joined_at')
                            ->orWhere('batch_player.joined_at', '<=', $endOfMonth);
                    })
                    ->select('batch_player.player_id', 'batch_player.batch_id', 'sports_levels.fees')
                    ->get();

                foreach ($enrollments as $enrollment) {
                    // Check if player has paid for this batch for this month
                    $hasPaid = PlayerFee::where('player_id', $enrollment->player_id)
                        ->where('batch_id', $enrollment->batch_id)
                        ->where('status', 'paid')
                        ->where('start_date', '<=', $endOfMonth)
                        ->where('end_date', '>=', $startOfMonth)
                        ->exists();

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
            $sports_earnings = Sport::all()->map(function ($sport) {
                $earnings = PlayerFee::whereHas('batch', function ($q) use ($sport) {
                    $q->where('sport_id', $sport->id);
                })->where('status', 'paid')->sum('total_amt');

                return [
                    'name' => $sport->name,
                    'earnings' => floatval($earnings),
                ];
            })->values()->toArray();

            return $dataTable->render('dashboard', array_merge($stats, [
                'monthly_earnings' => $monthly_earnings,
                'sports_earnings' => $sports_earnings,
                'unpaid_month' => $unpaid_month,
                'unpaid_year' => $unpaid_year,
            ]));
        } catch (\Exception $e) {
            Log::error('Dashboard Index Error: '.$e->getMessage());

            return back()->with('error', 'Something went wrong while loading the dashboard.');
        }
    }
}
