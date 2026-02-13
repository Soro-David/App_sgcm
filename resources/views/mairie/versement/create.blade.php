@extends('mairie.layouts.app')

@section('content')
    <div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 60vh;">
        <div class="card shadow-sm w-100" style="width: 100%; max-width: 600px;"
            data-code-generator-url="{{ route('mairie.secteurs.genererCode') }}">

            {{-- Header --}}
            <div class="card-header py-2">
                <h4 class="m-0 font-weight-bold text-primary text-center">Faire un Versement</h4>
            </div>

            {{-- Messages de session --}}
            <div class="px-4 pt-3">
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif
            </div>

            {{-- Formulaire --}}
            <div class="card-body p-4" id="versement-container"
                data-montant-url-template="{{ route('mairie.versements.montant_nonverse', ['agent' => 'AGENT_ID']) }}">
                <form action="{{ route('mairie.versements.store') }}" method="POST">
                    @csrf
                    <div class="row">

                        {{-- Agent --}}
                        <div class="col-md-6 mb-3">
                            <label for="agent_id" class="form-label">Sélectionnez un Agent</label>
                            <select name="agent_id" id="agent_id"
                                class="form-select select2 @error('agent_id') is-invalid @enderror">
                                <option value="" disabled selected>-- Sélectionnez un agent --</option>
                                @foreach ($agents ?? [] as $agent)
                                    <option value="{{ $agent->id }}"
                                        {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('agent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- Dette --}}
                        <div class="col-md-6 mb-3">
                            <label for="dette" class="form-label">Dette (Reste précédent)</label>
                            <input type="text" class="form-control @error('dette') is-invalid @enderror" id="dette"
                                name="dette" value="{{ old('dette', 0) }}" readonly>
                            @error('dette')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Montant perçu --}}
                        <div class="col-md-6 mb-3">
                            <label for="montant_percu" class="form-label">Montant Perçu (Nouveaux encaissements)</label>
                            <input type="text" class="form-control @error('montant_percu') is-invalid @enderror"
                                id="montant_percu" name="montant_percu" value="{{ old('montant_percu', 0) }}" readonly>
                            @error('montant_percu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Montant versé --}}
                        <div class="col-md-6 mb-3">
                            <label for="montant_verse" class="form-label">Montant Versé</label>
                            <input type="number" class="form-control @error('montant_verse') is-invalid @enderror"
                                id="montant_verse" name="montant_verse" value="{{ old('montant_verse') }}" required>
                            @error('montant_verse')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Reste à verser --}}
                        <div class="col-md-6 mb-3">
                            <label for="montant_restant" class="form-label text-danger fw-bold">Nouveau Reste à
                                Verser</label>
                            <input type="text"
                                class="form-control @error('montant_restant') is-invalid @enderror border-danger"
                                id="montant_restant" name="montant_restant" value="{{ old('montant_restant', 0) }}"
                                readonly>
                            @error('montant_restant')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Appréciation --}}
                        <div class="col-md-6 mb-3">
                            <label for="appreciation" class="form-label">Appréciation Automatique</label>
                            <input type="text" name="appreciation" id="appreciation"
                                class="form-control fw-bold @error('appreciation') is-invalid @enderror"
                                value="{{ old('appreciation') }}" readonly style="background-color: #f8f9fa;">
                            @error('appreciation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Section Détails des encaissements --}}
                        <div class="col-12 mb-3">
                            <hr>
                            <h5 class="text-primary"><i class="fas fa-list me-2"></i> Détails des encaissements non versés
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover border" id="encaissements-list">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Taxe</th>
                                            <th>Commerçant</th>
                                            <th class="text-end">Montant</th>
                                        </tr>
                                    </thead>
                                    <tbody id="encaissements-body">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Sélectionnez un agent pour
                                                voir
                                                les détails</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="bg-light fw-bold">
                                        <tr>
                                            <td colspan="3" class="text-end">Total Percu:</td>
                                            <td class="text-end" id="total-percu-footer">0 FCFA</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm"><i
                                class="fas fa-save me-2"></i> Enregistrer le Versement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/versements-create.js') }}"></script>
@endpush
