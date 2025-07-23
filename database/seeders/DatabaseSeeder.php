<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(ActivityLogTableSeeder::class);
        $this->call(CollectionsTableSeeder::class);
        $this->call(DeliveryAgentsTableSeeder::class);
        $this->call(ExpensesTableSeeder::class);
        $this->call(FailedJobsTableSeeder::class);
        $this->call(ImportedShipmentsTableSeeder::class);
        $this->call(MigrationsTableSeeder::class);
        $this->call(ModelHasPermissionsTableSeeder::class);
        $this->call(ModelHasRolesTableSeeder::class);
        $this->call(PasswordResetTokensTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PersonalAccessTokensTableSeeder::class);
        $this->call(ProductPricesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(RoleHasPermissionsTableSeeder::class);
        $this->call(RolePermissionTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(ShipmentProductTableSeeder::class);
        $this->call(ShipmentStatusesTableSeeder::class);
        $this->call(ShipmentsTableSeeder::class);
        $this->call(ShippingCompaniesTableSeeder::class);
        $this->call(ShippingRatesTableSeeder::class);
        $this->call(SqliteSequenceTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
