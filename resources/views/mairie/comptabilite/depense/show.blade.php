@extends('mairie.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4 class="m-0 font-weight-bold text-primary">Détails de la Dépense</h4>
            <a href="{{ route('mairie.depense.index') }}" class="btn btn-secondary btn-sm">Retour à la liste</a>
        </div>

        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-8">
                    <dl class="row">
                        <dt class="col-sm-4">Date de dépense :</dt>
                        <dd class="col-sm-8">{{ $depense->date_depense->format('d/m/Y') }}</dd>

                        <dt class="col-sm-4">Motif :</dt>
                        <dd class="col-sm-8">{{ $depense->motif }}</dd>

                        <dt class="col-sm-4">Montant :</dt>
                        <dd class="col-sm-8">{{ number_format($depense->montant, 0, ',', ' ') }} FCFA</dd>

                        <dt class="col-sm-4">Mode de paiement :</dt>
                        <dd class="col-sm-8">{{ ucfirst(str_replace('_', ' ', $depense->mode_paiement)) }}</dd>

                        <dt class="col-sm-4">Référence :</dt>
                        <dd class="col-sm-8">{{ $depense->reference ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Description :</dt>
                        <dd class="col-sm-8"><p>{{ $depense->description }}</p></dd>

                         <dt class="col-sm-4">Pièce jointe :</dt>
                        <dd class="col-sm-8">
                            @if($depense->piece_jointe)
                                <a href="{{ Storage::url($depense->piece_jointe) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="typcn typcn-document"></i> Voir la pièce jointe
                                </a>
                            @else
                                Aucune
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="mt-4 border-top pt-3">
                 <a href="{{ route('mairie.depense.edit', $depense->id) }}" class="btn btn-success">
                    <i class="typcn typcn-edit"></i> Modifier cette dépense
                </a>
            </div>
        </div>
    </div>
</div>
@endsection