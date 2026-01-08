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
                'ViewAny:Bimbingan',
                'View:Bimbingan',
                'Update:Bimbingan',
                'ViewAny:Laporan',
                'View:Laporan',
                'Update:Laporan',
                'ViewAny:LaporanMingguan',
                'View:LaporanMingguan',
                'Update:LaporanMingguan',
                'View:DosenDashboard',
            ];
            $dosen->syncPermissions($dosenPermissions);
            $this->command->info("Assigned permissions to dosen");

            // Permissions untuk mahasiswa
            $mahasiswaPermissions = [
                'ViewAny:Bimbingan',
                'View:Bimbingan',
                'Create:Bimbingan',
                'Update:Bimbingan',
                'Delete:Bimbingan',
                'ViewAny:Laporan',
                'View:Laporan',
                'Create:Laporan',
                'Update:Laporan',
                'Delete:Laporan',
                'ViewAny:LaporanMingguan',
                'View:LaporanMingguan',
                'Create:LaporanMingguan',
                'Update:LaporanMingguan',
                'View:MahasiswaDashboard',
            ];
            $mahasiswa->syncPermissions($mahasiswaPermissions);
            $this->command->info("Assigned permissions to mahasiswa");

            // Permissions untuk ka_prodi - hanya dashboard
            $kaprodiPermissions = [
                'View:KaprodiDashboard',
            ];
            $kaprodi->syncPermissions($kaprodiPermissions);
            $this->command->info("Assigned permissions to ka_prodi");
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
