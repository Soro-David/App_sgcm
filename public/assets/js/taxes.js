/**
 * Gestion du DataTable des taxes
 */
$(document).ready(function () {
    // Configuration AJAX pour inclure le token CSRF
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Récupération des URLs depuis les attributs data
    const ajaxUrl = $("#taxesTable").data("ajax-url");
    const langUrl = $("#taxesTable").data("lang-url");

    // Déterminer les colonnes dynamiquement
    const columnCount = $("#taxesTable thead th").length;
    let columns = [];

    if (columnCount === 5) {
        // Layout Mairie: Nom, Date, Fréquence, Montant, Action
        columns = [
            { data: "nom", name: "nom" },
            { data: "created_at", name: "created_at" },
            { data: "frequence", name: "frequence" },
            {
                data: "montant",
                name: "montant",
                render: function (data) {
                    return data ? data + " Fcfa" : "0 Fcfa";
                },
            },
            { data: "action", name: "action", orderable: false, searchable: false }
        ];
    } else {
        // Layout SuperAdmin: Date, Nom, Montant, Action
        columns = [
            { data: "created_at", name: "created_at" },
            { data: "nom", name: "nom" },
            {
                data: "montant",
                name: "montant",
                render: function (data) {
                    return data ? data + " Fcfa" : "0 Fcfa";
                },
            },
            { data: "action", name: "action", orderable: false, searchable: false }
        ];
    }

    // Initialisation du DataTable
    const table = $("#taxesTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: ajaxUrl,
            type: "GET",
            error: function (xhr, error, code) {
                console.error("Erreur AJAX:", error, code);
                console.error("Réponse:", xhr.responseText);
            },
        },
        columns: columns,
        language: {
            url: langUrl,
        },
        responsive: true,
        pageLength: 10,
        order: [[1, "desc"]],
    });

    /**
     * Gestion du clic sur le bouton "Modifier"
     */
    $("#taxesTable").on("click", ".btn-edit", function () {
        const taxeId = $(this).data("id");

        // Récupérer les données de la taxe via AJAX
        $.ajax({
            url: `/mairie/taxe/${taxeId}/edit`,
            type: "GET",
            success: function (response) {
                if (response.success) {
                    const taxe = response.taxe;

                    // Remplir le formulaire de modification
                    $("#edit_taxe_id").val(taxe.id);
                    $("#edit_nom").val(taxe.nom);
                    $("#edit_montant").val(taxe.montant);
                    $("#edit_frequence").val(taxe.frequence);
                    $("#edit_description").val(taxe.description || "");

                    // Afficher le modal
                    $("#editTaxeModal").modal("show");
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erreur",
                        text:
                            response.message ||
                            "Impossible de récupérer les données de la taxe.",
                    });
                }
            },
            error: function (xhr) {
                console.error("Erreur:", xhr);
                let errorMessage =
                    "Une erreur est survenue lors de la récupération des données.";
                if (xhr.status === 404) {
                    errorMessage = "Taxe introuvable.";
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: "error",
                    title: "Erreur",
                    text: errorMessage,
                });
            },
        });
    });

    /**
     * Gestion de la soumission du formulaire de modification
     */
    $("#editTaxeForm").on("submit", function (e) {
        e.preventDefault();

        const taxeId = $("#edit_taxe_id").val();
        const formData = {
            nom: $("#edit_nom").val(),
            montant: $("#edit_montant").val(),
            frequence: $("#edit_frequence").val(),
            description: $("#edit_description").val(),
            _token: $('input[name="_token"]').val(),
        };

        $.ajax({
            url: `/mairie/taxe/${taxeId}`,
            type: "PUT",
            data: formData,
            success: function (response) {
                if (response.success) {
                    // Fermer le modal
                    $("#editTaxeModal").modal("hide");

                    // Afficher un message de succès
                    Swal.fire({
                        icon: "success",
                        title: "Succès",
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false,
                    });

                    // Recharger le tableau
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erreur",
                        text:
                            response.message ||
                            "Une erreur est survenue lors de la modification.",
                    });
                }
            },
            error: function (xhr) {
                console.error("Erreur:", xhr);
                let errorMessage =
                    "Une erreur est survenue lors de la modification.";

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join("<br>");
                }

                Swal.fire({
                    icon: "error",
                    title: "Erreur de validation",
                    html: errorMessage,
                });
            },
        });
    });

    /**
     * Gestion du clic sur le bouton "Supprimer"
     */
    $("#taxesTable").on("click", ".btn-delete", function () {
        const taxeId = $(this).data("id");

        Swal.fire({
            title: "Êtes-vous sûr?",
            text: "Cette action est irréversible!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Oui, supprimer!",
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/mairie/taxe/${taxeId}`,
                    type: "DELETE",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Supprimé!",
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false,
                            });

                            // Recharger le tableau
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Erreur",
                                text:
                                    response.message ||
                                    "Impossible de supprimer la taxe.",
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error("Erreur:", xhr);
                        let errorMessage =
                            "Une erreur est survenue lors de la suppression.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: "error",
                            title: "Erreur",
                            text: errorMessage,
                        });
                    },
                });
            }
        });
    });

    /**
     * Réinitialiser le formulaire lors de la fermeture du modal
     */
    $("#editTaxeModal").on("hidden.bs.modal", function () {
        $("#editTaxeForm")[0].reset();
    });
});
