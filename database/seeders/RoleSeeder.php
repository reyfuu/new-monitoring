<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $dosen = Role::firstOrCreate(['name' => 'dosen']);
        $mahasiswa = Role::firstOrCreate(['name' => 'mahasiswa']);
        $kaprodi = Role::firstOrCreate(['name' => 'ka_prodi']);

        // **PASTIKAN** super_admin punya semua permissions
        $allPermissions = Permission::all();
        if ($allPermissions->count() > 0) {
            $superAdmin->syncPermissions($allPermissions);
            $this->command->info("Assigned {$allPermissions->count()} permissions to super_admin");
            
            // Permissions untuk dosen
            $dosenPermissions = [
                'View:Bimbingan', 'ViewAny:Bimbingan', 'Create:Bimbingan', 'Update:Bimbingan',
                'View:Laporan', 'ViewAny:Laporan', 'Update:Laporan',
                'View:LaporanMingguan', 'ViewAny:LaporanMingguan', 'Update:LaporanMingguan',
                'View:Dashboard', 'View:DashboardStats',
            ];
            $dosen->syncPermissions($dosenPermissions);
            $this->command->info("Assigned permissions to dosen");

            // Permissions untuk mahasiswa
            $mahasiswaPermissions = [
                'View:Bimbingan', 'ViewAny:Bimbingan', 'Create:Bimbingan', 'Update:Bimbingan', 'Delete:Bimbingan',
                'View:Laporan', 'ViewAny:Laporan', 'Create:Laporan',
                'View:LaporanMingguan', 'ViewAny:LaporanMingguan', 'Create:LaporanMingguan', 'Update:LaporanMingguan',
                'View:Dashboard', 'View:DashboardStats',
            ];
            $mahasiswa->syncPermissions($mahasiswaPermissions);
            $this->command->info("Assigned permissions to mahasiswa");
        } else {
            $this->command->error('No permissions found! Run shield:generate first.');
        }

        // Assign super_admin role to admin@example.com
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
            ]
        );
        
        if ($adminUser) {
            $adminUser->syncRoles(['super_admin']);
            $this->command->info('Role super_admin assigned to admin@example.com');
        } else {
            $this->command->error('User with email admin@example.com not found!');
        }
    }
}