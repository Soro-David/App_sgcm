$(document).ready(function () {

    // Initialiser Select2
    $('.select2').select2({
        width: '100%',
        placeholder: '-- Sélectionnez une ou plusieurs taxes --',
        allowClear: true
    });
    $('.select2-com').select2({
        width: '100%',
        placeholder: '-- Sélectionnez type de contribuables --',
        allowClear: true
    });

    /**
     * Fonction réutilisable pour gérer le clic sur les boutons d'upload et caméra
     * @param {string} uploadBtnId - ID du bouton d'upload
     * @param {string} cameraBtnId - ID du bouton de caméra
     * @param {string} fileInputId - ID du champ input de type file
     * @param {string} previewImgId - ID de l'image de prévisualisation
     */
    function setupImageUpload(uploadBtnId, cameraBtnId, fileInputId, previewImgId) {
        const uploadButton = document.getElementById(uploadBtnId);
        const cameraButton = document.getElementById(cameraBtnId);
        const fileInput = document.getElementById(fileInputId);
        const previewImage = document.getElementById(previewImgId);

        if (uploadButton) {
            uploadButton.addEventListener('click', () => {
                fileInput.removeAttribute('capture');
                fileInput.click();
            });
        }

        if (cameraButton) {
            cameraButton.addEventListener('click', () => {
                fileInput.setAttribute('capture', 'environment'); // Caméra arrière
                fileInput.click();
            });
        }

        if (fileInput) {
            fileInput.addEventListener('change', (e) => {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (evt) => {
                        previewImage.src = evt.target.result;
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }
    }

    // Appliquer la logique aux trois sections d'image
    setupImageUpload('upload_profil', 'camera_profil', 'photo_profil', 'preview_profil');
    setupImageUpload('upload_recto', 'camera_recto', 'photo_recto', 'preview_recto');
    setupImageUpload('upload_verso', 'camera_verso', 'photo_verso', 'preview_verso');

    // Gérer l'affichage du champ "Autre type de pièce"
    const typePieceSelect = document.getElementById('type_piece');
    const autreContainer = document.getElementById('autre_type_piece_container');
    if (typePieceSelect) {
        typePieceSelect.addEventListener('change', function () {
            autreContainer.classList.toggle('d-none', this.value !== 'autre');
        });
    }

    // Soumission du formulaire en AJAX
    $('#addCommerceForm').on('submit', function (e) {
        e.preventDefault(); // Empêche la soumission classique du formulaire

        const form = this;
        const formData = new FormData(form);
        const submitButton = $(this).find('button[type="submit"]');
        const originalButtonText = submitButton.html();

        // Désactiver le bouton et afficher un indicateur de chargement
        submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enregistrement...');

        $.ajax({
            url: $(form).attr('action'),
            method: 'POST',
            data: formData,
            processData: false, // Nécessaire pour l'envoi de fichiers
            contentType: false, // Nécessaire pour l'envoi de fichiers
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    alert(response.message); // Ou une notification plus élégante (SweetAlert2, etc.)
                    form.reset(); // Réinitialiser le formulaire
                    $('#preview_profil').attr('src', '/images/default_profile.png');
                    $('#preview_recto').attr('src', '/images/default_piece_recto.png');
                    $('#preview_verso').attr('src', '/images/default_piece_verso.png');
                    $('.select2').val(null).trigger('change');
                } else {
                     alert('Une erreur est survenue: ' + (response.message || 'Veuillez vérifier les champs.'));
                }
            },
            error: function (xhr) {
                // Gérer les erreurs de validation et autres erreurs serveur
                let errorMsg = "Une erreur serveur est survenue. Veuillez réessayer.";
                if (xhr.status === 422) { // Erreur de validation
                    const errors = xhr.responseJSON.errors;
                    errorMsg = "Erreurs de validation:\n";
                    $.each(errors, function (key, value) {
                        errorMsg += `- ${value[0]}\n`;
                    });
                }
                alert(errorMsg);
            },
            complete: function () {
                // Réactiver le bouton
                submitButton.prop('disabled', false).html(originalButtonText);
            }
        });
    });
});
