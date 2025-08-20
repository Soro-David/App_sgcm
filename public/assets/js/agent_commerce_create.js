$(document).ready(function () {
    // Initialisation des librairies Select2
    $('.select2').select2({ width: '100%', placeholder: '-- Sélectionnez une ou plusieurs taxes --', allowClear: true });
    $('.select2-com').select2({ width: '100%', placeholder: '-- Sélectionnez type de contribuables --', allowClear: true });

    /**
     * Configure le bouton d'upload (icône de dossier) pour ouvrir le sélecteur de fichiers.
     * @param {string} uploadBtnId - L'ID du bouton d'upload.
     * @param {string} fileInputId - L'ID de l'input de type 'file' associé.
     */
    function setupFileUpload(uploadBtnId, fileInputId) {
        const uploadButton = document.getElementById(uploadBtnId);
        const fileInput = document.getElementById(fileInputId);

        if (uploadButton) {
            uploadButton.addEventListener('click', () => {
                fileInput.click();
            });
        }
    }
    
    /**
     * Gère l'affichage de l'aperçu de l'image une fois qu'un fichier est sélectionné.
     * @param {string} fileInputId - L'ID de l'input de type 'file'.
     * @param {string} previewImgId - L'ID de l'élément 'img' pour l'aperçu.
     */
    function setupImagePreview(fileInputId, previewImgId) {
        const fileInput = document.getElementById(fileInputId);
        const previewImage = document.getElementById(previewImgId);

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

    // Configuration pour la photo de profil
    setupFileUpload('upload_profil', 'photo_profil');
    setupImagePreview('photo_profil', 'preview_profil');
    document.getElementById('camera_profil').addEventListener('click', () => initCameraCapture('preview_profil', 'photo_profil'));

    // Configuration pour la photo pièce Recto
    setupFileUpload('upload_recto', 'photo_recto');
    setupImagePreview('photo_recto', 'preview_recto');
    document.getElementById('camera_recto').addEventListener('click', () => initCameraCapture('preview_recto', 'photo_recto'));

    // Configuration pour la photo pièce Verso
    setupFileUpload('upload_verso', 'photo_verso');
    setupImagePreview('photo_verso', 'preview_verso');
    document.getElementById('camera_verso').addEventListener('click', () => initCameraCapture('preview_verso', 'photo_verso'));


    // Logique pour afficher/masquer le champ "autre type de pièce"
    const typePieceSelect = document.getElementById('type_piece');
    const autreContainer = document.getElementById('autre_type_piece_container');
    if (typePieceSelect) {
        typePieceSelect.addEventListener('change', function () {
            autreContainer.classList.toggle('d-none', this.value !== 'autre');
        });
    }

    // Logique de soumission du formulaire via AJAX
    $('#addCommerceForm').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const submitButton = $(this).find('button[type="submit"]');
        const originalButtonText = submitButton.html();

        submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enregistrement...');

        $.ajax({
            url: $(form).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

            success: function (response) {
                if (response.success && response.redirect_url) {
                    alert(response.message);
                    window.location.href = response.redirect_url;
                } else {
                    alert('Une erreur est survenue: ' + (response.message || 'Veuillez vérifier les champs.'));
                }
            },
            error: function (xhr) {
                let errorMsg = "Une erreur serveur est survenue. Veuillez réessayer.";
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    errorMsg = "Erreurs de validation:\n";
                    $.each(errors, function (key, value) {
                        errorMsg += `- ${value[0]}\n`;
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
            },
            complete: function () {
                submitButton.prop('disabled', false).html(originalButtonText);
            }
        });
    });
});


function initCameraCapture(targetPreviewId, targetInputId) {
    console.log("Tentative d'initialisation de la caméra pour l'input:", targetInputId);

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert("Votre navigateur ne supporte pas l'API de la caméra. Essayez avec un navigateur moderne comme Chrome ou Firefox.");
        return;
    }

    const modal = document.createElement('div');
    modal.className = 'camera-modal';
    Object.assign(modal.style, {
        position: 'fixed', top: 0, left: 0, width: '100%', height: '100%',
        backgroundColor: 'rgba(0,0,0,0.8)', display: 'flex', alignItems: 'center',
        justifyContent: 'center', zIndex: 9999
    });

    const cameraContainer = document.createElement('div');
    Object.assign(cameraContainer.style, {
        position: 'relative', backgroundColor: '#fff', padding: '20px',
        borderRadius: '10px', textAlign: 'center', maxWidth: '90%'
    });

    const video = document.createElement('video');
    video.setAttribute('autoplay', '');
    video.setAttribute('playsinline', ''); 
    Object.assign(video.style, { width: '100%', maxWidth: '500px', borderRadius: '10px', backgroundColor: '#000' });

    const captureButton = document.createElement('button');
    captureButton.textContent = 'Capturer';
    captureButton.className = 'btn btn-primary mt-3';

    const closeButton = document.createElement('button');
    closeButton.textContent = 'Fermer';
    closeButton.className = 'btn btn-secondary mt-3 ms-2';

    const messageElement = document.createElement('p');
    messageElement.textContent = "Demande d'accès à la caméra...";
    messageElement.style.color = '#666';

    cameraContainer.appendChild(messageElement);
    cameraContainer.appendChild(video);
    cameraContainer.appendChild(captureButton);
    cameraContainer.appendChild(closeButton);
    modal.appendChild(cameraContainer);
    document.body.appendChild(modal);

    let streamInstance = null; 

    const stopCamera = () => {
        if (streamInstance) {
            streamInstance.getTracks().forEach(track => track.stop());
            console.log("Caméra arrêtée.");
        }
        modal.remove();
    };

    const constraints = {
        video: {
            facingMode: 'environment'
        }
    };

    console.log("Demande d'accès à la caméra avec les contraintes :", constraints);

    navigator.mediaDevices.getUserMedia(constraints)
    .then(stream => {
        console.log("Accès à la caméra arrière réussi.");
        streamInstance = stream;
        video.srcObject = stream;
        messageElement.style.display = 'none'; // Cache le message de chargement
        video.play().catch(e => console.error("Erreur lors de la lecture de la vidéo : ", e));
    })
    .catch(err => {
        console.warn("Impossible d'accéder à la caméra arrière (" + err.name + "), nouvelle tentative avec n'importe quelle caméra.");
        // Deuxième tentative (caméra avant par défaut)
        return navigator.mediaDevices.getUserMedia({ video: true });
    })
    .then(stream => {
        // Ce bloc s'exécute si la deuxième tentative est nécessaire et réussit
        if (!video.srcObject && stream) {
            console.log("Accès à la caméra par défaut (avant) réussi.");
            streamInstance = stream;
            video.srcObject = stream;
            messageElement.style.display = 'none';
            video.play().catch(e => console.error("Erreur lors de la lecture de la vidéo : ", e));
        }
    })
    .catch(err => {
        // Si les deux tentatives échouent
        console.error("Erreur finale d'accès à la caméra : ", err.name, err.message);
        alert(`Erreur d'accès à la caméra : ${err.name}. Assurez-vous d'être sur une page HTTPS et d'avoir autorisé l'accès.`);
        stopCamera();
    });

    captureButton.onclick = () => {
        if (!video.srcObject) {
            alert("Le flux de la caméra n'est pas actif.");
            return;
        }
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageDataURL = canvas.toDataURL('image/jpeg');

        document.getElementById(targetPreviewId).src = imageDataURL;

        fetch(imageDataURL)
            .then(res => res.blob())
            .then(blob => {
                const fileName = `${targetInputId}_${Date.now()}.jpg`;
                const file = new File([blob], fileName, { type: 'image/jpeg' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById(targetInputId).files = dataTransfer.files;
                console.log("Image capturée et assignée à l'input :", file);
            });

        stopCamera();
    };

    closeButton.onclick = stopCamera;
}