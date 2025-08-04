@extends('mairie.layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow-sm w-100">
        <div class="card-body">
            {{-- Affichage des messages de session --}}
            @if(session('error'))
                <div class="alert alert-danger mb-4 fs-5">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success mb-4 fs-5">
                    {{ session('success') }}
                </div>
            @endif

            <h5 class="card-title mb-5 text-center fs-3">Ajouter un agent</h5>

            <form method="POST" action="{{ route('mairie.agents.store') }}" class="fs-5">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label">Nom de l'agent</label>
                    <input type="text" class="form-control fs-5" id="name" name="name" required value="{{ old('name') }}">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="type_agent" class="form-label">Type d'agent</label>
                        <select name="type_agent" id="type_agent" class="form-select fs-5" required>
                            <option value="" disabled selected>-- Sélectionnez un type d'agent --</option>
                            <option value="recouvrement">Recouvrement</option>
                            <option value="agent de mairie">Agent de mairie</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <input type="email" class="form-control fs-5" id="email" name="email" required value="{{ old('email') }}">
                    </div>
                </div>

                <input type="hidden" name="mairie_id" value="{{ $mairieId }}">

                <div class="d-flex justify-content-between mt-5">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary px-4 py-2 fs-5">Annuler</a>
                    <button type="submit" class="btn btn-primary px-4 py-2 fs-5">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
