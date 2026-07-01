<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportExportRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AllTablesSampleExport;
use App\Exports\CustomAllTablesExport;
use App\Imports\VerticalImport;
use App\Models\Batch;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Level;
use App\Models\Sport;
use App\Models\User;
use App\Models\SportsLevel;
use Illuminate\Support\Facades\DB;

class ImportExportController extends Controller
{
    public function index()
    {
        abort_if(! Auth::user()->can('setting_view'), 403);

        try {
            return view('settings.import-export');
        } catch (Exception $e) {
            Log::error('Import Export Index Error: '.$e->getMessage());

            return back()->with('error', 'Something went wrong.');
        }
    }

    public function downloadSample()
    {
        abort_if(! Auth::user()->can('setting_view'), 403);

        try {
            return Excel::download(new AllTablesSampleExport, 'all_tables_import_sample.xlsx');
        } catch (Exception $e) {
            Log::error('Download Sample Error: '.$e->getMessage());
            return back()->with('error', 'Unable to download sample file.');
        }
    }

    public function export(ImportExportRequest $request)
    {
        abort_if(! Auth::user()->can('setting_view'), 403);

        $request->validated();

        try {
            return Excel::download(new CustomAllTablesExport($request->input('columns')), 'All_Tables_Export_'.now()->format('YmdHis').'.xlsx');
        } catch (\Exception $e) {
            Log::error('Export Error: '.$e->getMessage());
            return back()->with('error', 'Unable to export data.');
        }
    }

    public function exportFields()
    {
        abort_if(! Auth::user()->can('setting_view'), 403);
        $schema = $this->getMappingSchema();
        return view('settings.export-fields-form', compact('schema'));
    }

