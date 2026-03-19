@extends('mairie.layouts.app')

@section('title', 'Agents en attente')

@push('css')
<style>
    /* ── Page Pending Agents ── */
    .pending-header {
        /* background: linear-gradient(135deg, #ff8c00 0%, #ff6200 100%); */
        border-radius: 16px;
        padding: 28px 32px;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        /* box-shadow: 0 8px 32px rgba(255, 140, 0, .25); */
    }
    /* .pending-header h1 {
        color: #fff;
        font-size: 1.55rem;
        font-weight: 700;
        margin: 0;
    } */
    .pending-header p {
        /* color: rgba(255,255,255,.85); */
        margin: 4px 0 0;
        font-size: .9rem;
    }
    .pending-header .badge-total {
        background: rgba(255,255,255,.2);
        color: #ff8c00;
        font-size: 1.8rem;
        font-weight: 800;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid rgba(255,255,255,.5);
        flex-shrink: 0;
    }

    /* ── Table card ── */
    .pending-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,.08);
        overflow: hidden;
    }
    .pending-card .card-top {
        padding: 20px 24px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 16px;
    }
    .pending-card .card-top h5 {
        font-size: 1rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
    }
    .search-input {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 8px 14px 8px 38px;
        font-size: .875rem;
        background: #f7f9fc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23999' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0'/%3E%3C/svg%3E") no-repeat 12px center;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .search-input:focus {
        border-color: #ff8c00;
        box-shadow: 0 0 0 3px rgba(255,140,0,.12);
        background-color: #fff;
    }

    /* ── Table ── */
    .pending-table { width: 100%; border-collapse: collapse; }
    .pending-table thead tr {
        background: #f8fafc;
    }
    .pending-table thead th {
        padding: 14px 20px;
        font-size: .78rem;
        font-weight: 700;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: .05em;
        border-bottom: 2px solid #edf2f7;
        white-space: nowrap;
    }
    .pending-table tbody tr {
        border-bottom: 1px solid #f0f4f8;
        transition: background .15s;
    }
    .pending-table tbody tr:hover { background: #fffbf5; }
    .pending-table td {
        padding: 14px 20px;
        vertical-align: middle;
        font-size: .875rem;
        color: #2d3748;
    }

    /* ── Avatar ── */
    .agent-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ff8c00, #ffb347);
        color: #fff;
        font-weight: 700;
        font-size: .85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(255,140,0,.3);
    }
    .agent-info .name { font-weight: 600; color: #1a202c; }
    .agent-info .email { font-size: .8rem; color: #718096; }

    /* ── Role badges ── */
    .role-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 600;
    }
    .role-badge.agent    { background: #e8f4fd; color: #2b6cb0; }
    .role-badge.mairie   { background: #fef3c7; color: #92400e; }
    .role-badge.finance  { background: #e6fffa; color: #276749; }
    .role-badge.financier{ background: #faf5ff; color: #6b46c1; }
    .role-badge.caissier { background: #fff5f5; color: #c53030; }

    /* ── Status chip ── */
    .status-pending {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        background: #fff7ed;
        color: #c05621;
        font-size: .78rem;
        font-weight: 600;
        border: 1px solid #fed7aa;
    }
    .status-pending::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #ed8936;
        animation: pulse-dot 1.5s infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: .5; transform: scale(1.3); }
    }

    /* ── Resend button ── */
    .btn-resend {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 16px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, #ff8c00, #ff6200);
        color: #fff;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform .15s, box-shadow .15s, opacity .15s;
        /* box-shadow: 0 2px 10px rgba(255,140,0,.3); */
    }
    .btn-resend:hover {
        transform: translateY(-2px);
        /* box-shadow: 0 6px 18px rgba(255,140,0,.4); */
        color: #fff;
    }
    .btn-resend:active { transform: translateY(0); }
    .btn-resend.loading { opacity: .6; pointer-events: none; }

    /* ── Edit button ── */
    .btn-edit-pending {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        background: #fff;
        color: #4a5568;
        font-size: .8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
    }
    .btn-edit-pending:hover {
        background: #f8fafc;
        border-color: #cbd5e0;
        color: #2d3748;
    }

    /* ── Action buttons container ── */
    .action-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 64px 24px;
    }
    .empty-state .icon {
        font-size: 4rem;
        color: #e2e8f0;
        margin-bottom: 16px;
    }
    .empty-state h4 { color: #4a5568; font-weight: 700; }
    .empty-state p  { color: #a0aec0; font-size: .9rem; }

    /* ── Alerts ── */
    .alert-premium {
        border-radius: 12px;
        border: none;
        padding: 14px 20px;
        font-size: .875rem;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,.06);
    }
    .alert-success-premium { background: #f0fff4; color: #276749; }
    .alert-error-premium   { background: #fff5f5; color: #c53030; }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- Alertes session --}}
    @if(session('success'))
        <div class="alert-premium alert-success-premium">
            <i class="fas fa-check-circle fa-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert-premium alert-error-premium">
            <i class="fas fa-exclamation-circle fa-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- En-tête --}}
    <div class="pending-header">
        <div>
            <h1><i class="fas fa-user-clock me-2"></i>Agents en attente</h1>
            <p>Ces agents n'ont pas encore finalisé leur inscription. Renvoyez-leur un email d'invitation.</p>
        </div>
        <div class="badge-total">{{ $pendingAgents->count() }}</div>
    </div>

    @if($pendingAgents->isEmpty())
        {{-- État vide --}}
        <div class="pending-card">
            <div class="empty-state">
                <div class="icon"><i class="fas fa-user-check"></i></div>
                <h4>Aucun agent en attente</h4>
                <p>Tous les agents ont finalisé leur inscription. Bravo !</p>
                <a href="{{ route('mairie.agents.index') }}" class="btn btn-outline-secondary mt-2">
                    <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                </a>
            </div>
        </div>
    @else
        {{-- Table --}}
        <div class="pending-card">
            <div class="card-top">
                <h5><i class="fas fa-hourglass-half me-2 text-warning"></i>Liste des agents en attente</h5>
                <input type="text" id="searchPending" class="search-input" placeholder="Rechercher un agent…">
            </div>

            <div class="table-responsive">
                <table class="pending-table" id="pendingTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Agent</th>
                            <th>Rôle</th>
                            <th>Ajouté par</th>
                            <th>Date d'ajout</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingAgents as $index => $agent)
                        <tr class="agent-row">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="agent-avatar">{{ strtoupper(substr($agent->name, 0, 2)) }}</div>
                                    <div class="agent-info">
                                        <div class="name">{{ $agent->name }}</div>
                                        <div class="email">{{ $agent->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $roleClass = match(true) {
                                        $agent->type_model === 'agent'     => 'agent',
                                        $agent->type_model === 'financier' => 'financier',
                                        str_contains(strtolower($agent->role), 'caissier') => 'caissier',
                                        $agent->type_model === 'finance'   => 'finance',
                                        default                            => 'mairie',
                                    };
                                @endphp
                                <span class="role-badge {{ $roleClass }}">{{ ucfirst($agent->role) }}</span>
                            </td>
                            <td>{{ $agent->added_by ?? '—' }}</td>
                            <td>
                                {{ $agent->created_at ? \Carbon\Carbon::parse($agent->created_at)->format('d/m/Y H:i') : '—' }}
                            </td>
                            <td>
                                <span class="status-pending">En attente</span>
                            </td>
                            <td>
                                <div class="action-group">
                                    <button type="button" class="btn-edit-pending edit-btn-modal"
                                        data-id="{{ $agent->id }}"
                                        data-name="{{ $agent->name }}"
                                        data-email="{{ $agent->email }}"
                                        data-phone="{{ $agent->phone }}"
                                        data-type="{{ $agent->type_model }}">
                                        <i class="fas fa-edit"></i>
                                        Modifier
                                    </button>

                                    <form
                                        action="{{ route('mairie.agents.resend_invitation', ['type' => $agent->type_model, 'id' => $agent->id]) }}"
                                        method="POST"
                                        class="resend-form">
                                        @csrf
                                        <button type="submit" class="btn-resend" title="Renvoyer l'email d'invitation">
                                            <i class="fas fa-paper-plane"></i>
                                            Renvoyer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

{{-- Modal de modification --}}
<div class="modal fade" id="editPendingModal" tabindex="-1" aria-labelledby="editPendingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,.2);">
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #edf2f7; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <h5 class="modal-title" id="editPendingModalLabel" style="font-weight: 700; color: #2d3748;">
                    <i class="fas fa-user-edit me-2 text-warning"></i>Modifier les informations
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPendingForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; font-size: .85rem; color: #4a5568;">Nom et Prénom</label>
                        <input type="text" name="name" id="modal_name" class="form-control" style="border-radius: 10px; padding: 10px 14px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; font-size: .85rem; color: #4a5568;">Email</label>
                        <input type="email" name="email" id="modal_email" class="form-control" style="border-radius: 10px; padding: 10px 14px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; font-size: .85rem; color: #4a5568;">Téléphone</label>
                        <input type="text" name="phone" id="modal_phone" class="form-control" style="border-radius: 10px; padding: 10px 14px;">
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #edf2f7; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Annuler</button>
                    <button type="submit" class="btn btn-warning" style="border-radius: 10px; font-weight: 600; background: #ff8c00; border: none; color: #fff; padding: 8px 24px;">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Gestion du Modal ──
    const editModal = new bootstrap.Modal(document.getElementById('editPendingModal'));
    const editForm = document.getElementById('editPendingForm');

    document.querySelectorAll('.edit-btn-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const email = this.getAttribute('data-email');
            const phone = this.getAttribute('data-phone');
            const type = this.getAttribute('data-type');

            // Remplir les champs
            document.getElementById('modal_name').value = name;
            document.getElementById('modal_email').value = email;
            document.getElementById('modal_phone').value = phone || '';

            // Mettre à jour l'action du formulaire (route Laravel avec placeholders remplacés par JS)
            // Utilisation d'un template d'URL généré par Blade, puis remplacement en JS
            let url = "{{ route('mairie.agents.update_pending', ['type' => ':type', 'id' => ':id']) }}";
            url = url.replace(':type', type).replace(':id', id);
            editForm.setAttribute('action', url);

            editModal.show();
        });
    });

    // ── Recherche en temps réel ──
    const searchInput = document.getElementById('searchPending');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#pendingTable tbody .agent-row').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });
    }

    // ── Feedback bouton Renvoyer ──
    document.querySelectorAll('.resend-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            const btn = this.querySelector('.btn-resend');
            btn.classList.add('loading');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi…';
        });
    });

    // ── Auto-dismiss alerts ──
    document.querySelectorAll('.alert-premium').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity .5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>
@endpush
