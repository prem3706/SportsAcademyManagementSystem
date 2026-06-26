<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ImportExportPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_with_permission_can_preview_excel_and_get_schema()
    {
        // Setup permissions and user
        $permission = Permission::firstOrCreate([
            'name' => 'setting_view',
            'guard_name' => 'web',
            'module_name' => 'settings',
        ]);
        $admin = User::factory()->create();
        $admin->givePermissionTo($permission);

        // Create dummy excel data matching the AllTablesSampleExport structure
        $data = [
            [
                '[Sports]', '', '', 
                '[Levels]', '', 
                '[Sport Levels]', '', '', 
                '[Expense Categories]', '', '', 
                '[Batches]', '', '', '', '', '', '', 
                '[Users]', '', '', '', '', '', '', '', 
                '[Expenses]', '', '', '', '', '', 
                '[Players]'
            ],
            [
                'name', 'description', 'status',
                'name', 'status',
                'sport', 'level', 'fees',
                'name', 'description', 'status',
                'name', 'capacity', 'start_time', 'end_time', 'sport', 'level', 'status',
                'firstname', 'lastname', 'email', 'phone', 'gender', 'role', 'status', 'joined_at',
                'category', 'expense_date', 'amount', 'payment_mode', 'reference_no', 'description',
                'firstname', 'lastname', 'email', 'phone', 'gender', 'status', 'joined_at', 'sport', 'level', 'batch'
            ],
            [
                'Football', 'Football Academy', 'active',
                'Beginner', 'active',
                'Football', 'Beginner', '500',
                'Equipment', 'Sports Eq.', 'active',
                'Football Morning', '20', '06:00:00', '08:00:00', 'Football', 'Beginner', 'active',
                'John', 'Coach', 'john.coach@example.com', '9876543210', 'male', 'coach', 'active', '2026-06-01',
                'Equipment', '2026-06-25', '500', 'Cash', '', 'Purchased footballs',
                'Bobby', 'Player', 'bobby@example.com', '1234567890', 'male', 'active', '2026-06-26', 'Football', 'Beginner', 'Football Morning'
            ]
        ];

        // Store to temporary Excel file using Maatwebsite Excel
        Excel::fake();
        Excel::shouldReceive('toArray')
            ->once()
            ->andReturn([$data]);

        // Upload fake file
        $file = UploadedFile::fake()->create('all_tables_import_sample.xlsx', 10);

        $response = $this->actingAs($admin)->postJson(route('import.export.preview'), [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'headers',
            'rows',
            'schema' => [
                'Sports',
                'Levels',
                'Sport Levels',
                'Expense Categories',
                'Batches',
                'Users / Staff',
                'Expenses',
                'Players',
            ]
        ]);

        $json = $response->json();
        $this->assertTrue($json['success']);
        
        // Assert header transformation
        $this->assertContains('[Sports] - name', $json['headers']);
        $this->assertContains('[Sports] - description', $json['headers']);
        $this->assertContains('[Sports] - status', $json['headers']);
        $this->assertContains('[Levels] - name', $json['headers']);
        
        // Assert schema prefixes and fields
        $this->assertEquals('sport', $json['schema']['Sports']['prefix']);
        $this->assertEquals('Sport Name', $json['schema']['Sports']['fields']['name']);
        $this->assertEquals('Sport Description', $json['schema']['Sports']['fields']['description']);
        $this->assertEquals('Sport Status', $json['schema']['Sports']['fields']['status']);
    }
}
