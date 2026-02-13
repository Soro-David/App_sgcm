@extends('commercant.layouts.app')

@section('title', 'Mon Compte - Recharge & Paiements')

@push('css')
    <style>
        .balance-card {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .balance-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
            border-radius: 50%;
        }

        .balance-label {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
        }

        .balance-amount {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 10px 0;
        }

        .btn-group-custom {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .btn-custom {
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-recharger {
            background-color: #28a745;
            color: white;
            border: none;
        }

        .btn-recharger:hover {
            background-color: #218838;
            transform: translateY(-2px);
            color: white;
        }

        .btn-paiement {
            background-color: #007bff;
            color: white;
            border: none;
        }

        .btn-paiement:hover {
            background-color: #0069d9;
            transform: translateY(-2px);
            color: white;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="balance-card">
                    <div class="balance-label">Mon Solde</div>
                    <div class="balance-amount">{{ number_format($solde->montant, 0, ',', ' ') }} FCFA</div>
                    <div class="balance-label">Dernière mise à jour : {{ $solde->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="btn-group-custom">
            <button type="button" class="btn btn-custom btn-recharger" data-bs-toggle="modal"
                data-bs-target="#rechargeModal">
                <i class="fas fa-plus-circle"></i> Recharger mon compte
            </button>
            <a href="{{ route('commercant.payement.create') }}" class="btn btn-custom btn-paiement">
                <i class="fas fa-hand-holding-usd"></i> Effectuer un paiement
            </a>
        </div>

        @include('commercant.recharge.partials.modal_recharge')

        <div class="table-container">
            <h4 class="section-title">
                <i class="fas fa-history text-primary"></i> 10 Dernières Recharges
            </h4>
            <div class="table-responsive">
                <table id="list_recharge" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Référence</th>
                            <th>Mode</th>
                            <th>Montant</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recharges as $recharge)
                            <tr>
                                <td>{{ $recharge->created_at->format('d/m/Y H:i') }}</td>
                                <td><code>{{ $recharge->reference }}</code></td>
                                <td>{{ $recharge->mode_paiement }}</td>
                                <td>{{ number_format($recharge->montant, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    <span class="badge {{ $recharge->statut == 'réussi' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($recharge->statut) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/list_recharge.js') }}"></script>
@endpush
