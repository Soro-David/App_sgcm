@extends('mairie.layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow w-100" style="max-width: 800px;" data-code-generator-url="{{ route('mairie.secteurs.genererCode') }}">

        {{-- Header --}}
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ajouter un nouveau secteur</h6>
        </div>

        {{-- Affichage des messages de session --}}
        <div class="px-4 pt-3">
            @if(session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif
        </div>

        {{-- Formulaire --}}
        <div class="card-body">
            <form action="{{ route('mairie.secteurs.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="code_secteur">Code Secteur</label>
                        <input type="text" class="form-control @error('code_secteur') is-invalid @enderror"
                               id="code_secteur" name="code_secteur" readonly required>
                        @error('code_secteur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nom">Nom du secteur</label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror"
                               id="nom" name="nom" value="{{ old('nom') }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Ajouter le secteur</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/secteurs.js') }}"></script>
@endpush
