@props([
    'fieldId' => 'filiation',
    'fieldName' => 'filiation',
    'required' => true,
    'currentValue' => null,
    'labelClass' => 'form-label',
])

@php
    $filiations = cache()->remember('filiations_list', 60, function () {
        return \App\Models\Agent::whereNotNull('filiation')
            ->where('filiation', '!=', '')
            ->distinct()
            ->pluck('filiation')
            ->merge(
                \App\Models\Mairie::whereNotNull('filiation')
                    ->where('filiation', '!=', '')
                    ->distinct()
                    ->pluck('filiation'),
            )
            ->merge(
                \App\Models\Finance::whereNotNull('filiation')
                    ->where('filiation', '!=', '')
                    ->distinct()
                    ->pluck('filiation'),
            )
            ->merge(
                \App\Models\Financier::whereNotNull('filiation')
                    ->where('filiation', '!=', '')
                    ->distinct()
                    ->pluck('filiation'),
            )
            ->unique()
            ->sort()
            ->values();
    });
@endphp

<div class="s2-filiation">
    <label for="{{ $fieldId }}" class="{{ $labelClass }}">
        Lien de parenté
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <select id="{{ $fieldId }}" name="{{ $fieldName }}" class="form-select s2-filiation-input"
        data-placeholder="Sélectionner ou saisir une filiation" {{ $required ? 'required' : '' }}>
        <option value=""></option>
        @foreach ($filiations as $f)
            <option value="{{ $f }}" {{ (string) $currentValue === (string) $f ? 'selected' : '' }}>
                {{ $f }}</option>
        @endforeach

        @if ($currentValue && !$filiations->contains($currentValue))
            <option value="{{ $currentValue }}" selected>{{ $currentValue }}</option>
        @endif
    </select>
</div>

@push('js')
    <script src="{{ asset('assets/js/filiation-select2.js') }}"></script>
@endpush
