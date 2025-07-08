@extends('admin.layouts.app')

@section('title', 'Alertes de stock')

@section('content')
<h1 class="mb-4">Alertes de Stock</h1>

<form method="GET" action="{{ route('admin.alerts') }}" class="mb-3 d-flex gap-2 align-items-center flex-wrap">
    <select name="type" class="form-select w-25 " onchange="this.form.submit()">
        <option value="">-- Tous les types d'alertes --</option>
        <option value="stock_faible" {{ request('type') == 'stock_faible' ? 'selected' : '' }}>Stock faible</option>
        <option value="bientot_perime" {{ request('type') == 'bientot_perime' ? 'selected' : '' }}>Bientôt périmé</option>
        <option value="perime" {{ request('type') == 'perime' ? 'selected' : '' }}>Périmé</option>
        <option value="epuise" {{ request('type') == 'epuise' ? 'selected' : '' }}>Épuisé</option>
    </select>
</form>

@if($alerts->isEmpty())
    <div class="alert alert-success">Aucune alerte pour le moment 🎉</div>
@else
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Produit</th>
            <th>Quantité reçue</th>
            <th>Quantité disponible</th>
            <th>Prix unitaire</th>
            <th>Date réception</th>
            <th>Date expiration</th>
            <th>Type d'alerte</th>
        </tr>
    </thead>
    {{-- <tbody>
        @foreach($alerts as $alert)
            @php
                $lot = $alert['lot'];
            @endphp
            <tr>
                <td>{{ $lot->produit->nom }}</td>
                <td>{{ $alert['type'] == 'stock_faible' || $alert['type'] == 'epuise' ? $lot->quantite_recue : '' }}</td>
                <td>{{ $lot->quantite_disponible }}</td>
                <td>{{ number_format($lot->prix_unitaire, 2) }} DH</td>
                <td>{{ $alert['type'] == 'bientot_perime' || $alert['type'] == 'perime' ? $lot->date_reception : '' }}</td>
                <td>{{ $lot->date_expiration }}</td>
                <td>
                    @if($alert['type'] == 'stock_faible')
                        Stock faible
                    @elseif($alert['type'] == 'bientot_perime')
                        ⚠️ Bientôt périmé
                    @elseif($alert['type'] == 'perime')
                        ❌ Périmé
                    @elseif($alert['type'] == 'epuise')
                        Épuisé
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody> --}}

    <tbody>
    @foreach($alerts as $alert)
        @php
            $lot = $alert['lot'];
            $rowClass = '';
            switch ($alert['type']) {
                case 'perime':
                    $rowClass = 'table-danger'; // rouge
                    break;
                case 'bientot_perime':
                    $rowClass = 'table-warning'; // orange (bootstrap)
                    break;
                case 'stock_faible':
                    $rowClass = 'table-warning text-dark'; // jaune (bootstrap warning) + texte sombre
                    break;
                case 'epuise':
                    $rowClass = 'table-secondary'; // gris clair, proche rouge mais neutre
                    break;
            }
        @endphp
        <tr class="{{ $rowClass }}">
            <td>{{ $lot->produit->nom }}</td>
            <td>{{ in_array($alert['type'], ['stock_faible', 'epuise']) ? $lot->quantite_recue : '' }}</td>
            <td>{{ $lot->quantite_disponible }}</td>
            <td>{{ number_format($lot->prix_unitaire, 2) }} DH</td>
            <td>{{ in_array($alert['type'], ['bientot_perime', 'perime']) ? $lot->date_reception : '' }}</td>
            <td>{{ $lot->date_expiration }}</td>
            <td>
                @if($alert['type'] == 'stock_faible')
                    ⚠️ Stock faible
                @elseif($alert['type'] == 'bientot_perime')
                    🟠 Bientôt périmé
                @elseif($alert['type'] == 'perime')
                    ❌ Périmé
                @elseif($alert['type'] == 'epuise')
                    ⚫ Épuisé
                @endif
            </td>
        </tr>
    @endforeach
</tbody>

</table>

        {{-- <div class="d-flex justify-content-end mt-4">
            {{ $lots->withQueryString()->links() }}
        </div> --}}

        <div class="d-flex justify-content-end mt-3">
            {{ $alerts->withQueryString()->links() }}
        </div>
@endif
@endsection
