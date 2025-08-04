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
            
            <form method="POST" action="{{ route('superadmin.mairies.update', $mairie->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nom de la mairie</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               value="{{ old('name', $mairie->name) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required 
                               value="{{ old('email', $mairie->email) }}">
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="region" class="form-label">Région</label>
                        <select class="form-select" id="region" name="region" 
                                data-url="{{ route('superadmin.mairies.get_communes', ['region' => 'PLACEHOLDER']) }}" required>
                            <option value="" disabled>-- Sélectionnez une région --</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->region }}" 
                                       {{ old('region', $region->region) == $region->region ? 'selected' : '' }}>
                                    {{ $region->region }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="commune" class="form-label">Commune</label>
                        <select class="form-select" id="commune" name="commune_id" required>
                            @forelse($communesDeLaRegion as $commune)
                                <option value="{{ $commune->id }}" 
                                        {{ old('commune_id', $commune->commune_id) == $commune->id ? 'selected' : '' }}>
                                    {{ $commune->name }}
                                </option>
                            @empty
                                <option value="" selected disabled>-- Sélectionnez d'abord une région --</option>
                            @endforelse
                        </select>
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