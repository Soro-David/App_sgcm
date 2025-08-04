@extends('mairie.layouts.app')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 60vh;">
    <div class="card shadow-sm w-100" style="width: 100%; max-width: 600px;" data-code-generator-url="{{ route('mairie.secteurs.genererCode') }}">

        {{-- Header --}}
        <div class="card-header py-2">
            <h4 class="m-0 font-weight-bold text-primary text-center">Faire un Versement</h4>
        </div>

        {{-- Messages de session --}}
        <div class="px-4 pt-3">
            @if(session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif
        </div>

        {{-- Formulaire --}}
        <div id="versement-container" class="card-body px-4 py-2" data-montant-url="{{ route('mairie.versements.montant_nonverse', ['agent_id' => 'AGENT_ID']) }}">
            <form action="{{ route('mairie.versements.store') }}" method="POST">
                @csrf
                <div class="row">

                    {{-- Agent --}}
                   <div class="col-md-6 mb-3">
                        <label for="agent_id">Sélectionnez un Agent</label>
                        <select name="agent_id" id="agent_id" class="form-select select2 @error('agent_id') is-invalid @enderror">
                            <option value="" disabled selected>-- Sélectionnez un agent --</option>
                            @foreach ($agents ?? [] as $agent)
                                <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>   
                        @error('agent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- Dette --}}
                    <div class="col-md-6 mb-3">
                        <label for="dette">Dette</label>
                        <input type="text" class="form-control @error('dette') is-invalid @enderror"
                               id="dette" name="dette" value="{{ old('dette') }}" readonly>
                        @error('dette')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Montant perçu --}}
                    <div class="col-md-6 mb-3">
                        <label for="montant_percu">Montant Perçu</label>
                        <input type="text" class="form-control @error('montant_percu') is-invalid @enderror"
                               id="montant_percu" name="montant_percu" value="{{ old('montant_percu') }}" readonly>
                        @error('montant_percu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Montant versé --}}
                    <div class="col-md-6 mb-3">
                        <label for="montant_verse">Montant Versé</label>
                        <input type="text" class="form-control @error('montant_verse') is-invalid @enderror"
                               id="montant_verse" name="montant_verse" value="{{ old('montant_verse') }}" required>
                        @error('montant_verse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Reste --}}
                    <div class="col-md-6 mb-3">
                        <label for="montant_restant">Montant Restant</label>
                        <input type="text" class="form-control @error('montant_restant') is-invalid @enderror"
                               id="montant_restant" name="montant_restant" value="{{ old('montant_restant') }}" readonly>
                        @error('montant_restant')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Appréciation --}}
                    <div class="col-md-6 mb-3">
                        <label for="appreciation">Appréciation</label>
                        <select name="appreciation" id="appreciation" class="form-select @error('appreciation') is-invalid @enderror">
                            <option value="" disabled selected>-- Choisir une appréciation --</option>
                            <option value="excellent" {{ old('appreciation') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                            <option value="bon" {{ old('appreciation') == 'bon' ? 'selected' : '' }}>Bon</option>
                            <option value="moyen" {{ old('appreciation') == 'moyen' ? 'selected' : '' }}>Moyen</option>
                            <option value="faible" {{ old('appreciation') == 'faible' ? 'selected' : '' }}>Faible</option>
                        </select>
                        @error('appreciation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
               
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/versements.js') }}"></script>
@endpush

