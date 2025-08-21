@extends('mairie.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h4 class="m-0 font-weight-bold text-primary">Modifier la Dépense</h4>
        </div>

        <div class="card-body p-4">
            {{-- Affiche les erreurs de validation --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('mairie.depense.update', $depense->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- Indique à Laravel que c'est une requête de type PUT --}}

                {{-- Les champs du formulaire, pré-remplis avec les données existantes --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="motif" class="form-label">Motif*</label>
                        <input type="text" class="form-control" id="motif" name="motif" value="{{ old('motif', $depense->motif) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="montant" class="form-label">Montant (FCFA)*</label>
                        <input type="number" step="1" class="form-control" id="montant" name="montant" value="{{ old('montant', $depense->montant) }}" required>
                    </div>
                </div>
                {{-- ... Ajoutez les autres champs ici en suivant le même modèle ... --}}
                <div class="row">
                     <div class="col-md-6 mb-3">
                        <label for="date_depense" class="form-label">Date de dépense*</label>
                        <input type="date" class="form-control" id="date_depense" name="date_depense" value="{{ old('date_depense', $depense->date_depense->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="mode_paiement" class="form-label">Mode de paiement*</label>
                        <select class="form-control" id="mode_paiement" name="mode_paiement" required>
                            <option value="cash" @selected(old('mode_paiement', $depense->mode_paiement) == 'cash')>Cash</option>
                            <option value="mobile_money" @selected(old('mode_paiement', $depense->mode_paiement) == 'mobile_money')>Mobile Money</option>
                            <option value="cheque" @selected(old('mode_paiement', $depense->mode_paiement) == 'cheque')>Chèque</option>
                            <option value="virement" @selected(old('mode_paiement', $depense->mode_paiement) == 'virement')>Virement bancaire</option>
                        </select>
                    </div>
                </div>
                 <div class="mb-3">
                    <label for="description" class="form-label">Description*</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $depense->description) }}</textarea>
                </div>
                 <div class="mb-3">
                    <label for="piece_jointe" class="form-label">Changer la pièce jointe (facultatif)</label>
                    <input type="file" class="form-control" id="piece_jointe" name="piece_jointe">
                    @if($depense->piece_jointe)
                       <small class="form-text text-muted">Pièce actuelle : <a href="{{ Storage::url($depense->piece_jointe) }}" target="_blank">Voir le fichier</a>. Laisser vide pour conserver.</small>
                    @endif
                </div>


                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('mairie.depense.index') }}" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="typcn typcn-input-checked"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection