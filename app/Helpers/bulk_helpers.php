<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

if (!function_exists('handleBulkDelete')) {
    function handleBulkDelete(Request $request, string $modelClass, string $modelLabel, ?string $permission = null, ?callable $customDelete = null)
    {
        if ($permission) {
            abort_if(! Auth::user()->can($permission), 403);
        }

        try {
            $ids = $request->input('select', []);

            if (! is_array($ids)) {
                $ids = array_filter(explode(',', $ids));
            }

            if (count($ids) > 0) {
                $deletedCount = $customDelete ? $customDelete($ids) : $modelClass::destroy($ids);

                return response()->json([
                    'success' => true,
                    'message' => $deletedCount . ' ' . $modelLabel . ' deleted successfully.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No valid ' . strtolower($modelLabel) . ' selected for deletion.',
            ], 422);

        } catch (\Exception $e) {
            Log::error($modelLabel . ' Bulk Delete Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }
}

if (!function_exists('handleBulkUpdate')) {
    function handleBulkUpdate(Request $request, string $modelClass, string $modelLabel, ?string $permission = null, ?callable $resolveStatus = null)
    {
        if ($permission) {
            abort_if(! Auth::user()->can($permission), 403);
        }

        try {
            $validated = $request->validate([
                'select' => 'required',
                'status' => 'required|string|in:active,inactive',
            ]);

            $ids = $request->input('select', []);
            $status = $resolveStatus ? $resolveStatus($request->input('status')) : $request->input('status');

            if (! is_array($ids)) {
                $ids = array_filter(explode(',', $ids));
            }

            if (count($ids) > 0) {
                $updatedCount = $modelClass::whereIn('id', $ids)->update(['status' => $status]);

                return response()->json([
                    'success' => true,
                    'message' => $updatedCount . ' ' . $modelLabel . ' updated successfully.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No valid ' . strtolower($modelLabel) . ' selected for update.',
            ], 422);

        } catch (\Exception $e) {
            Log::error($modelLabel . ' Bulk Update Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }
}
