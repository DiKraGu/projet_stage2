@extends('admin.layouts.app')

@section('title', 'Gestion du stock central')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Stock central</h1>
        <a href="{{ route('admin.stocks.create') }}" class="btn btn-primary">Ajouter un lot</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

<form method="GET" action="{{ route('admin.stocks.index') }}" class="mb-3 d-flex gap-2 align-items-center flex-nowrap">

    {{-- Recherche --}}
    <input type="text" name="search" value="{{ request('search') }}" class="form-control w-20" placeholder="Rechercher un produit...">
    <button type="submit" class="btn btn-outline-primary ms-2">Rechercher</button>

    {{-- Filtrage par état --}}
    <select name="etat" class="form-select w-15" onchange="this.form.submit()">
        <option value="">-- Tous les états --</option>
        <option value="actif" {{ request('etat') == 'actif' ? 'selected' : '' }}>Actif</option>
        <option value="perime" {{ request('etat') == 'perime' ? 'selected' : '' }}>Périmé</option>
        <option value="epuise" {{ request('etat') == 'epuise' ? 'selected' : '' }}>Épuisé</option>
    </select>

    {{-- Filtre par date de réception --}}
    <input type="date" name="date_reception" value="{{ request('date_reception') }}" class="form-control w-20">


    {{-- Filtre par date d'expiration --}}
    <input  type="date"
            name="date_expiration"
            value="{{ request('date_expiration') }}"
            class="form-control w-20">

        <button type="submit" class="btn btn-outline-primary ms-2">Filtrer</button>

</form>




    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Produit</th>
                <th>Quantité reçue</th>
                <th>Quantité dispo</th>
                <th>Prix unitaire</th>
                <th>Date réception</th>
                <th>Date expiration</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lots as $lot)
                {{-- <tr class="{{ $lot->isExpired() ? 'table-danger' : '' }}"> --}}
                <tr class="
                    @if($lot->quantite_disponible == 0)
                        table-warning
                    @elseif($lot->isExpired())
                        table-danger
                    @endif
                ">
                    <td>{{$lot->id}}</td>
                    <td>{{ $lot->produit->nom }}</td>
                    <td>{{ $lot->quantite_recue }}</td>
                    <td>{{ $lot->quantite_disponible }}</td>
                    <td>{{ number_format($lot->prix_unitaire, 2) }} DH</td>
                    <td>{{ $lot->date_reception->format('Y-m-d') }}</td>
                    <td>{{ $lot->date_expiration->format('Y-m-d') }}</td>

                    <td>
                        @if ($lot->quantite_disponible == 0)
                            ⚠️ Épuisé
                        @elseif ($lot->isExpired())
                            ❌ Périmé
                        @else
                            ✅ Actif
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.stocks.edit', $lot) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form method="POST" action="{{ route('admin.stocks.destroy', $lot) }}" class="d-inline" onsubmit="return confirm('Supprimer ce lot ?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

        <div class="d-flex justify-content-end mt-4">
            {{ $lots->withQueryString()->links() }}
        </div>
@endsection

