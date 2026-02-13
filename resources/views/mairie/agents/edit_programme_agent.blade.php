@extends('mairie.layouts.app')

@section('content')
    <div class="container-fluid">
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Modifier le programme de l'agent</h6>
                <a href="{{ route('mairie.agents.programme_agent') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('mairie.agents.update_programme', $agent->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        {{-- Agent --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="agent_id">Agent</label>
                                <select class="form-control select2 @error('agent_id') is-invalid @enderror" name="agent_id"
                                    id="agent_id" required>
                                    <option value="">-- Choisir un agent --</option>
                                    @foreach ($agents as $a)
                                        <option value="{{ $a->id }}" {{ $agent->id == $a->id ? 'selected' : '' }}>
                                            {{ $a->name }}</option>
                                    @endforeach
                                </select>
                                @error('agent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="secteur_id">Secteur</label>
                                <select class="form-control select2 @error('secteur_id') is-invalid @enderror"
                                    name="secteur_id" id="secteur_id" required>
                                    <option value="">-- Choisir un secteur --</option>
                                    @foreach ($secteurs as $secteur)
                                        @php $currentSecteurId = is_array($agent->secteur_id) ? ($agent->secteur_id[0] ?? null) : $agent->secteur_id; @endphp<option value="{{ $secteur->id }}"
                                            {{ $currentSecteurId == $secteur->id ? 'selected' : '' }}>{{ $secteur->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('secteur_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- Taxes --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="taxe_ids">Taxes</label>
                                <select class="form-control select2 @error('taxe_ids') is-invalid @enderror"
                                    name="taxe_ids[]" id="taxe_ids" multiple required>
                                    @foreach ($taxes as $taxe)
                                        @php $currentTaxeIds = is_array($agent->taxe_id) ? $agent->taxe_id : []; @endphp<option value="{{ $taxe->id }}"
                                            {{ in_array($taxe->id, $currentTaxeIds) ? 'selected' : '' }}>
                                            {{ $taxe->nom }}</option>
                                    @endforeach
                                </select>
                                @error('taxe_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: "Veuillez choisir",
                allowClear: true
            });
        });
    </script>
@endpush
