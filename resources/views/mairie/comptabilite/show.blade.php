@extends('mairie.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container mt-4">
            <div class="container mt-4">
                <h1 class="h3 mb-2">Détails du commerçant</h1>
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Nom :</strong> {{ $commercant->nom }}
                            </div>
                            <div class="col-md-6">
                                <strong>Email :</strong> {{ $commercant->email ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Téléphone :</strong> {{ $commercant->telephone ?? '-' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Adresse :</strong> {{ $commercant->adresse ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Secteur :</strong> {{ $commercant->secteur->nom ?? '-' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Numéro de commerce :</strong> {{ $commercant->num_commerce ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Taxes associées :</strong>
                            @php
                                $taxeIds = is_array($commercant->taxe_id) ? $commercant->taxe_id : json_decode($commercant->taxe_id, true);
                            @endphp

                            @if (!empty($taxeIds))
                                <ul>
                                    @foreach ($taxeIds as $taxeId)
                                        @php
                                            $taxe = \App\Models\Taxe::find($taxeId);
                                        @endphp
                                        <li>{{ $taxe ? $taxe->nom : "Taxe ID #$taxeId non trouvée" }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Aucune taxe associée.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <a href="{{ route('mairie.commerce.index') }}" class="btn btn-secondary mt-3">Retour</a>
            </div>
        </div>
    </div>
</div>
@endsection
