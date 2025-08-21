<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Journal des Recettes</title>
    <style>
        @page {
            margin: 100px 25px 50px 25px; /* Augmentation de la marge haute pour l'en-tête */
        }

        body { 
            font-family: 'DejaVu Sans', sans-serif; /* Important pour les caractères spéciaux */
            font-size: 10px; 
            line-height: 1.2;
        }

        .header {
            position: fixed;
            top: -90px;
            left: 0;
            right: 0;
            height: 80px;
            text-align: center;
        }

        .footer {
            position: fixed; 
            bottom: -30px;
            left: 0;
            right: 0;
            height: 20px; 
            text-align: center;
        }
        
        /* Script pour la numérotation des pages */
        .footer .page-number:after {
            content: "Page " counter(page);
        }

        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
        }
        .table th, .table td { 
            border: 1px solid #999; 
            padding: 5px; 
            text-align: left;
        }
        .table th { 
            background-color: #f0f0f0; 
            font-weight: bold;
        }

        /* Règle CRUCIALE pour répéter les en-têtes de tableau sur chaque page */
        .table thead {
            display: table-header-group;
        }

        /* Tente d'éviter les coupures à l'intérieur d'une ligne */
        .table tbody tr {
            page-break-inside: avoid;
        }
        
        h1, h2 { 
            text-align: center; 
            page-break-after: avoid; /* Évite un saut de page juste après un titre */
        }
    </style>
</head>
<body>
    <!-- En-tête fixe -->
    <header class="header">
        <h1>Journal des Recettes à Percevoir</h1>
        <p>Rapport généré le : {{ now()->isoFormat('D MMMM YYYY') }}</p>
    </header>

    <!-- Pied de page avec numérotation -->
    <footer class="footer">
        <script type="text/php">
            if (isset($pdf)) {
                $x = 500;
                $y = 820;
                $text = "Page {PAGE_NUM} sur {PAGE_COUNT}";
                $font = $fontMetrics->get_font("DejaVu Sans", "normal");
                $size = 9;
                $color = array(0,0,0);
                $word_space = 0.0;
                $char_space = 0.0;
                $angle = 0.0;
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }
        </script>
    </footer>

    <!-- Contenu principal -->
    <main>
        <h2>Synthèse par Agent</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Agent</th>
                    <th>Total Encaissé (sur la sélection)</th>
                    <th>Montant Restant à Verser</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agentsData as $agent)
                <tr>
                    <td>{{ $agent->nom }}</td>
                    <td>{{ number_format($agent->total_encaisse, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($agent->doit_verser, 0, ',', ' ') }} FCFA</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Aucun encaissement par un agent pour cette sélection.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <h2>Détail des Paiements (Total: {{ number_format($totalTaxesCollectees, 0, ',', ' ') }} FCFA)</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Commerçant (N°)</th>
                    <th>Taxe</th>
                    <th>Période</th>
                    <th>Montant</th>
                    <th>Statut Encaissement</th>
                    <th>Agent Encaisseur</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paiements as $paiement)
                <tr>
                    <td>{{ optional($paiement->commercant)->nom ?? 'N/A' }} ({{ optional($paiement->commercant)->num_commerce }})</td>
                    <td>{{ $paiement->taxe->nom }}</td>
                    <td>{{ \Carbon\Carbon::parse($paiement->periode)->isoFormat('MMMM YYYY') }}</td>
                    <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                    <td>{{ $paiement->statut_encaissement }}</td>
                    <td>{{ $paiement->agent_encaisseur }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Aucun paiement trouvé pour cette sélection.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>