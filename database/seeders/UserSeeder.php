<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
            ]
        );
        $admin->syncRoles(['super_admin']);
        $this->command->info('Admin user created/updated: admin@example.com');

        // Mahasiswa 1: Audi
        $mahasiswa1 = User::firstOrCreate(
            ['email' => 'audi@gmail.com'],
            [
                'name' => 'Audi',
                'password' => Hash::make('mahasiswa123'),
                'angkatan' => 2019,
                'kategori' => 'skripsi',
                'npm' => '19340019'
            ]
        );
        $mahasiswa1->syncRoles(['mahasiswa']);
        $this->command->info('Mahasiswa created/updated: audi@gmail.com');

        // Mahasiswa 2: Dudu
        $mahasiswa2 = User::firstOrCreate(
            ['email' => 'dudu@gmail.com'],
            [
                'name' => 'Dudu',
                'password' => Hash::make('mahasiswa123'),
                'angkatan' => 2020,
                'kategori' => 'magang',
                'npm' => '20340020'
            ]
        );
        $mahasiswa2->syncRoles(['mahasiswa']);
        $this->command->info('Mahasiswa created/updated: dudu@gmail.com');

        // Dosen: Ryan
        $dosen = User::firstOrCreate(
            ['email' => 'ryan@example.com'],
            [
                'name' => 'Ryan',
                'password' => Hash::make('domen123'),
                'nidn' => '1234567890',
            ]
        );
        $dosen->syncRoles(['dosen']);
        $this->command->info('Dosen created/updated: ryan@example.com');

        // Ka Prodi
        $kaprodi = User::firstOrCreate(
            ['email' => 'kaprodi@example.com'],
            [
                'name' => 'Kepala Prodi',
                'password' => Hash::make('kaprodi123'),
            ]
        );
        $kaprodi->syncRoles(['ka_prodi']);
        $this->command->info('Ka Prodi created/updated: kaprodi@example.com');

        $this->command->info('All users seeded successfully!');
    }
}
