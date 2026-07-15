{{-- 
    Custom view untuk menampilkan badge role agar tidak melar (stretch).
    Dibungkus dengan flex-wrap agar badge berjajar rapi ke samping jika ada lebih dari satu.
--}}
<div class="flex flex-wrap gap-1">
    {{-- Mengambil relasi roles dari baris data yang sedang dirender dan melakukan perulangan --}}
    @foreach ($getRecord()->roles as $role)
        @php
            // Menentukan warna badge berdasarkan nama role
            $color = match ($role->name) {
                'super_admin' => 'primary',
                'dosen' => 'success',
                'mahasiswa' => 'warning',
                default => 'gray',
            };
            
            // Menentukan icon badge berdasarkan nama role
            $icon = match ($role->name) {
                'super_admin' => 'heroicon-m-shield-check',
                'dosen' => 'heroicon-m-academic-cap',
                'mahasiswa' => 'heroicon-m-user',
                default => 'heroicon-m-question-mark-circle',
            };
        @endphp
        
        {{-- Menggunakan komponen blade bawaan Filament untuk merender badge --}}
        <x-filament::badge :color="$color" :icon="$icon">
            {{ $role->name }}
        </x-filament::badge>
    @endforeach
</div>
