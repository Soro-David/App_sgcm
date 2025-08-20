<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Journal des Recettes</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', sans-serif; /* Police compatible avec les caractères spéciaux */
            font-size: 10px; 
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
        }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 6px; 
            text-align: left;
        }
        .table th { 
            background-color: #f2f2f2; 
        }
        h1, h2 { 
            text-align: center; 
        }
        .header {
            margin-bottom: 30px;
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Journal des Recettes à Percevoir</h1>
        <p>Rapport généré le : {{ now()->isoFormat('D MMMM YYYY') }}</p>
    </div>

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
            @foreach($paiements as $paiement)
            <tr>
                <td>{{ optional($paiement->commercant)->nom ?? 'N/A' }} ({{ optional($paiement->commercant)->num_commerce }})</td>
                <td>{{ $paiement->taxe->nom }}</td>
                <td>{{ $paiement->periode->format('d/m/Y') }}</td>
                <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                <td>{{ $paiement->statut_encaissement }}</td>
                <td>{{ $paiement->agent_encaisseur }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Page <span class="pagenum"></span>
    </div>
</body>
</html>