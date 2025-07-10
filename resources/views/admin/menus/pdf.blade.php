
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Menu Hebdomadaire</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        .header img {
            height: 80px;
            margin-right: 20px;
            margin-bottom: 30px;

        }
        .etablissement-info {
            flex-grow: 1;
            text-align: center;
        }
        .etablissement-info h2,
        .etablissement-info h4 {
            margin: 5px 0;
        },


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/pro-rest-logo.jpg') }}" alt="Logo">
        <div class="etablissement-info">
            <h2>{{ $etablissement->nom }} - {{ $etablissement->province->nom }}, {{ $etablissement->province->ville->nom }}</h2>
            <h4>Menu du : Du {{ \Carbon\Carbon::parse($menu->semaine)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($menu->semaine_fin)->format('d/m/Y') }}</h4>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Jour</th>
                <th>Petit Déjeuner</th>
                <th>Déjeuner</th>
                <th>Dîner</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'] as $jour)
                <tr>
                    <td style="text-transform: capitalize;">{{ $jour }}</td>
                    <td>
                        @if(isset($produits[$jour]['petit_dejeuner']))
                            @foreach($produits[$jour]['petit_dejeuner'] as $produit)
                                - {{ $produit->nom }}<br>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if(isset($produits[$jour]['dejeuner']))
                            @foreach($produits[$jour]['dejeuner'] as $produit)
                                - {{ $produit->nom }}<br>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if(isset($produits[$jour]['diner']))
                            @foreach($produits[$jour]['diner'] as $produit)
                                - {{ $produit->nom }}<br>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
