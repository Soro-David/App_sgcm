@extends('mairie.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-cash-register fa-4x text-primary mb-3"></i>
                        <h3 class="font-weight-bold">Faire un encaissement</h3>
                        <p class="text-muted">Recherchez un contribuable par son numéro de commerce, son nom ou son
                            email.</p>
                    </div>

                    <form id="searchForm" class="mb-4">
                        @csrf
                        <div class="input-group input-group-lg">
                            <input type="text" name="query" id="searchInput" class="form-control"
                                placeholder="Numéro commerce, Nom ou Email..." required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search me-1"></i> Rechercher
                            </button>
                        </div>
                    </form>

                    <div class="divider text-muted mb-4">OU</div>

                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-6 text-center qr-section">

                                <div class="qr-section">
                                    <button id="startScan" class="btn btn-outline-dark btn-lg w-100 mb-3">
                                        <i class="fas fa-qrcode me-2"></i> Scanner un QR Code
                                    </button>
                                    <div id="qr-reader" style="width: 100%; display: none;" class="rounded border"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('searchForm');
            const startScanBtn = document.getElementById('startScan');
            const qrReaderDiv = document.getElementById('qr-reader');
            let html5QrcodeScanner;

            // Gestion de la recherche textuelle
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const query = document.getElementById('searchInput').value;

                if (!query) return;

                fetch("{{ route('mairie.caisse.search') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            query: query
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data.message
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur est survenue lors de la recherche.'
                        });
                    });
            });

            // Gestion du scan QR
            startScanBtn.addEventListener('click', function() {
                qrReaderDiv.style.display = 'block';
                startScanBtn.style.display = 'none';

                html5QrcodeScanner = new Html5QrcodeScanner(
                    "qr-reader", {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },
                    /* verbose= */
                    false
                );

                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            });

            function onScanSuccess(decodedText) {
                console.log("Texte scanné :", decodedText);

                // On extrait le numéro de commerce si c'est le format "Numéro commerce: XXX"
                const match = decodedText.match(/Numéro commerce:\s*([^\n\r]*)/);
                const query = match ? match[1].trim() : decodedText.trim();

                html5QrcodeScanner.clear().then(() => {
                    qrReaderDiv.style.display = 'none';
                    startScanBtn.style.display = 'block';

                    // On lance la recherche avec le texte scanné
                    document.getElementById('searchInput').value = query;
                    searchForm.dispatchEvent(new Event('submit'));
                });
            }

            function onScanFailure(error) {
                // console.warn(`Code scan error = ${error}`);
            }
        });
    </script>
@endpush
