<?php

namespace App\Http\Controllers;

use App\DataTables\PlayersDataTable;
use App\Exports\PlayersExport;
use App\Imports\PlayersImport;
use App\Models\Batch;
use App\Models\Level;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PlayersDataTable $dataTable)
    {
        abort_if(! Auth::user()->can('player_view'), 403);
        try {
            $sports = Sport::where('status', 'active')->get();
            $levels = Level::where('status', 'active')->get();
            $batches = Batch::where('status', 'active')->get();

            return $dataTable->render('players.index', compact('sports', 'levels', 'batches'));
        } catch (\Exception $e) {
            Log::error('PlayerController index error: ' . $e->getMessage());
            abort(500, 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(! Auth::user()->can('player_create'), 403);
        try {
            $sports = Sport::where('status', 'active')->get();

            return view('players.addPlayerForm', compact('sports'));
        } catch (\Exception $e) {
            Log::error('PlayerController create error: ' . $e->getMessage());
            abort(500, 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(! Auth::user()->can('player_create'), 403);
        try {
            $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'nullable|email|unique:users,email',
                'phone' => 'required|string|max:10|unique:users,phone',
                'joined_at' => 'required|date',
                'gender' => 'nullable|in:male,female,other',
                'assignments' => 'required|array|min:1',
                'assignments.*.sport_id' => 'required|exists:sports,id',
                'assignments.*.level_id' => 'required|exists:levels,id',
                'assignments.*.batch_id' => 'required|exists:batches,id',
                'assignments.*.joined_at' => 'required|date',
            ]);

            // Generate password: firstname@123 (lowercase)
            $plainPassword = strtolower(str_replace(' ', '', $request->firstname)).'@123';

            // Create user as active player
            $player = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => $plainPassword,
                'gender' => $request->gender,
                'role' => 'player',
                'status' => 'active',
                'joined_at' => $request->joined_at,
            ]);
            $player->assignRole('player');

            // Attach player to all selected batches
            foreach ($request->assignments as $assignment) {
                $batch = Batch::findOrFail($assignment['batch_id']);
                $batch->players()->attach($player->id, [
                    'joined_at' => $assignment['joined_at'],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Player created successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('PlayerController store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating player: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_if(! Auth::user()->can('player_edit'), 403);
        try {
            $player = User::findOrFail($id);
            $sports = Sport::where('status', 'active')->get();

            // Load player's batches with sport and level
            $playerBatches = $player->playerBatches()->with(['sport.levels', 'level'])->get();

            return view('players.editPlayerForm', compact('player', 'sports', 'playerBatches'));
        } catch (\Exception $e) {
            Log::error('PlayerController edit error: ' . $e->getMessage());
            abort(500, 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(! Auth::user()->can('player_edit'), 403);
        try {
            $player = User::findOrFail($id);

            $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'nullable|email|unique:users,email,'.$player->id,
                'phone' => 'required|string|max:10|unique:users,phone,'.$player->id,
                'joined_at' => 'required|date',
                'gender' => 'nullable|in:male,female,other',
                'status' => 'required|in:active,inactive',
                'assignments' => 'required|array|min:1',
                'assignments.*.batch_id' => 'required|exists:batches,id',
                'assignments.*.joined_at' => 'required|date',
            ]);

            $player->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'status' => $request->status,
                'joined_at' => $request->joined_at,
            ]);
            $player->syncRoles(['player']);

            // Sync batches
            $syncBatches = [];
            foreach ($request->assignments as $assignment) {
                $syncBatches[$assignment['batch_id']] = [
                    'joined_at' => $assignment['joined_at'],
                ];
            }
            $player->playerBatches()->sync($syncBatches);

            return response()->json([
                'success' => true,
                'message' => 'Player updated successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('PlayerController update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating player: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(! Auth::user()->can('player_delete'), 403);
        try {
            $player = User::findOrFail($id);
            $player->delete();

            return response()->json([
                'success' => true,
                'message' => 'Player deleted successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('PlayerController destroy error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting player: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get batches filtered by sport and level.
     */
    public function getBatches($sportId, $levelId)
    {
        abort_if(! Auth::user()->can('player_create'), 403);
        try {
            $batches = Batch::where('sport_id', $sportId)
                ->where('level_id', $levelId)
                ->where('status', 'active')
                ->get();

            return response()->json($batches);
        } catch (\Exception $e) {
            Log::error('PlayerController getBatches error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching batches: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk Delete Players
     */
    public function bulkDelete(Request $request)
    {
        abort_if(! Auth::user()->can('player_delete'), 403);
        try {
            $ids = $request->input('select', []);

            if (! is_array($ids)) {
                $ids = array_filter(explode(',', $ids));
            }

            if (count($ids) > 0) {
                $deletedCount = User::destroy($ids);

                return response()->json([
                    'success' => true,
                    'message' => $deletedCount.' players deleted successfully.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No players selected.',
            ], 400);
        } catch (\Exception $e) {
            Log::error('PlayerController bulkDelete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error performing bulk delete: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk Status Update Players
     */
    public function bulkUpdate(Request $request)
    {
        abort_if(! Auth::user()->can('player_edit'), 403);
        try {
            $validated = $request->validate([
                'select' => 'required',
                'status' => 'required|string|in:active,inactive',
            ]);

            $ids = $request->input('select', []);
            $status = $request->input('status');

            if (! is_array($ids)) {
                $ids = array_filter(explode(',', $ids));
            }

            if (count($ids) > 0) {
                $updatedCount = User::whereIn('id', $ids)->update(['status' => $status]);

                return response()->json([
                    'success' => true,
                    'message' => $updatedCount.' players updated successfully.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No valid players selected for update.',
            ], 422);
        } catch (\Exception $e) {
            Log::error('PlayerController bulkUpdate error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error performing bulk update: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export players to Excel
     */
    public function export(Request $request)
    {
        abort_if(! Auth::user()->can('player_view'), 403);
        try {
            Log::info('Export Request Data:', $request->all());

            $columns = $request->columns ?? [];

            return Excel::download(
                new PlayersExport($columns),
                'Players_'.now()->format('YmdHis').'.xlsx'
            );
        } catch (\Exception $e) {
            Log::error('PlayerController export error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error exporting players: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show Player Import Form
     */
    public function importForm()
    {
        abort_if(! Auth::user()->can('player_create'), 403);
        try {
            return view('players.importForm');
        } catch (\Exception $e) {
            Log::error('PlayerController importForm error: ' . $e->getMessage());
            abort(500, 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Import Players from Excel
     */
    public function import(Request $request)
    {
        abort_if(! Auth::user()->can('player_create'), 403);
        try {
            $request->validate([
                'players' => 'required|array',
            ]);

            $players = $request->input('players');
            $importer = new PlayersImport;

            foreach ($players as $player) {
                // Ensure row has data before processing
                if (empty(array_filter($player))) {
                    continue;
                }
                $importer->model($player);
            }

            $importedCount = $importer->getImportedCount();
            $skippedCount = $importer->getSkippedCount();
            $errors = $importer->getErrors();
            $totalCount = $importedCount + $skippedCount;

            $message = "Import process completed. Imported: {$importedCount}, Skipped/Errors: {$skippedCount}";

            return response()->json([
                'success' => true,
                'message' => $message,
                'summary' => [
                    'imported' => $importedCount,
                    'skipped' => $skippedCount,
                    'total' => $totalCount,
                ],
                'errors' => $errors,
            ]);

        } catch (\Exception $e) {
            Log::error('Player Import Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error importing file: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Read Excel File for preview
     */
    public function readExcel(Request $request)
    {
        abort_if(! Auth::user()->can('player_create'), 403);
        try {
            // Validate the uploaded file
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            ]);

            // Read data into a plain PHP array directly from the uploaded file
            $dataArray = Excel::toArray([], $request->file('file'));
            $sheetData = $dataArray[0] ?? [];

            if (empty($sheetData)) {
                throw new \Exception('The Excel file is empty.');
            }

            // Find all columns that are not entirely empty (has data)
            $maxCols = 0;
            foreach ($sheetData as $row) {
                $maxCols = max($maxCols, count($row));
            }

            $rawHeaders = $sheetData[0] ?? [];
            $nonEmptyColIndices = [];
            for ($colIdx = 0; $colIdx < $maxCols; $colIdx++) {
                $hasData = false;
                foreach ($sheetData as $row) {
                    $val = $row[$colIdx] ?? null;
                    if ($val !== null && trim((string) $val) !== '') {
                        $hasData = true;
                        break;
                    }
                }
                if ($hasData) {
                    $nonEmptyColIndices[] = $colIdx;
                }
            }

            if (empty($nonEmptyColIndices)) {
                throw new \Exception('No columns with data found in the Excel file.');
            }

            // Extract headers from the first row of the sheet
            $headers = [];
            foreach ($nonEmptyColIndices as $colIdx) {
                $headers[] = isset($rawHeaders[$colIdx]) && trim((string) $rawHeaders[$colIdx]) !== ''
                    ? trim((string) $rawHeaders[$colIdx])
                    : 'Column '.($colIdx + 1);
            }

            // Extract filtered preview rows starting from index 1 (skipping header)
            $rawRows = array_slice($sheetData, 1);
            $rows = [];
            foreach ($rawRows as $row) {
                // Check if row has any non-empty cells
                if (empty(array_filter($row, function ($v) {
                    return $v !== null && trim((string) $v) !== '';
                }))) {
                    continue;
                }
                $filteredRow = [];
                foreach ($nonEmptyColIndices as $colIdx) {
                    $filteredRow[] = $row[$colIdx] ?? '';
                }
                $rows[] = $filteredRow;
            }

            return response()->json([
                'success' => true,
                'headers' => $headers,
                'rows' => $rows,
            ]);
        } catch (\Exception $e) {
            Log::error('PlayerController readExcel error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error reading Excel file: '.$e->getMessage(),
            ], 500);
        }
    }
}
