document.addEventListener('DOMContentLoaded', function () {
    const paymentForm = document.getElementById('paymentForm');
    if (!paymentForm) return;

    // Configuration et éléments du DOM
    const { paymentFormAction, successRedirectUrl, csrfToken, getTaxeDetailsUrl } = window.config;
    const numCommerceAttendu = document.getElementById('num_commerce_display').textContent.trim();
    const qrReaderDiv = document.getElementById('qr-reader');
    
    const taxeSelect = document.getElementById('taxe_id');
    const paymentDetailsDiv = document.getElementById('paymentDetails');
    const montantParPeriodeInput = document.getElementById('montant_par_periode');
    const nombrePeriodesInput = document.getElementById('nombre_periodes');
    const unpaidInfo = document.getElementById('unpaid_periods_info');
    const totalAmountDisplay = document.getElementById('total_amount_display');
    
    let montantUnitaire = 0;
    let html5QrcodeScanner;
    console.log(numCommerceAttendu);
    // Fonction de succès du scan QR
    const onScanSuccess = (decodedText) => {

        console.log("Texte brut scanné : ", decodedText);

        const match = decodedText.match(/Numéro commerce:\s*([^\n\r]*)/);
        const numeroScanne = match ? match[1].trim() : decodedText.trim();

        console.log("Numéro extrait :", numeroScanne);
        console.log("Numéro attendu :", numCommerceAttendu);

        // 2. Comparaison
        if (numeroScanne === numCommerceAttendu) {
            Swal.fire({
                icon: 'success',
                title: 'Commerçant Vérifié !',
                showConfirmButton: false,
                timer: 1500
            });
            paymentForm.style.display = 'block';
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear().catch(err => console.error("Échec de l'arrêt du scanner", err));
            }
            qrReaderDiv.style.display = 'none';
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Le QR Code ne correspond pas à ce commerçant.\nScanné : ' + numeroScanne + '\nAttendu : ' + numCommerceAttendu
            });
        }
    };

    // Initialisation du scanner QR
    function startScanner() {
        html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: { width: 250, height: 250 } }, false);
        html5QrcodeScanner.render(onScanSuccess, (error) => { /* ignorer les erreurs de non-détection */ });
    }

    // Demande initiale pour lancer le scan
    Swal.fire({
        title: 'Effectuer un Encaissement',
        text: "Veuillez scanner le QR Code du contribuable pour continuer.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Démarrer le scan',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            startScanner();
        } else {
            window.location.href = successRedirectUrl; // Rediriger si l'agent annule
        }
    });

    // Gestion du changement de taxe
    taxeSelect.addEventListener('change', function() {
        const taxeId = this.value;
        montantUnitaire = 0;
        updateTotalAmount();
        
        if (!taxeId) {
            paymentDetailsDiv.style.display = 'none';
            return;
        }

        montantParPeriodeInput.value = 'Chargement...';
        unpaidInfo.textContent = '';
        paymentDetailsDiv.style.display = 'block';

        const url = getTaxeDetailsUrl.replace('__TAXE_ID__', taxeId);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    montantUnitaire = parseFloat(data.montant);
                    montantParPeriodeInput.value = montantUnitaire.toLocaleString('fr-FR');
                    nombrePeriodesInput.value = 1;
                    // On retire la limite .max car le client peut payer d'avance
                    unpaidInfo.innerHTML = `<span class="text-info fw-bold">${data.unpaid_count} période(s) impayée(s)</span>. Vous pouvez encaisser pour cette durée ou plus.`;
                    updateTotalAmount();
                } else {
                    Swal.fire('Erreur', 'Impossible de récupérer les détails de la taxe.', 'error');
                    paymentDetailsDiv.style.display = 'none';
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Erreur', 'Un problème de communication est survenu.', 'error');
                paymentDetailsDiv.style.display = 'none';
            });
    });

    // Mise à jour du montant total
    function updateTotalAmount() {
        const nombrePeriodes = parseInt(nombrePeriodesInput.value, 10) || 0;
        const total = nombrePeriodes * montantUnitaire;
        totalAmountDisplay.textContent = total.toLocaleString('fr-FR');
    }
    nombrePeriodesInput.addEventListener('input', updateTotalAmount);

    // Soumission du formulaire
    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Confirmer le Paiement',
            html: `Vous êtes sur le point d'encaisser <b class="fs-5">${totalAmountDisplay.textContent} FCFA</b>.<br>Voulez-vous continuer ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, valider',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData(paymentForm);
                formData.append('_method', 'PUT'); // Spoofing de la méthode pour la route resource

                fetch(paymentFormAction, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Succès !', data.message, 'success').then(() => {
                            window.location.href = successRedirectUrl;
                        });
                    } else {
                        Swal.fire('Erreur !', data.message || 'Une erreur est survenue.', 'error');
                    }
                })
                .catch(err => {
                    console.error('Fetch Error:', err);
                    Swal.fire('Erreur !', 'Un problème de communication est survenu.', 'error');
                });
            }
        });
    });
});