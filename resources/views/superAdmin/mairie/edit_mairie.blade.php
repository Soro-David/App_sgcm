@extends('superAdmin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Modifier la Mairie : <span class="text-primary">{{ $mairie->name }}</span></h1>
        <a href="{{ route('superadmin.mairies.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('superadmin.mairies.update', $mairie->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="edit-name" class="form-label">Nom de la mairie <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit-name" name="name" required value="{{ old('name', $mairie->name) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="edit-email" class="form-label">Adresse e-mail <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="edit-email" name="email" required value="{{ old('email', $mairie->email) }}">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="edit-region" class="form-label">Région <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit-region" name="region" data-url="{{ route('superadmin.mairies.get_communes', ['region' => 'PLACEHOLDER']) }}" required>
                            <option value="" disabled>-- Sélectionnez une région --</option>
                            @foreach($regions as $region)
                            <option value="{{ $region->region }}" {{ old('region', $mairie->region) == $region->region ? 'selected' : '' }}>
                                {{ $region->region }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="edit-commune" class="form-label">Commune <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit-commune" name="commune_id" required>
                            @forelse($communesDeLaRegion as $commune)
                            <option value="{{ $commune->id }}" {{ old('commune_id', $mairie->commune_id) == $commune->id ? 'selected' : '' }}>
                                {{ $commune->nom }}
                            </option>
                            @empty
                            <option value="" selected disabled>-- Sélectionnez d'abord une région --</option>
                            @endforelse
                        </select>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label for="edit-adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="edit-adresse" name="adresse" value="{{ old('adresse', $mairie->adresse) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="edit-telephone1" class="form-label">Téléphone 1</label>
                        <input type="tel" class="form-control" id="edit-telephone1" name="telephone1" value="{{ old('telephone1', $mairie->telephone1) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="edit-telephone2" class="form-label">Téléphone 2</label>
                        <input type="tel" class="form-control" id="edit-telephone2" name="telephone2" value="{{ old('telephone2', $mairie->telephone2) }}">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('superadmin.mairies.index') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/mairies.js') }}"></script>
@endpush