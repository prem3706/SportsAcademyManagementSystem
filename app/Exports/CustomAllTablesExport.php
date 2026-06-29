<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use App\Models\Sport;
use App\Models\Level;
use App\Models\SportsLevel;
use App\Models\ExpenseCategory;
use App\Models\Batch;
use App\Models\User;
use App\Models\Expense;

class CustomAllTablesExport implements FromArray
{
    protected $selectedColumns;

    public function __construct(array $selectedColumns)
    {
        $this->selectedColumns = $selectedColumns;
    }

    public function array(): array
    {
        // 1. Define configuration for all supported tables, query builder functions and mapper callbacks
        $tablesConfig = [
            'sports' => [
                'header' => '[Sports]',
                'model' => Sport::class,
                'query' => function() { return Sport::all(); },
                'map_row' => function($item, $cols) {
                    $row = [];
                    foreach ($cols as $col) {
                        $row[] = $item->{$col} ?? '';
                    }
                    return $row;
                }
            ],
            'levels' => [
                'header' => '[Levels]',
                'model' => Level::class,
                'query' => function() { return Level::all(); },
                'map_row' => function($item, $cols) {
                    $row = [];
                    foreach ($cols as $col) {
                        $row[] = $item->{$col} ?? '';
                    }
                    return $row;
                }
            ],
            'sport_levels' => [
                'header' => '[Sport Levels]',
                'model' => SportsLevel::class,
                'query' => function() { return SportsLevel::with(['sport', 'level'])->get(); },
                'map_row' => function($item, $cols) {
                    $row = [];
                    foreach ($cols as $col) {
                        if ($col === 'sport') {
                            $row[] = $item->sport->name ?? '';
                        } elseif ($col === 'level') {
                            $row[] = $item->level->name ?? '';
                        } else {
                            $row[] = $item->{$col} ?? '';
                        }
                    }
                    return $row;
                }
            ],
            'expense_categories' => [
                'header' => '[Expense Categories]',
                'model' => ExpenseCategory::class,
                'query' => function() { return ExpenseCategory::all(); },
                'map_row' => function($item, $cols) {
                    $row = [];
                    foreach ($cols as $col) {
                        $row[] = $item->{$col} ?? '';
                    }
                    return $row;
                }
            ],
            'batches' => [
                'header' => '[Batches]',
                'model' => Batch::class,
                'query' => function() { return Batch::with(['sport', 'level'])->get(); },
                'map_row' => function($item, $cols) {
                    $row = [];
                    foreach ($cols as $col) {
                        if ($col === 'sport') {
                            $row[] = $item->sport->name ?? '';
                        } elseif ($col === 'level') {
                            $row[] = $item->level->name ?? '';
                        } else {
                            $row[] = $item->{$col} ?? '';
                        }
                    }
                    return $row;
                }
            ],
            'users' => [
                'header' => '[Users]',
                'model' => User::class,
                'query' => function() { return User::whereIn('role', ['admin', 'coach', 'manager'])->get(); },
                'map_row' => function($item, $cols) {
                    $row = [];
                    foreach ($cols as $col) {
                        $row[] = $item->{$col} ?? '';
                    }
                    return $row;
                }
            ],
            'expenses' => [
                'header' => '[Expenses]',
                'model' => Expense::class,
                'query' => function() { return Expense::with('category')->get(); },
                'map_row' => function($item, $cols) {
                    $row = [];
                    foreach ($cols as $col) {
                        if ($col === 'category') {
                            $row[] = $item->category->name ?? '';
                        } else {
                            $row[] = $item->{$col} ?? '';
                        }
                    }
                    return $row;
                }
            ],
            'players' => [
                'header' => '[Players]',
                'model' => User::class,
                'query' => function() { return User::where('role', 'player')->with(['playerBatches.sport', 'playerBatches.level'])->get(); },
                'map_row' => function($item, $cols) {
                    $row = [];
                    foreach ($cols as $col) {
                        if ($col === 'sport') {
                            $row[] = $item->playerBatches->map(function($b) { return $b->sport->name ?? ''; })->filter()->unique()->implode(', ');
                        } elseif ($col === 'level') {
                            $row[] = $item->playerBatches->map(function($b) { return $b->level->name ?? ''; })->filter()->unique()->implode(', ');
                        } elseif ($col === 'batch') {
                            $row[] = $item->playerBatches->pluck('name')->filter()->unique()->implode(', ');
                        } else {
                            $row[] = $item->{$col} ?? '';
                        }
                    }
                    return $row;
                }
            ],
        ];

        // 2. Build the top two metadata rows (Table Identifiers & Headers)
        $row1 = [];
        $row2 = [];
        $selectedTables = [];

        foreach ($tablesConfig as $key => $config) {
            if (isset($this->selectedColumns[$key]) && !empty($this->selectedColumns[$key])) {
                $cols = $this->selectedColumns[$key];
                $selectedTables[$key] = [
                    'config' => $config,
                    'cols' => $cols,
                    'data' => $config['query'](),
                ];

                // Append table identifier (bracketed header) to row 1, rest padded with empty spaces
                $row1[] = $config['header'];
                for ($i = 1; $i < count($cols); $i++) {
                    $row1[] = '';
                }

                // Append column header names to row 2
                foreach ($cols as $col) {
                    $row2[] = $col;
                }
            }
        }

        if (empty($selectedTables)) {
            return [];
        }

        $result = [$row1, $row2];

        // 3. Find the maximum count of records across the selected models
        $maxCount = 0;
        foreach ($selectedTables as $tableData) {
            $maxCount = max($maxCount, count($tableData['data']));
        }

        // 4. Construct horizontal data rows aligned by index
        for ($index = 0; $index < $maxCount; $index++) {
            $dataRow = [];
            foreach ($selectedTables as $key => $tableData) {
                $cols = $tableData['cols'];
                $config = $tableData['config'];
                $items = $tableData['data'];

                if ($index < count($items)) {
                    $item = $items[$index];
                    $mapped = $config['map_row']($item, $cols);
                    foreach ($mapped as $val) {
                        $dataRow[] = $val;
                    }
                } else {
                    // Pad with empty cells
                    for ($i = 0; $i < count($cols); $i++) {
                        $dataRow[] = '';
                    }
                }
            }
            $result[] = $dataRow;
        }

        return $result;
    }
}