    public function preview(ImportExportRequest $request)
    {
        abort_if(! Auth::user()->can('setting_view'), 403);

        $request->validated();

        try {
            // Read data into a plain PHP array directly from the uploaded file
            $dataArray = Excel::toArray([], $request->file('file'));
            $sheetData = $dataArray[0] ?? [];

            if (empty($sheetData)) {
                throw new \Exception('The Excel file is empty.');
            }

            $row1 = $sheetData[0] ?? []; // Table names: [Sports], '', '', [Levels], ...
            $row2 = $sheetData[1] ?? []; // Column names: name, description, status, ...

            if (empty($row2)) {
                throw new \Exception('No columns found in the Excel file.');
            }

            // Combine table names (Row 1) and column names (Row 2) to build rich headers
            $headers = [];
            $currentEntity = '';
            for ($i = 0; $i < count($row2); $i++) {
                $entityLabel = isset($row1[$i]) ? trim((string)$row1[$i]) : '';
                if ($entityLabel !== '') {
                    $currentEntity = $entityLabel;
                }

                $colName = isset($row2[$i]) ? trim((string)$row2[$i]) : '';
                if ($colName === '') {
                    $colName = 'Column ' . ($i + 1);
                }

                if ($currentEntity !== '') {
                    $headers[] = $currentEntity . ' - ' . $colName;
                } else {
                    $headers[] = $colName;
                }
            }

            // Extract data rows starting from row index 2 (skipping rows 0 and 1)
            $rawRows = array_slice($sheetData, 2);
            $rows = [];
            foreach ($rawRows as $row) {
                // Check if row has any non-empty cells
                if (empty(array_filter($row, function ($v) {
                    return $v !== null && trim((string) $v) !== '';
                }))) {
                    continue;
                }

                // Align row cells count with headers count
                $filteredRow = [];
                for ($i = 0; $i < count($headers); $i++) {
                    $filteredRow[] = $row[$i] ?? '';
                }
                $rows[] = $filteredRow;
            }

            return response()->json([
                'success' => true,
                'headers' => $headers,
                'rows' => $rows,
                'schema' => $this->getMappingSchema(),
            ]);
        } catch (\Exception $e) {
            Log::error('Import Export Preview Error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error reading Excel file: '.$e->getMessage(),
            ], 422);
        }
    }

    public function import(ImportExportRequest $request)
    {
        abort_if(! Auth::user()->can('setting_view'), 403);

        $request->validated();

        DB::beginTransaction();
        try {
            $importer = new VerticalImport();
            $importer->import($request->all());

            DB::commit();

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
            DB::rollBack();
            Log::error('Bulk import settings error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error during save: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Dynamically fetch mapping schema from model fillable attributes and relationships/pivots.
     */
    protected function getMappingSchema(): array
    {
        // 1. Sport
        $sportModel = new Sport();
        $sportFields = [];
        foreach ($sportModel->getFillable() as $field) {
            if ($field === 'slug') continue;
            $sportFields[$field] = 'Sport ' . ucfirst(str_replace('_', ' ', $field));
        }

        // 2. Level
        $levelModel = new Level();
        $levelFields = [];
        foreach ($levelModel->getFillable() as $field) {
            $levelFields[$field] = 'Level ' . ucfirst(str_replace('_', ' ', $field));
        }

        // 3. Sport Levels (Pivot table - extra headers)
        $sportsLevelFields = [
            'sport' => 'Sport',
            'level' => 'Level',
            'fees' => 'Fees',
        ];

        // 4. ExpenseCategory
        $expCatModel = new ExpenseCategory();
        $expCatFields = [];
        foreach ($expCatModel->getFillable() as $field) {
            if ($field === 'slug') continue;
            $expCatFields[$field] = 'Category ' . ucfirst(str_replace('_', ' ', $field));
        }

        // 5. Batch
        $batchModel = new Batch();
        $batchFields = [];
        foreach ($batchModel->getFillable() as $field) {
            if ($field === 'sport_id') {
                $batchFields['sport'] = 'Batch Sport';
            } elseif ($field === 'level_id') {
                $batchFields['level'] = 'Batch Level';
            } else {
                $batchFields[$field] = 'Batch ' . ucfirst(str_replace('_', ' ', $field));
            }
        }

        // 6. User (Staff)
        $userModel = new User();
        $userFields = [];
        foreach ($userModel->getFillable() as $field) {
            if (in_array($field, ['password', 'profile_picture'])) continue;
            $userFields[$field] = ucfirst(str_replace('_', ' ', $field));
        }

        // 7. Expense
        $expenseModel = new Expense();
        $expenseFields = [];
        foreach ($expenseModel->getFillable() as $field) {
            if (in_array($field, ['receipt', 'created_by'])) continue;
            if ($field === 'expense_category_id') {
                $expenseFields['category'] = 'Category';
            } else {
                $cleanLabel = str_replace('expense_', '', $field);
                $expenseFields[$field] = ucfirst(str_replace('_', ' ', $cleanLabel));
            }
        }

        // 8. Player (Extra headers / custom mapping)
        $playerFields = [
            'firstname' => 'Player First Name',
            'lastname' => 'Player Last Name',
            'email' => 'Player Email',
            'phone' => 'Player Phone',
            'gender' => 'Player Gender',
            'status' => 'Player Status',
            'joined_at' => 'Player Joined At',
            'sport' => 'Player Sport',
            'level' => 'Player Level',
            'batch' => 'Player Batch',
        ];

        return [
            'sports' => [
                'label' => 'Sports',
                'prefix' => 'sport',
                'fields' => $sportFields
            ],
            'levels' => [
                'label' => 'Levels',
                'prefix' => 'level',
                'fields' => $levelFields
            ],
            'sport_levels' => [
                'label' => 'Sport Levels',
                'prefix' => 'sport_level',
                'fields' => $sportsLevelFields
            ],
            'expense_categories' => [
                'label' => 'Expense Categories',
                'prefix' => 'exp_cat',
                'fields' => $expCatFields
            ],
            'batches' => [
                'label' => 'Batches',
                'prefix' => 'batch',
                'fields' => $batchFields
            ],
            'users' => [
                'label' => 'Users / Staff',
                'prefix' => 'user',
                'fields' => $userFields
            ],
            'expenses' => [
                'label' => 'Expenses',
                'prefix' => 'expense',
                'fields' => $expenseFields
            ],
            'players' => [
                'label' => 'Players',
                'prefix' => 'player',
                'fields' => $playerFields
            ]
        ];
    }
}
