<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    /**
     * Konfigurasi untuk tabel User.
     * Mengatur kolom apa saja yang ditampilkan, filter, dan aksi-aksi (action) tabel.
     */
    public static function configure(Table $table): Table
    {
        return $table
            // Menonaktifkan link default saat baris tabel diklik
            ->recordUrl(null)
            ->columns([
                
                // Kolom Nama Lengkap
                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    // Kolom bisa dicari berdasarkan nama, npm, dan nidn
                    ->searchable(['name', 'npm', 'nidn'])
                    ->sortable(['name'])
                    ->weight('bold')
                    // Menambahkan sub-teks di bawah nama berdasarkan role (NPM atau NIDN)
                    ->description(function (User $record) {
                        // Mengambil daftar nama role dari user
                        $roles = $record->roles->pluck('name')->toArray();

                        // Jika mahasiswa dan memiliki NPM, tampilkan NPM
                        if (in_array('mahasiswa', $roles) && $record->npm) {
                            return $record->npm;
                        // Jika dosen dan memiliki NIDN, tampilkan NIDN
                        } elseif (in_array('dosen', $roles) && $record->nidn) {
                            return $record->nidn;
                        }

                        return null; // Tidak ada sub-teks jika bukan mahasiswa/dosen atau data kosong
                    }),

                // Kolom Email
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    // Memungkinkan user mengklik alamat email untuk menyalinnya (copy)
                    ->copyable()
                    ->copyMessage('Email disalin')
                    ->copyMessageDuration(1500),

                // Kolom Role (Menggunakan custom view agar tampilan badge tidak melar)
                TextColumn::make('roles.name')
                    ->label('Role')
                    // Memanggil view khusus untuk merender badge role
                    ->view('filament.tables.columns.roles-badge')
                    ->searchable(),

                // Kolom Status (Aktif/Tidak Aktif)
                TextColumn::make('status')
                    ->badge()
                    // Menentukan warna badge berdasarkan status
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'suspended' => 'danger',
                        default => 'secondary',
                    })
                    // Menentukan icon badge berdasarkan status
                    ->icon(fn (string $state): string => match ($state) {
                        'active' => 'heroicon-m-check-circle',
                        'inactive' => 'heroicon-m-x-circle',
                        'suspended' => 'heroicon-m-no-symbol',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->sortable(),

                // Kolom Angkatan (Disembunyikan secara default)
                TextColumn::make('angkatan')
                    ->icon('heroicon-m-hashtag')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Kolom Dosen Pembimbing (Disembunyikan secara default)
                TextColumn::make('dosenPembimbing.name')
                    ->label('Dosen Pembimbing')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Kolom Waktu Registrasi (Disembunyikan secara default)
                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->icon('heroicon-m-clock')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // Tombol di bagian atas tabel (Header)
            ->headerActions([
                CreateAction::make()
                    ->label('Buat User Baru')
                    ->icon('heroicon-o-plus')
            ])
            // Fitur Filter Data
            ->filters([
                // Filter berdasarkan Role
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),

                // Filter berdasarkan Status
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                    ]),

                // Filter berdasarkan Angkatan
                SelectFilter::make('angkatan')
                    // Membuat opsi secara dinamis berdasarkan tahun angkatan unik yang ada di database
                    ->options(function () {
                        return User::distinct()
                            ->whereNotNull('angkatan')
                            ->pluck('angkatan', 'angkatan')
                            ->toArray();
                    }),
            ])
            // Aksi per baris tabel
            ->recordActions([
                EditAction::make(),
            ])
            // Aksi massal (Bulk Actions) untuk beberapa data yang dipilih sekaligus
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
