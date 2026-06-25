<?php

namespace App\Exports;

use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PlayersExport implements FromCollection, WithHeadings
{
    protected $columns;

    public function __construct($columns)
    {
        $this->columns = $columns;
    }

    public function collection()
    {
        return User::role('player')
            ->with(['playerBatches.sport'])
            ->get()
            ->map(function ($user) {

                $data = [];

                if (in_array('firstname', $this->columns)) {
                    $data['FirstName'] = $user->firstname;
                }
                if (in_array('lastname', $this->columns)) {
                    $data['LastName'] = $user->lastname;
                }

                if (in_array('email', $this->columns)) {
                    $data['Email'] = $user->email;
                }

                if (in_array('phone', $this->columns)) {
                    $data['Phone'] = $user->phone;
                }
                if (in_array('gender', $this->columns)) {
                    $data['Gender'] = $user->gender;
                }

                if (in_array('joined_at', $this->columns)) {
                    $data['Joined_at'] = $user->joined_at
                        ? Carbon::parse($user->joined_at)->format('Y-m-d')
                        : null;
                }

                if (in_array('sport', $this->columns)) {
                    $data['Sport'] = $user->playerBatches
                        ->map(function ($batch) {
                            return $batch->sport
                                ? $batch->sport->name
                                : '';
                        })
                        ->filter()
                        ->unique()
                        ->implode(', ');
                }
                if (in_array('level', $this->columns)) {
                    $data['Level'] = $user->playerBatches
                        ->map(function ($batch) {
                            return $batch->level
                                ? $batch->level->name
                                : '';
                        })
                        ->filter()
                        ->unique()
                        ->implode(', ');
                }

                if (in_array('batch', $this->columns)) {
                    $data['Batch'] = $user->playerBatches
                        ->pluck('name')
                        ->filter()
                        ->unique()
                        ->implode(', ');
                }
                if (in_array('status', $this->columns)) {
                    $data['Status'] = $user->status;
                }

                return $data;
            });
    }

    public function headings(): array
    {
        $headings = [];

        if (in_array('firstname', $this->columns)) {
            $headings[] = 'FirstName';
        }
        if (in_array('lastname', $this->columns)) {
            $headings[] = 'LastName';
        }

        if (in_array('email', $this->columns)) {
            $headings[] = 'Email';
        }

        if (in_array('phone', $this->columns)) {
            $headings[] = 'Phone';
        }
        if (in_array('gender', $this->columns)) {
            $headings[] = 'Gender';
        }

        if (in_array('joined_at', $this->columns)) {
            $headings[] = 'Joined_at';
        }

        if (in_array('sport', $this->columns)) {
            $headings[] = 'Sport';
        }
        if (in_array('level', $this->columns)) {
            $headings[] = 'Level';
        }

        if (in_array('batch', $this->columns)) {
            $headings[] = 'Batch';
        }
        if (in_array('status', $this->columns)) {
            $headings[] = 'Status';
        }

        return $headings;
    }
}
