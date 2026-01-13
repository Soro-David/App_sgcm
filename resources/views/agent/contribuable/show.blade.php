@extends('agent.layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Détails du commerçant</h3>

    <div class="card">
        <div class="card-body">
            <p><strong>Nom :</strong> {{ $commercant->nom }}</p>
            <p><strong>Email :</strong> {{ $commercant->email ?? '-' }}</p>
            <p><strong>Téléphone :</strong> {{ $commercant->telephone ?? '-' }}</p>
            <p><strong>Adresse :</strong> {{ $commercant->adresse ?? '-' }}</p>
            <p><strong>Secteur :</strong> {{ $commercant->secteur->nom ?? '-' }}</p>
            <p><strong>Numéro de commerce :</strong> {{ $commercant->num_commerce }}</p>

            <p><strong>Taxes associées :</strong></p>
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

    <a href="{{ route('agent.contribuable.index') }}" class="btn btn-secondary mt-3">Retour</a>
</div>
@endsection
