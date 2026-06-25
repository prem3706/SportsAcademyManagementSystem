<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Sport;
use App\Models\Level;
use App\Models\Batch;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PlayersImport implements ToModel, WithHeadingRow
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
     * Parse row and create player model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // We will keep track of the row name/identifier for the error message
        $rowName = (!empty($row['first_name']) ? $row['first_name'] : '') . ' ' . (!empty($row['last_name']) ? $row['last_name'] : '');
        $rowName = trim($rowName);
        if (empty($rowName)) {
            $rowName = !empty($row['phone']) ? $row['phone'] : 'Unknown';
        }

        try {
            // Check for required fields
            if (empty($row['first_name']) || empty($row['last_name']) || empty($row['phone']) || empty($row['joined_at'])) {
                $this->skippedCount++;
                $this->errors[] = "Row for '{$rowName}': Missing required field(s) (First Name, Last Name, Phone, and Joined At are required).";
                return null;
            }

            // Clean values
            $firstname = trim($row['first_name']);
            $lastname = trim($row['last_name']);
            $phone = trim($row['phone']);
            $email = !empty($row['email']) ? trim($row['email']) : null;

            // Skip if email or phone is already taken to avoid duplicate key exceptions
            $duplicatePhone = User::where('phone', $phone)->exists();
            if ($duplicatePhone) {
                $this->skippedCount++;
                $this->errors[] = "Row for '{$rowName}': Phone number '{$phone}' is already registered.";
                return null;
            }

            if ($email) {
                $duplicateEmail = User::where('email', $email)->exists();
                if ($duplicateEmail) {
                    $this->skippedCount++;
                    $this->errors[] = "Row for '{$rowName}': Email '{$email}' is already registered.";
                    return null;
                }
            }

            // Process Gender Format: male, female, other (case-insensitive)
            $gender = !empty($row['gender']) ? strtolower(trim($row['gender'])) : null;
            if ($gender && !in_array($gender, ['male', 'female', 'other'])) {
                $gender = null;
            }

            // Process Status: active, inactive (case-insensitive)
            $status = 'active';
            if (isset($row['status']) && trim($row['status']) !== '') {
                $statusVal = strtolower(trim($row['status']));
                if (in_array($statusVal, ['active', 'inactive'])) {
                    $status = $statusVal;
                } else {
                    $this->skippedCount++;
                    $this->errors[] = "Row for '{$rowName}': Status must be either 'active' or 'inactive'.";
                    return null;
                }
            }

            // Process Joined At Date: YYYY-MM-DD
            $joinedAtRaw = $row['joined_at'];
            if (is_numeric($joinedAtRaw)) {
                try {
                    $joinedAt = Date::excelToDateTimeObject($joinedAtRaw)->format('Y-m-d');
                } catch (\Exception $e) {
                    $joinedAt = now()->format('Y-m-d');
                }
            } else {
                $timestamp = strtotime(trim($joinedAtRaw));
                if (!$timestamp) {
                    $this->skippedCount++;
                    $this->errors[] = "Row for '{$rowName}': Joined At date '{$joinedAtRaw}' is invalid. Please format as YYYY-MM-DD.";
                    return null;
                }
                $joinedAt = date('Y-m-d', $timestamp);
            }

            // Resolve Sport, Level, Batch assignments if provided
            $resolvedBatches = [];

            $hasSport = !empty($row['sport']);
            $hasLevel = !empty($row['level']);
            $hasBatch = !empty($row['batch']);

            if ($hasSport || $hasLevel || $hasBatch) {
                if (!$hasSport || !$hasLevel || !$hasBatch) {
                    $this->skippedCount++;
                    $this->errors[] = "Row for '{$rowName}': To assign a player to batches, you must map and provide all three fields: Sport, Level, and Batch.";
                    return null;
                }

                // Split comma-separated values
                $sportNames = array_map('trim', explode(',', $row['sport']));
                $levelNames = array_map('trim', explode(',', $row['level']));
                $batchNames = array_map('trim', explode(',', $row['batch']));

                // Loop through each batch name to find a match
                foreach ($batchNames as $batchName) {
                    if (empty($batchName)) {
                        continue;
                    }

                    // Find active batches matching the name
                    $possibleBatches = Batch::where('name', 'like', $batchName)
                        ->where('status', 'active')
                        ->with(['sport', 'level'])
                        ->get();

                    if ($possibleBatches->isEmpty()) {
                        $this->skippedCount++;
                        $this->errors[] = "Row for '{$rowName}': Active Batch '{$batchName}' not found in the system.";
                        return null;
                    }

                    $matchedBatch = null;

                    foreach ($possibleBatches as $b) {
                        $bSportName = $b->sport ? $b->sport->name : '';
                        $bLevelName = $b->level ? $b->level->name : '';

                        // Check if the batch's sport name and level name are within the sportNames and levelNames arrays (case-insensitive)
                        $sportMatched = false;
                        foreach ($sportNames as $sName) {
                            if (strcasecmp($sName, $bSportName) === 0) {
                                $sportMatched = true;
                                break;
                            }
                        }

                        $levelMatched = false;
                        foreach ($levelNames as $lName) {
                            if (strcasecmp($lName, $bLevelName) === 0) {
                                $levelMatched = true;
                                break;
                            }
                        }

                        if ($sportMatched && $levelMatched) {
                            $matchedBatch = $b;
                            break;
                        }
                    }

                    if (!$matchedBatch) {
                        $this->skippedCount++;
                        $this->errors[] = "Row for '{$rowName}': Batch '{$batchName}' could not be matched with the provided Sport(s) and Level(s).";
                        return null;
                    }

                    $resolvedBatches[] = $matchedBatch;
                }
            }

            // Generate password: lowercase firstname + @123
            $plainPassword = strtolower(str_replace(' ', '', $firstname)) . '@123';

            // Create player
            $player = User::create([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'phone' => $phone,
                'gender' => $gender,
                'password' => $plainPassword,
                'role' => 'player',
                'status' => $status,
                'joined_at' => $joinedAt,
            ]);

            // Assign Spatie player role
            $player->assignRole('player');

            // Attach to batches if resolved
            foreach ($resolvedBatches as $batch) {
                $batch->players()->attach($player->id, [
                    'joined_at' => $joinedAt,
                ]);
            }

            $this->importedCount++;
        } catch (\Exception $e) {
            $this->skippedCount++;
            $this->errors[] = "Row for '{$rowName}': Error occurred: " . $e->getMessage();
        }

        return null;
    }
}
