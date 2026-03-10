<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Actions\Action;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Hidden;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // 🧩 1. Role duluan
            Select::make('roles')
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->searchable()
                ->live()
                ->required()
                ->placeholder('Pilih role user')
                ->afterStateHydrated(function (?array $state, callable $set) {
                    $roleNames = $state
                        ? Role::whereIn('id', $state)->pluck('name')->toArray()
                        : [];
                    $set('selected_role_names', $roleNames);
                })
                ->afterStateUpdated(function (?array $state, callable $set) {
                    $roleNames = $state
                        ? Role::whereIn('id', $state)->pluck('name')->toArray()
                        : [];
                    $set('selected_role_names', $roleNames);
                }),

            Hidden::make('selected_role_names')
                ->dehydrated(false),

            TextInput::make('npm')
                ->label('NPM')
                ->required(fn ($get) => in_array('mahasiswa', (array) ($get('selected_role_names') ?? [])))
                ->visible(fn ($get) => in_array('mahasiswa', (array) ($get('selected_role_names') ?? [])))
                ->maxLength(20)
                ->unique(ignoreRecord: true)
                ->placeholder('Masukkan NPM mahasiswa')
                ->validationMessages([
                    'required' => 'NPM wajib diisi untuk mahasiswa.',
                    'unique' => 'NPM ini sudah terdaftar.',
                ]),

            TextInput::make('nidn')
                ->label('NIDN')
                ->maxLength(20)
                ->unique(ignoreRecord: true)
                ->placeholder('Masukkan NIDN dosen')
                ->required(fn ($get) => in_array('dosen', (array) ($get('selected_role_names') ?? [])))
                ->visible(fn ($get) => in_array('dosen', (array) ($get('selected_role_names') ?? [])))
                ->validationMessages([
                    'required' => 'NIDN wajib diisi untuk dosen.',
                    'unique' => 'NIDN ini sudah terdaftar.',
                ]),

           

            // 🧍 Nama
            TextInput::make('name')
                ->label('Nama Lengkap')
                ->required()
                ->maxLength(100)
                ->placeholder('Nama lengkap'),

            // 📧 Email
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(100)
                ->unique(ignoreRecord: true)
                ->placeholder('Email address'),

            // 🔒 Password
            TextInput::make('password')
                ->password()
                ->required(fn($operation) => $operation === 'create')
                ->dehydrateStateUsing(fn($state) => Hash::make($state))
                ->dehydrated(fn($state) => filled($state))
                ->minLength(8)
                ->maxLength(255)
                ->placeholder('Password minimal 8 karakter')
                ->confirmed()
                ->validationMessages([
                    'required'  => 'Password wajib diisi saat membuat user baru.',
                    'min'       => 'Password minimal 8 karakter.',
                    'confirmed' => 'Konfirmasi password tidak cocok.',
                ]),

            // 🔒 Konfirmasi Password
            TextInput::make('password_confirmation')
                ->password()
                ->label('Konfirmasi Password')
                ->required(fn($operation) => $operation === 'create')
                ->dehydrated(false)
                ->placeholder('Konfirmasi password'),


            // ⚙️ Status
            Select::make('status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'suspended' => 'Suspended',
                ])
                ->required()
                ->default('active'),

            // 🎓 Angkatan
            TextInput::make('angkatan')
                ->maxLength(10)
                ->required()
                ->placeholder('Contoh: 2025')
                ->visible(function ($get) {
                    $roles = Role::whereIn('id', (array) $get('roles'))->pluck('name')->toArray();
                    return in_array('mahasiswa', $roles);
                }),


            // 📚 Kategori Mahasiswa
            Select::make('kategori')
                ->label('Kategori Mahasiswa')
                ->required(fn ($get) => in_array('mahasiswa', $get('selected_role_names') ?? []))
                ->visible(fn ($get) => in_array('mahasiswa', $get('selected_role_names') ?? []))
                ->options([
                    'skripsi' => 'Skripsi',
                    'magang' => 'Magang',
                ])
                ->visible(function ($get) {
                    $roles = Role::whereIn('id', (array) $get('roles'))->pluck('name')->toArray();
                    return in_array('mahasiswa', $roles);
                })
                ->placeholder('Pilih kategori mahasiswa'),
               


            Select::make('dosen_pembimbing_id')
                ->label('Dosen Pembimbing')
                ->required(fn ($get) => in_array('mahasiswa', $get('selected_role_names') ?? []))
                ->visible(fn ($get) => in_array('mahasiswa', $get('selected_role_names') ?? []))
                ->relationship(
                    name: 'dosenPembimbing',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn($query) => $query->whereHas('roles', function ($q) {
                        $q->where('name', 'dosen');
                    })
                )
                ->searchable()
                ->preload()
                ->reactive()
                ->hidden(function ($get) {
                    $roles = \Spatie\Permission\Models\Role::whereIn('id', (array) $get('roles'))
                        ->pluck('name')
                        ->toArray();
                    return !in_array('mahasiswa', $roles);
                })
                ->placeholder('Pilih dosen pembimbing'),

            TextInput::make('telegram_chat_id')
                ->label('Telegram Chat ID')
                ->placeholder('Masukkan ID Telegram (Personal)')
                ->helperText('Gunakan bot @userinfobot untuk mendapatkan ID Telegram kamu.')
                ->numeric()
                ->maxLength(50)
                ->validationMessages([
                    'numeric' => 'Telegram ID harus berupa angka.',
                ]),
    
            ]);

    }
}
