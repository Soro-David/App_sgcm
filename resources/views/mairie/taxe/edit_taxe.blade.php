@extends('mairie.layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('mairie.taxes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste des taxes
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Assigner une Taxe à une mairie</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('mairie.taxes.update', $taxe->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="nom">Nom de la taxe</label>
                        <input type="text" name="nom" class="form-control" value="{{ old('nom', $taxe->nom) }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="mairie_ref">Sélectionner une mairie</label>
                        <select name="mairie_ref" class="form-select" id="mairieSelect"
                                data-url="{{ route('mairie.taxes.infos.mairie', ['id' => 'ID_PLACEHOLDER']) }}">
                            <option value="">-- Choisissez une mairie --</option>
                            @foreach($mairies as $mairie)
                                <option value="{{ $mairie->mairie_ref }}" {{ old('mairie_ref', $taxe->mairie_ref) == $mairie->mairie_ref ? 'selected' : '' }}>
                                    {{ $mairie->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row" id="mairieInfos">
                    <div class="mb-3 col-md-6">
                        <label for="region">Région</label>
                        <input type="text" class="form-control" id="region" value="{{ $taxe->mairie->region->nom ?? '' }}" disabled>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="commune">Commune</label>
                        <input type="text" class="form-control" id="commune" value="{{ $taxe->mairie->commune->commune ?? '' }}" disabled>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4>Liste des mairies ayant des taxes assignées</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="mairieTable"
                data-url="{{ route('mairie.taxes.mairie.list') }}">
                <thead>
                    <tr>
                        <th>Nom de la Mairie</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Le contenu sera chargé par DataTables via AJAX --}}
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('js')
    {{-- Assurez-vous d'inclure jQuery et DataTables dans votre layout principal --}}
    <script>
        $(document).ready(function () {
            // On détruit l'instance précédente pour éviter les conflits
            if ($.fn.DataTable.isDataTable('#mairieTable')) {
                $('#mairieTable').DataTable().destroy();
            }

            // On initialise la DataTable
            $('#mairieTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('mairie.taxes.mairie.list') }}", // Assurez-vous que ce nom de route est correct
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                // Ajout pour un meilleur affichage des erreurs AJAX dans la console
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("Erreur AJAX DataTables : ", textStatus, errorThrown);
                    console.error("Réponse du serveur : ", jqXHR.responseText);
                    alert("Une erreur est survenue lors du chargement des données. Veuillez vérifier la console du navigateur.");
                }
            });
        });
    </script>
    <script src="{{ asset('assets/js/mairies.js') }}"></script>
@endpush