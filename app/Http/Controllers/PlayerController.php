<?php

namespace App\Http\Controllers;

use App\DataTables\PlayersDataTable;
use App\Exports\PlayerSampleExport;
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

        $sports = Sport::where('status', 'active')->get();
        $levels = Level::where('status', 'active')->get();
        $batches = Batch::where('status', 'active')->get();

        return $dataTable->render('players.index', compact('sports', 'levels', 'batches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(! Auth::user()->can('player_create'), 403);

        $sports = Sport::where('status', 'active')->get();

        return view('players.addPlayerForm', compact('sports'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(! Auth::user()->can('player_create'), 403);

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

        $player = User::findOrFail($id);
        $sports = Sport::where('status', 'active')->get();

        // Load player's batches with sport and level
        $playerBatches = $player->playerBatches()->with(['sport.levels', 'level'])->get();
        // Log::info($playerBatches);

        return view('players.editPlayerForm', compact('player', 'sports', 'playerBatches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(! Auth::user()->can('player_edit'), 403);

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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(! Auth::user()->can('player_delete'), 403);

        $player = User::findOrFail($id);
        $player->delete();

        return response()->json([
            'success' => true,
            'message' => 'Player deleted successfully.',
        ]);
    }

    /**
     * Get batches filtered by sport and level.
     */
    public function getBatches($sportId, $levelId)
    {
        abort_if(! Auth::user()->can('player_create'), 403);

        $batches = Batch::where('sport_id', $sportId)
            ->where('level_id', $levelId)
            ->where('status', 'active')
            ->get();

        return response()->json($batches);
    }

    /**
     * Bulk Delete Players
     */
    public function bulkDelete(Request $request)
    {
        abort_if(! Auth::user()->can('player_delete'), 403);

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
    }

    /**
     * Bulk Status Update Players
     */
    public function bulkUpdate(Request $request)
    {
        abort_if(! Auth::user()->can('player_edit'), 403);

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
    }

    /**
     * Export players to Excel
     */
    public function export(Request $request)
    {
        abort_if(! Auth::user()->can('player_view'), 403);

        Log::info('Export Request Data:', $request->all());

        $columns = $request->columns ?? [];

        return Excel::download(
            new PlayersExport($columns),
            'Players_'.now()->format('YmdHis').'.xlsx'
        );
    }

    /**
     * Show Player Import Form
     */
    public function importForm()
    {
        abort_if(! Auth::user()->can('player_create'), 403);

        return view('players.importForm');
    }



    /**
     * Import Players from Excel
     */
    public function import(Request $request)
    {
        abort_if(! Auth::user()->can('player_create'), 403);

        $request->validate([
            'players' => 'required|array',
        ]);

        try {
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
            ], 422);
        }
    }

    public function readExcel(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
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
            return response()->json([
                'success' => false,
                'message' => 'Error reading Excel file: '.$e->getMessage(),
            ], 422);
        }
    }
}
