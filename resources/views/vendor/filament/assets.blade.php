@if (isset($data))
    <script>
        window.filamentData = @js($data)
    </script>
@endif

@foreach ($assets as $asset)
    @if (! $asset->isLoadedOnRequest())
        {{ $asset->getHtml() }}
    @endif
@endforeach

<!-- Load comment styles (cacheable static file) -->
<link rel="stylesheet" href="{{ asset('css/filament-comments.css') }}">

<style>
    :root {
        @foreach ($cssVariables ?? [] as $cssVariableName => $cssVariableValue) --{{ $cssVariableName }}:{{ $cssVariableValue }}; @endforeach
    }

    @foreach ($customColors ?? [] as $customColorName => $customColorShades) .fi-color-{{ $customColorName }} { @foreach ($customColorShades as $customColorShade) --color-{{ $customColorShade }}:var(--{{ $customColorName }}-{{ $customColorShade }}); @endforeach } @endforeach

    /* Reduce horizontal padding between table columns to make columns closer */
    .fi-ta-cell { padding-inline: calc(var(--spacing) * 1); }
    @media (min-width: 40rem) {
        .fi-ta-cell:first-of-type { padding-inline-start: calc(var(--spacing) * 1.5); }
        .fi-ta-cell:last-of-type { padding-inline-end: calc(var(--spacing) * 1.5); }
        .fi-ta-cell:has(.fi-ta-actions) { padding-inline: calc(var(--spacing) * 1.5); }
    }
</style>
