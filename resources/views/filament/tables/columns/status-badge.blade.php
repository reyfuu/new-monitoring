{{-- 
    Custom view untuk menampilkan badge status agar tidak melar (stretch).
--}}
@php
    $state = $getState();
    $normalizedState = blank($state) ? 'review' : strtolower($state);
    
    $color = match ($normalizedState) {
        'review' => 'warning',
        'disetujui' => 'success',
        'revisi' => 'danger',
        default => 'secondary',
    };
    
    $icon = match ($normalizedState) {
        'review' => 'heroicon-m-clock',
        'disetujui' => 'heroicon-m-check-circle',
        'revisi' => 'heroicon-m-exclamation-triangle',
        default => 'heroicon-m-question-mark-circle',
    };
    
    $label = match ($normalizedState) {
        'review' => 'Review',
        'disetujui' => 'Disetujui',
        'revisi' => 'Revisi',
        default => ucfirst($normalizedState),
    };
@endphp

<div class="flex w-max">
    <x-filament::badge :color="$color" :icon="$icon">
        {{ $label }}
    </x-filament::badge>
</div>
