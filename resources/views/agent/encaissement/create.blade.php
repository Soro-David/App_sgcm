@extends('agent.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container mt-4">
            <form id="addCommerceForm" method="POST" action="{{ route('agent.commerce.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header justify-content-center">
                    <h3 class="modal-title">Ajoute d'un contribuable</h3>
                </div>

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
                
                <div class="modal-body">
                    {{-- Section des champs --}}
                    <div class="row g-3 mt-2">
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="flex-grow-1 me-2">
                                <label for="type_contribuable" class="form-label">Type Contribuable</label>
                                <select name="type_contribuable_id" id="type_contribuable" class="form-select select2-com w-100" required>
                                    <option value="" disabled selected>-- Sélectionnez un type --</option>
                                    {{-- @foreach ($type_contribuables as $type_contribuable)
                                        <option value="{{ $type_contribuable->id }}">{{ $type_contribuable->libelle }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>
                    </div><br>

                    <div class="modal-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    @include('agent.contribuable.partials.add_contribuable')
@endsection



@push('js')
    <script src="{{ asset('assets/js/agent_commerce_create.js') }}"></script>
@endpush