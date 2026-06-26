<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Setting;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        abort_if(! Auth::user()->can('setting_view'), 403);

        try {
            $settings = Setting::firstOrCreate(
                ['id' => 1],
                [
                    'allow_penalty' => false,
                    'penalty_days' => 0,
                    'penalty_type' => 'fixed',
                    'penalty_amount' => 0.00,
                    'discount_type' => 'percentage',
                    'discount_monthly' => 0.00,
                    'discount_quarterly' => 0.00,
                    'discount_half_yearly' => 0.00,
                    'discount_yearly' => 0.00,
                ]
            );

            return view('settings.index', compact('settings'));
        } catch (Exception $e) {
            Log::error('Settings Index Error: ' . $e->getMessage());
            return abort(500);
        }
    }

    /**
     * Update Penalty Settings.
     */
    public function updatePenalty(Request $request)
    {
        abort_if(! Auth::user()->can('setting_edit'), 403);

        try {
            $settings = Setting::firstOrCreate(['id' => 1]);

            // If allow_penalty is checked, it will be sent, otherwise we set to false
            $allowPenalty = $request->has('allow_penalty');

            $rules = [
                'allow_penalty' => 'nullable',
            ];

            if ($allowPenalty) {
                $rules = array_merge($rules, [
                    'penalty_days' => 'required|integer|min:0',
                    'penalty_type' => 'required|in:fixed,percentage',
                    'penalty_amount' => 'required|numeric|min:0',
                ]);
            }

            $request->validate($rules);

            $settings->update([
                'allow_penalty' => $allowPenalty,
                'penalty_days' => $allowPenalty ? $request->penalty_days : 0,
                'penalty_type' => $allowPenalty ? $request->penalty_type : 'fixed',
                'penalty_amount' => $allowPenalty ? $request->penalty_amount : 0.00,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Penalty settings updated successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('Update Penalty Settings Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Update Discount Settings.
     */
    public function updateDiscount(Request $request)
    {
        abort_if(! Auth::user()->can('setting_edit'), 403);

        try {
            $settings = Setting::firstOrCreate(['id' => 1]);

            $rules = [
                'discount_type' => 'required|in:fixed,percentage',
                'discount_monthly' => 'required|numeric|min:0',
                'discount_quarterly' => 'required|numeric|min:0',
                'discount_half_yearly' => 'required|numeric|min:0',
                'discount_yearly' => 'required|numeric|min:0',
            ];

            if ($request->discount_type === 'percentage') {
                $rules['discount_monthly'] .= '|max:100';
                $rules['discount_quarterly'] .= '|max:100';
                $rules['discount_half_yearly'] .= '|max:100';
                $rules['discount_yearly'] .= '|max:100';
            }

            $request->validate($rules);

            $settings->update([
                'discount_type' => $request->discount_type,
                'discount_monthly' => $request->discount_monthly,
                'discount_quarterly' => $request->discount_quarterly,
                'discount_half_yearly' => $request->discount_half_yearly,
                'discount_yearly' => $request->discount_yearly,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Discount settings updated successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('Update Discount Settings Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Something went wrong.',
            ], 500);
        }
    }

    public function rolePermission()
    {
        try {
            $roles = Role::where('name', '!=', 'admin')->withCount('users')->get();
            
            return view('settings.roles-permissions', compact('roles'));
        } catch (Exception $e) {
            Log::error('Settings Role Permission Error: ' . $e->getMessage());
            return abort(500);
        }
    }
}
