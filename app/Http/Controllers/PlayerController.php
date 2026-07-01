<?php

namespace App\Http\Controllers;

use App\DataTables\PlayersDataTable;
use App\Exports\PlayersExport;
use App\Imports\PlayersImport;
use App\Models\Batch;
use App\Models\Level;
use App\Models\Sport;
use App\Http\Requests\PlayerRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use Nette\Schema\ValidationException;

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
            Log::error('PlayerController index error: '.$e->getMessage());
            abort(500, 'Something went wrong: '.$e->getMessage());
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
            Log::error('PlayerController create error: '.$e->getMessage());
            abort(500, 'Something went wrong: '.$e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlayerRequest $request)
    {
        abort_if(! Auth::user()->can('player_create'), 403);
        try {
            $validated = $request->validated();

            // Ensure the 'player' role exists
            if (!Role::where('name', 'player')->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The player role does not exist. Please create or seed roles first.',
                ], 422);
            }

            // Check if any of the target batches are full
            foreach ($request->assignments as $assignment) {
                $batch = Batch::findOrFail($assignment['batch_id']);
                if ($batch->isFull()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Batch '{$batch->name}' has reached its maximum capacity of {$batch->capacity} players.",
                    ], 422);
                }
            }

            // Generate password: firstname@123 (lowercase)
            $plainPassword = strtolower(str_replace(' ', '', $request->firstname)).'@123';

            DB::transaction(function () use ($request, $plainPassword) {
                // Create user as active player
                $player = User::create([
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => $plainPassword,
                    'gender' => $request->gender,
                    'status' => 'active',
                    'joined_at' => $request->joined_at,
                    'role' => 'player',
                ]);

                // Attach player to all selected batches
                foreach ($request->assignments as $assignment) {
                    $batch = Batch::findOrFail($assignment['batch_id']);
                    $batch->players()->attach($player->id, [
                        'joined_at' => $assignment['joined_at'],
                    ]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Player created successfully.',
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('PlayerController store error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error creating player: '.$e->getMessage(),
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
            Log::error('PlayerController edit error: '.$e->getMessage());
            abort(500, 'Something went wrong: '.$e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlayerRequest $request, string $id)
    {
        abort_if(! Auth::user()->can('player_edit'), 403);
        try {
            $player = User::findOrFail($id);

            $validatedData = $request->validated();

            // Ensure the 'player' role exists
            if (!Role::where('name', 'player')->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The player role does not exist.',
                ], 422);
            }

            // Check if any of the target batches are full (excluding this player)
            foreach ($request->assignments as $assignment) {
                $batch = Batch::findOrFail($assignment['batch_id']);
                if ($batch->isFull($player->id)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Batch '{$batch->name}' has reached its maximum capacity of {$batch->capacity} players.",
                    ], 422);
                }
            }

            DB::transaction(function () use ($player, $request) {
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
            });

            return response()->json([
                'success' => true,
                'message' => 'Player updated successfully.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('PlayerController update error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating player: '.$e->getMessage(),
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
            Log::error('PlayerController destroy error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting player: '.$e->getMessage(),
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
            Log::error('PlayerController getBatches error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching batches: '.$e->getMessage(),
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        return handleBulkDelete($request, User::class, 'players', 'player_delete');
    }

    public function bulkUpdate(Request $request)
    {
        return handleBulkUpdate($request, User::class, 'players', 'player_edit');
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
    public function import(PlayerRequest $request)
    {
        abort_if(! Auth::user()->can('player_create'), 403);

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

    public function readExcel(PlayerRequest $request)
    {

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


