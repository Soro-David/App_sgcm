@extends('mairie.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h4 class="card-title mb-0 font-weight-bold">Mes Encaissements</h4>
                        <p class="text-muted small mb-0">Liste de vos encaissements effectués à la caisse (Ordre décroissant)
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="mesEncaissementsTable" class="table table-hover" style="width:100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Date & Heure</th>
                                        <th>Contribuable</th>
                                        <th>Type de Taxe</th>
                                        <th>Montant Perçu</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#mesEncaissementsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('mairie.caisse.get_mes_list') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'commercant_info',
                        name: 'num_commerce'
                    },
                    {
                        data: 'taxe_nom',
                        name: 'taxe_nom',
                        orderable: false
                    },
                    {
                        data: 'montant_percu',
                        name: 'montant_percu',
                        className: 'font-weight-bold text-dark'
                    }
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
                },
                order: [
                    [1, 'desc']
                ], // Trier par date décroissante par défaut (colonne 1)
                pageLength: 25,
                drawCallback: function() {
                    $('.dataTables_paginate > .pagination').addClass('pagination-sm');
                }
            });
        });
    </script>
@endpush
