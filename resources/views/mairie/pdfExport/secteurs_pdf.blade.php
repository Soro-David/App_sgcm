<!DOCTYPE html>
<html lang="fr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Liste des Secteurs</title>
    <style>
        @page {
            margin: 100px 25px 50px 25px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }

        .header {
            position: fixed;
            top: -90px;
            left: 0;
            right: 0;
            height: 90px;
            text-align: center;
            background-color: #f8f9fa;
            border-bottom: 2px solid #4e73df;
            padding-top: 10px;
        }

        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 20px;
            text-align: center;
            font-size: 9px;
            color: #777;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #e3e6f0;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #4e73df;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }

        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table thead {
            display: table-header-group;
        }

        h1 {
            text-align: center;
            color: #4e73df;
            margin: 0;
            font-size: 18px;
        }

        .meta-info {
            font-size: 10px;
            color: #5a5c69;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <header class="header">
        <h1>Liste des Secteurs</h1>
        <p class="meta-info">Rapport généré le : {{ now()->isoFormat('D MMMM YYYY [à] HH:mm') }}</p>
    </header>

    <main>
        <table class="table">
            <thead>
                <tr>
                    <th>Nom du secteur</th>
                    <th>Date de création</th>
                </tr>
            </thead>
            <tbody>
                @forelse($secteurs as $secteur)
                    <tr>
                        <td>{{ $secteur->nom }}</td>
                        <td>{{ $secteur->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" style="text-align: center;">Aucun secteur trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>

</html>
