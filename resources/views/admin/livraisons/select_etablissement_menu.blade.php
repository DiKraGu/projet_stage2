{{-- @extends('admin.layouts.app')

@section('title', 'Nouvelle Livraison')

@section('content')
<div class="container">
    <h2>Créer une livraison</h2>

    <form method="POST" action="{{ route('admin.livraisons.store') }}">
        @csrf

        <div class="mb-3">
            <label for="etablissement_id" class="form-label">Établissement</label>
            <select class="form-select" id="etablissement_id" name="etablissement_id" required>
                <option value="">-- Sélectionner --</option>
                @foreach ($etablissements as $etablissement)
                    <option value="{{ $etablissement->id }}">{{ $etablissement->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="menu_id" class="form-label">Menu Hebdomadaire</label>
            <select class="form-select" id="menu_id" name="menu_id" required>
                <option value="">-- Sélectionner un menu --</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="date_livraison" class="form-label">Date Livraison (optionnelle)</label>
            <input type="date" class="form-control" name="date_livraison" id="date_livraison">
        </div>

        <div id="produits-liste" class="mt-4">
            <p>Sélectionnez un menu pour afficher les produits.</p>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Créer Livraison</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('etablissement_id').addEventListener('change', function () {
        const etablissementId = this.value;
        const menuSelect = document.getElementById('menu_id');
        const produitsListe = document.getElementById('produits-liste');
        menuSelect.innerHTML = '<option value="">Chargement...</option>';
        produitsListe.innerHTML = '';

        if (!etablissementId) return;

        fetch(`{{ route('admin.livraisons.menusParEtablissement') }}?etablissement_id=${etablissementId}`)
            .then(res => res.json())
            .then(data => {
                menuSelect.innerHTML = '<option value="">-- Sélectionner un menu --</option>';
                if (data.length === 0) {
                    menuSelect.innerHTML += '<option disabled>Aucun menu</option>';
                } else {
                    data.forEach(menu => {
                        const semaine = new Date(menu.semaine);
                        const dateDebut = semaine.toISOString().split('T')[0];
                        const dateFin = new Date(semaine.getTime() + 6 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
                        menuSelect.innerHTML += `<option value="${menu.id}">Semaine du ${dateDebut} au ${dateFin}</option>`;
                    });
                }
            });
    });

    document.getElementById('menu_id').addEventListener('change', function () {
        const menuId = this.value;
        const produitsListe = document.getElementById('produits-liste');

        if (!menuId) return;

        fetch(`{{ route('admin.livraisons.produitsParMenu') }}?menu_id=${menuId}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    produitsListe.innerHTML = '<div class="alert alert-warning">Aucun produit pour ce menu.</div>';
                } else {
                    let html = '<ul class="list-group">';
                    data.forEach(prod => {
                        html += `<li class="list-group-item d-flex justify-content-between">
                                    ${prod.nom}
                                    <span class="badge bg-primary">${prod.quantite_utilisee} unités</span>
                                </li>`;
                    });
                    html += '</ul>';
                    produitsListe.innerHTML = html;
                }
            });
    });
</script>
@endsection --}}

@extends('admin.layouts.app')

@section('title', 'Planifier une livraison')

@section('content')
<h1>Planifier une livraison</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.livraisons.store') }}" method="POST" id="formLivraison">
    @csrf

    <div class="mb-3">
        <label for="etablissement_id" class="form-label">Établissement</label>
        <select name="etablissement_id" id="etablissement_id" class="form-control" required>
            <option value="">-- Sélectionnez un établissement --</option>
            @foreach($etablissements as $etablissement)
                <option value="{{ $etablissement->id }}"
                    {{ old('etablissement_id', request('etablissement_id')) == $etablissement->id ? 'selected' : '' }}>
                    {{ $etablissement->nom }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="menu_id" class="form-label">Menu de la semaine</label>
        <select name="menu_id" id="menu_id" class="form-control" required>
            <option value="">-- Sélectionnez un menu --</option>
            @foreach ($menus as $menu)
                @php
                    $dateDebut = \Carbon\Carbon::parse($menu->semaine)->format('Y-m-d');
                    $dateFin = \Carbon\Carbon::parse($menu->semaine)->addDays(6)->format('Y-m-d');
                @endphp
                <option value="{{ $menu->id }}"
                    {{ old('menu_id', request('menu_id')) == $menu->id ? 'selected' : '' }}>
                    Semaine du {{ $dateDebut }} au {{ $dateFin }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="date_livraison" class="form-label">Date de livraison (optionnelle)</label>
        <input type="date" name="date_livraison" id="date_livraison" class="form-control"
            value="{{ old('date_livraison', request('date_livraison')) }}">
    </div>

    @if(isset($produitsParMenu) && $produitsParMenu->isNotEmpty())
        <h4>Produits du menu sélectionné</h4>
        <ul class="list-group">
            @foreach ($produitsParMenu as $prod)
                <li class="list-group-item d-flex justify-content-between">
                    {{ $prod->nom }}
                    <span class="badge bg-primary">{{ $prod->quantite_utilisee }} unités</span>
                </li>
            @endforeach
        </ul>
    @elseif(request()->has('menu_id'))
        <p>Aucun produit pour ce menu.</p>
    @endif

    <button type="submit" class="btn btn-success mt-3">Planifier la livraison</button>
</form>
@endsection
