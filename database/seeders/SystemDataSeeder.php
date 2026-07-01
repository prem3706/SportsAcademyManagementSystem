<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SystemDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Roles
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'manager']);
        Role::firstOrCreate(['name' => 'coach']);
        Role::firstOrCreate(['name' => 'player']);

        // 2. Seed Settings
        Setting::create([
            'id' => 1,
            'allow_penalty' => true,
            'penalty_days' => 10,
            'penalty_type' => 'fixed',
            'penalty_amount' => 100.00,
            'discount_type' => 'percentage',
            'discount_monthly' => 0.00,
            'discount_quarterly' => 5.00,
            'discount_half_yearly' => 10.00,
            'discount_yearly' => 15.00,
        ]);

        // 3. Seed Admin User
        $admin = User::create([
            'firstname' => 'Academy',
            'lastname' => 'Admin',
            'email' => 'admin@sams.com',
            'password' => 'admin123',
            'phone' => '9876543210',
            'gender' => 'male',
            'status' => 'active',
            'joined_at' => now(),
            'role' => 'admin',
        ]);

        // 4. Permissions definition
        $permissions = [
            'Dashboard' => [
                'dashboard_view',
            ],
            'Player' => [
                'player_view',
                'player_create',
                'player_edit',
                'player_delete',
            ],
            'Expense Category' => [
                'expense_category_view',
                'expense_category_create',
                'expense_category_edit',
                'expense_category_delete',
            ],
            'Expense' => [
                'expense_view',
                'expense_create',
                'expense_edit',
                'expense_delete',
            ],
            'Sport' => [
                'sport_view',
                'sport_create',
                'sport_edit',
                'sport_delete',
            ],
            'Level' => [
                'level_view',
                'level_create',
                'level_edit',
                'level_delete',
            ],
            'Sports Level' => [
                'sports_level_view',
                'sports_level_create',
                'sports_level_edit',
                'sports_level_delete',
            ],
            'Batch' => [
                'batch_view',
                'batch_create',
                'batch_edit',
                'batch_delete',
            ],
            'Fee' => [
                'fee_view',
                'fee_create',
                'fee_edit',
                'fee_delete',
            ],
            'User' => [
                'user_view',
                'user_create',
                'user_edit',
                'user_delete',
            ],
            'Setting' => [
                'setting_view',
                'setting_create',
                'setting_edit',
                'setting_delete',
            ],
        ];

        foreach ($permissions as $moduleName => $modulePermissions) {
            foreach ($modulePermissions as $permission) {
                Permission::updateOrCreate(
                    ['name' => $permission],
                    ['module_name' => $moduleName]
                );
            }
        }

        // 5. Admin Role Permissions
        $adminRole = Role::findByName('admin');
        $adminRole->syncPermissions(
            Permission::pluck('name')->toArray()
        );

        // 6. Manager Role Permissions
        $managerRole = Role::findByName('manager');
        $managerRole->syncPermissions([
            'dashboard_view',
            'player_view',
            'player_create',
            'player_edit',
            'player_delete',
            'expense_category_view',
            'expense_category_create',
            'expense_category_edit',
            'expense_category_delete',
            'expense_view',
            'expense_create',
            'expense_edit',
            'expense_delete',
            'sport_view',
            'sport_create',
            'sport_edit',
            'level_view',
            'level_create',
            'level_edit',
            'sports_level_view',
            'sports_level_create',
            'sports_level_edit',
            'batch_view',
            'batch_create',
            'batch_edit',
            'batch_delete',
            'fee_view',
            'fee_create',
            'fee_edit',
            'user_view',
            'setting_view',
            'setting_edit',
        ]);

        // 7. Coach Role Permissions
        $coachRole = Role::findByName('coach');
        $coachRole->syncPermissions([
            'dashboard_view',
            'player_view',
            'player_edit',
            'sport_view',
            'level_view',
            'sports_level_view',
            'batch_view',
            'fee_view',
        ]);

        // 8. Player Role Permissions
        $playerRole = Role::findByName('player');
        $playerRole->syncPermissions([
            'dashboard_view',
            'batch_view',
            'sport_view',
            'level_view',
        ]);
    }
}
