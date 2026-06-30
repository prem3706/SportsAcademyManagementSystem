<?php

namespace App\Imports;

use App\Models\Sport;
use App\Models\Level;
use App\Models\SportsLevel;
use App\Models\ExpenseCategory;
use App\Models\Batch;
use App\Models\User;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class VerticalImport
{
    protected $importedCount = 0;
    protected $skippedCount = 0;
    protected $errors = [];

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getSkippedCount()
    {
        return $this->skippedCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Import settings and entities data in vertical batches.
     *
     * @param array $data
     * @return void
     */
    public function import(array $data)
    {
        // 1. Import Sports
        $sports = $data['sports'] ?? [];
        foreach ($sports as $row) {
            if (empty(array_filter($row))) continue;
            if (empty($row['name'])) {
                $this->skippedCount++;
                $this->errors[] = "Sports Section: Row is missing required field 'name'.";
                continue;
            }

            try {
                if (Sport::where('name', trim($row['name']))->exists()) {
                    $this->skippedCount++;
                    $this->errors[] = "Sports Section: Sport '{$row['name']}' already exists.";
                    continue;
                }

                Sport::create([
                    'name' => trim($row['name']),
                    'slug' => \Illuminate\Support\Str::slug(trim($row['name'])),
                    'description' => $row['description'] ?? null,
                    'status' => strtolower($row['status'] ?? '') === 'active' ? 'active' : 'inactive',
                ]);
                $this->importedCount++;
            } catch (\Exception $e) {
                $this->skippedCount++;
                $this->errors[] = "Sports Section (Name: '{$row['name']}'): " . $this->cleanErrorMessage($e);
            }
        }

        // 2. Import Levels
        $levels = $data['levels'] ?? [];
        foreach ($levels as $row) {
            if (empty(array_filter($row))) continue;
            if (empty($row['name'])) {
                $this->skippedCount++;
                $this->errors[] = "Levels Section: Row is missing required field 'name'.";
                continue;
            }

            try {
                if (Level::where('name', trim($row['name']))->exists()) {
                    $this->skippedCount++;
                    $this->errors[] = "Levels Section: Level '{$row['name']}' already exists.";
                    continue;
                }

                Level::create([
                    'name' => trim($row['name']),
                    'slug' => \Illuminate\Support\Str::slug(trim($row['name'])),
                    'status' => strtolower($row['status'] ?? '') === 'active' ? 'active' : 'inactive',
                ]);
                $this->importedCount++;
            } catch (\Exception $e) {
                $this->skippedCount++;
                $this->errors[] = "Levels Section (Name: '{$row['name']}'): " . $this->cleanErrorMessage($e);
            }
        }

        // 3. Import Sport Levels
        $sportLevels = $data['sport_levels'] ?? [];
        foreach ($sportLevels as $row) {
            if (empty(array_filter($row))) continue;
            if (empty($row['sport']) || empty($row['level'])) {
                $this->skippedCount++;
                $this->errors[] = "Sport Levels Section: Row is missing 'sport' or 'level'.";
                continue;
            }

            try {
                $sport = Sport::where('name', trim($row['sport']))->first();
                $level = Level::where('name', trim($row['level']))->first();

                if (!$sport) {
                    $this->skippedCount++;
                    $this->errors[] = "Sport Levels Section: Sport '{$row['sport']}' not found in database.";
                    continue;
                }
                if (!$level) {
                    $this->skippedCount++;
                    $this->errors[] = "Sport Levels Section: Level '{$row['level']}' not found in database.";
                    continue;
                }

                if (SportsLevel::where('sport_id', $sport->id)->where('level_id', $level->id)->exists()) {
                    $this->skippedCount++;
                    $this->errors[] = "Sport Levels Section: Mapping for Sport '{$row['sport']}' and Level '{$row['level']}' already exists.";
                    continue;
                }

                SportsLevel::create([
                    'sport_id' => $sport->id,
                    'level_id' => $level->id,
                    'fees' => floatval($row['fees'] ?? 0),
                ]);
                $this->importedCount++;
            } catch (\Exception $e) {
                $this->skippedCount++;
                $this->errors[] = "Sport Levels Section (Sport: '{$row['sport']}', Level: '{$row['level']}'): " . $this->cleanErrorMessage($e);
            }
        }

        // 4. Import Expense Categories
        $expenseCategories = $data['expense_categories'] ?? [];
        foreach ($expenseCategories as $row) {
            if (empty(array_filter($row))) continue;
            if (empty($row['name'])) {
                $this->skippedCount++;
                $this->errors[] = "Expense Categories Section: Row is missing required field 'name'.";
                continue;
            }

            try {
                if (ExpenseCategory::where('name', trim($row['name']))->exists()) {
                    $this->skippedCount++;
                    $this->errors[] = "Expense Categories Section: Category '{$row['name']}' already exists.";
                    continue;
                }

                ExpenseCategory::create([
                    'name' => trim($row['name']),
                    'slug' => \Illuminate\Support\Str::slug(trim($row['name'])),
                    'description' => $row['description'] ?? null,
                    'status' => strtolower($row['status'] ?? '') === 'active' ? true : false,
                ]);
                $this->importedCount++;
            } catch (\Exception $e) {
                $this->skippedCount++;
                $this->errors[] = "Expense Categories Section (Name: '{$row['name']}'): " . $this->cleanErrorMessage($e);
            }
        }

        // 5. Import Batches
        $batches = $data['batches'] ?? [];
        foreach ($batches as $row) {
            if (empty(array_filter($row))) continue;
            if (empty($row['name']) || empty($row['sport']) || empty($row['level'])) {
                $this->skippedCount++;
                $this->errors[] = "Batches Section (Name: '" . ($row['name'] ?? 'Unknown') . "'): Missing required fields (name, sport, level).";
                continue;
            }

            try {
                $sport = Sport::where('name', trim($row['sport']))->first();
                $level = Level::where('name', trim($row['level']))->first();

                if (!$sport) {
                    $this->skippedCount++;
                    $this->errors[] = "Batches Section (Name: '{$row['name']}'): Sport '{$row['sport']}' not found.";
                    continue;
                }
                if (!$level) {
                    $this->skippedCount++;
                    $this->errors[] = "Batches Section (Name: '{$row['name']}'): Level '{$row['level']}' not found.";
                    continue;
                }

                if (Batch::where('name', trim($row['name']))->exists()) {
                    $this->skippedCount++;
                    $this->errors[] = "Batches Section: Batch name '{$row['name']}' already exists.";
                    continue;
                }

                Batch::create([
                    'name' => trim($row['name']),
                    'capacity' => intval($row['capacity'] ?? 20),
                    'start_time' => $row['start_time'] ?? '06:00:00',
                    'end_time' => $row['end_time'] ?? '08:00:00',
                    'sport_id' => $sport->id,
                    'level_id' => $level->id,
                    'status' => strtolower($row['status'] ?? '') === 'active' ? 'active' : 'inactive',
                ]);
                $this->importedCount++;
            } catch (\Exception $e) {
                $this->skippedCount++;
                $this->errors[] = "Batches Section (Name: '{$row['name']}'): " . $this->cleanErrorMessage($e);
            }
        }

        // 6. Import Users (Staff)
        $users = $data['users'] ?? [];
        foreach ($users as $row) {
            if (empty(array_filter($row))) continue;
            $rowName = (!empty($row['firstname']) ? $row['firstname'] : '') . ' ' . (!empty($row['lastname']) ? $row['lastname'] : '');
            $rowName = trim($rowName);
            if (empty($rowName)) {
                $rowName = !empty($row['phone']) ? $row['phone'] : 'Unknown';
            }

            if (empty($row['firstname']) || empty($row['lastname']) || empty($row['email']) || empty($row['phone'])) {
                $this->skippedCount++;
                $this->errors[] = "Staff Users Section (Name: '{$rowName}'): Missing required fields (First Name, Last Name, Email, Phone).";
                continue;
            }

            try {
                $role = strtolower(trim($row['role'] ?? 'coach'));
                if (!in_array($role, ['admin', 'coach', 'manager'])) {
                    $role = 'coach';
                }

                $plainPassword = strtolower(str_replace(' ', '', trim($row['firstname']))) . '@123';

                if (User::where('email', trim($row['email']))->exists()) {
                    $this->skippedCount++;
                    $this->errors[] = "Staff Users Section (Name: '{$rowName}'): Email '{$row['email']}' is already registered.";
                    continue;
                }
                if (User::where('phone', trim($row['phone']))->exists()) {
                    $this->skippedCount++;
                    $this->errors[] = "Staff Users Section (Name: '{$rowName}'): Phone '{$row['phone']}' is already registered.";
                    continue;
                }

                // Check if the role exists
                if (!Role::where('name', $role)->exists()) {
                    $this->skippedCount++;
                    $this->errors[] = "Staff Users Section (Name: '{$rowName}'): Role '{$role}' does not exist in the database.";
                    continue;
                }

                DB::transaction(function () use ($row, $plainPassword, $role) {
                    $user = User::create([
                        'firstname' => trim($row['firstname']),
                        'lastname' => trim($row['lastname']),
                        'email' => trim($row['email']),
                        'phone' => trim($row['phone']),
                        'gender' => strtolower($row['gender'] ?? '') === 'female' ? 'female' : 'male',
                        'password' => $plainPassword,
                        'status' => strtolower($row['status'] ?? '') === 'active' ? 'active' : 'inactive',
                        'joined_at' => !empty($row['joined_at']) ? date('Y-m-d', strtotime($row['joined_at'])) : now()->toDateString(),
                    ]);

                    $user->syncRoles([$role]);
                });
                $this->importedCount++;
            } catch (\Exception $e) {
                $this->skippedCount++;
                $this->errors[] = "Staff Users Section (Name: '{$rowName}'): " . $this->cleanErrorMessage($e);
            }
        }

        // 7. Import Expenses
        $expenses = $data['expenses'] ?? [];
        foreach ($expenses as $row) {
            if (empty(array_filter($row))) continue;
            if (empty($row['category']) || empty($row['amount'])) {
                $this->skippedCount++;
                $this->errors[] = "Expenses Section: Missing category or amount.";
                continue;
            }

            try {
                $category = ExpenseCategory::where('name', trim($row['category']))->first();
                if (!$category) {
                    $category = ExpenseCategory::create([
                        'name' => trim($row['category']),
                        'slug' => \Illuminate\Support\Str::slug(trim($row['category'])),
                        'status' => true,
                    ]);
                }

                Expense::create([
                    'expense_category_id' => $category->id,
                    'expense_date' => !empty($row['expense_date']) ? date('Y-m-d', strtotime($row['expense_date'])) : now()->toDateString(),
                    'amount' => floatval($row['amount']),
                    'payment_mode' => $row['payment_mode'] ?? 'Cash',
                    'reference_no' => $row['reference_no'] ?? null,
                    'description' => $row['description'] ?? null,
                ]);
                $this->importedCount++;
            } catch (\Exception $e) {
                $this->skippedCount++;
                $this->errors[] = "Expenses Section (Category: '{$row['category']}'): " . $this->cleanErrorMessage($e);
            }
        }

        // 8. Import Players
        $players = $data['players'] ?? [];
        foreach ($players as $row) {
            if (empty(array_filter($row))) continue;
            $rowName = (!empty($row['firstname']) ? $row['firstname'] : '') . ' ' . (!empty($row['lastname']) ? $row['lastname'] : '');
            $rowName = trim($rowName);
            if (empty($rowName)) {
                $rowName = !empty($row['phone']) ? $row['phone'] : 'Unknown';
            }

            if (empty($row['firstname']) || empty($row['lastname']) || empty($row['phone'])) {
                $this->skippedCount++;
                $this->errors[] = "Players Section (Name: '{$rowName}'): Missing required fields (First Name, Last Name, Phone).";
                continue;
            }

            try {
                $email = !empty($row['email']) ? trim($row['email']) : null;
                $phone = trim($row['phone']);

                $plainPassword = strtolower(str_replace(' ', '', trim($row['firstname']))) . '@123';

                if ($email && User::where('email', $email)->exists()) {
                    $this->skippedCount++;
                    $this->errors[] = "Players Section (Name: '{$rowName}'): Email '{$email}' is already registered.";
                    continue;
                }
                if (User::where('phone', $phone)->exists()) {
                    $this->skippedCount++;
                    $this->errors[] = "Players Section (Name: '{$rowName}'): Phone number '{$phone}' is already registered.";
                    continue;
                }

                // Check if the 'player' role exists
                if (!Role::where('name', 'player')->exists()) {
                    $this->skippedCount++;
                    $this->errors[] = "Players Section (Name: '{$rowName}'): The player role does not exist in the database.";
                    continue;
                }

                DB::transaction(function () use ($row, $email, $phone, $plainPassword) {
                    $player = User::create([
                        'firstname' => trim($row['firstname']),
                        'lastname' => trim($row['lastname']),
                        'email' => $email,
                        'phone' => $phone,
                        'gender' => strtolower($row['gender'] ?? '') === 'female' ? 'female' : 'male',
                        'password' => $plainPassword,
                        'status' => strtolower($row['status'] ?? '') === 'active' ? 'active' : 'inactive',
                        'joined_at' => !empty($row['joined_at']) ? date('Y-m-d', strtotime($row['joined_at'])) : now()->toDateString(),
                    ]);
                    $player->assignRole('player');

                    if (!empty($row['batch']) && !empty($row['sport']) && !empty($row['level'])) {
                        $sport = Sport::where('name', trim($row['sport']))->first();
                        $level = Level::where('name', trim($row['level']))->first();

                        if ($sport && $level) {
                            $batch = Batch::where('name', trim($row['batch']))
                                ->where('sport_id', $sport->id)
                                ->where('level_id', $level->id)
                                ->first();

                            if ($batch) {
                                if ($batch->isFull($player->id)) {
                                    throw new \Exception("Batch '{$batch->name}' has reached its maximum capacity of {$batch->capacity} players.");
                                }
                                if (!$batch->players()->where('users.id', $player->id)->exists()) {
                                    $batch->players()->attach($player->id, [
                                        'joined_at' => $player->joined_at ?: now()->toDateString(),
                                    ]);
                                }
                            }
                        }
                    }
                });
                $this->importedCount++;
            } catch (\Exception $e) {
                $this->skippedCount++;
                $this->errors[] = "Players Section (Name: '{$rowName}'): " . $this->cleanErrorMessage($e);
            }
        }
    }

    /**
     * Clean database raw exception details for user-friendliness.
     *
     * @param \Exception $e
     * @return string
     */
    protected function cleanErrorMessage(\Exception $e)
    {
        $message = $e->getMessage();
        if ($e instanceof \Illuminate\Database\QueryException) {
            $pos = strpos($message, ' (Connection:');
            if ($pos !== false) {
                $message = substr($message, 0, $pos);
            } else {
                $posSql = strpos($message, ' (SQL:');
                if ($posSql !== false) {
                    $message = substr($message, 0, $posSql);
                }
            }
        }
        return $message;
    }
}
