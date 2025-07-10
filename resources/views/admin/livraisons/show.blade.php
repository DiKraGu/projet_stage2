@extends('admin.layouts.app')

@section('title', 'Détails de la livraison')

@section('content')
<h1>Détails livraison #{{ $livraison->id }}</h1>

<a href="{{ route('admin.livraisons.index') }}" class="btn btn-secondary mb-3">Retour à la liste</a>

<div>
    <p><strong>Établissement :</strong> {{ $livraison->etablissement->nom }}</p>
    <p><strong>Semaine du menu :</strong> {{ \Carbon\Carbon::parse($livraison->menu->semaine)->format('d/m/Y') }}</p>
    <p><strong>Date de livraison :</strong> {{ $livraison->date_livraison ?? '-' }}</p>
    <p><strong>Statut :</strong>
        @if($livraison->statut === 'en_attente')
            <span class="badge bg-warning text-dark">En attente</span>
        @elseif($livraison->statut === 'livrée')
            <span class="badge bg-success">Livrée</span>
        @elseif($livraison->statut === 'annulée')
            <span class="badge bg-danger">Annulée</span>
        @endif
    </p>
</div>

<h3>Produits livrés</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Produit</th>
            <th>Quantité livrée</th>
            <th>Lot</th>
            <th>Date d'expiration du lot</th>
        </tr>
    </thead>
    <tbody>
        @foreach($livraison->details as $detail)
            <tr>
                <td>{{ $detail->produit->nom }}</td>
                <td>{{ $detail->quantite_livree }}</td>
                <td>{{ $detail->lotStockAdmin->id ?? '-' }}</td>
                <td>{{ $detail->lotStockAdmin->date_expiration ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
