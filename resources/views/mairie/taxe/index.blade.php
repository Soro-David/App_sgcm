@extends('mairie.layouts.app')

@section('content')
    <div class="container-fluid">
       
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row mb-3">
            <div class="col d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="h3 mb-2">Liste des tache</h1>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tachesTable" 
                            class="table table-bordered table-hover" 
                            style="width:100%"
                            data-ajax-url="{{ route('mairie.taches.get_list_tache') }}"
                            data-lang-url="{{ asset('js/fr-FR.json') }}">
    
                        <thead class="table-light">
                            <tr>
                                <th>Date de Création</th>
                                <th>Nom</th>
                                <th>Action</th> {{-- ← AJOUTÉ --}}
                            </tr>
                        </thead>
    
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/taches.js') }}"></script>
@endpush