{{--

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

<form action="{{ route('admin.livraisons.create') }}" method="GET" id="formLivraison">

    <div class="mb-3">
        <label for="etablissement_id" class="form-label">Établissement</label>
        <select name="etablissement_id" id="etablissement_id" class="form-control" required onchange="this.form.submit()">
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
        <select name="menu_id" id="menu_id" class="form-control" required onchange="this.form.submit()" {{ $menus->isEmpty() ? 'disabled' : '' }}>
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

</form>

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

<form action="{{ route('admin.livraisons.store') }}" method="POST" class="mt-3">
    @csrf
    <input type="hidden" name="etablissement_id" value="{{ old('etablissement_id', request('etablissement_id')) }}">
    <input type="hidden" name="menu_id" value="{{ old('menu_id', request('menu_id')) }}">
    <div class="mb-3">
        <label for="date_livraison_final" class="form-label">Date de livraison (optionnelle)</label>
        <input type="date" name="date_livraison" id="date_livraison_final" class="form-control" value="{{ old('date_livraison') }}">
    </div>
    <button type="submit" class="btn btn-success">Planifier la livraison</button>
</form>
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

@if ($errors->has('exception'))
    <div class="alert alert-danger">
        Erreur technique : {{ $errors->first('exception') }}
    </div>
@endif


{{-- Formulaire pour filtrer menus selon établissement (auto submit) --}}
<form action="{{ route('admin.livraisons.create') }}" method="GET" id="formFiltreLivraison">
    <div class="mb-3">
        <label for="etablissement_id" class="form-label">Établissement</label>
        <select name="etablissement_id" id="etablissement_id" class="form-control" required onchange="this.form.submit()">
            <option value="">-- Sélectionnez un établissement --</option>
            @foreach($etablissements as $etablissement)
                <option value="{{ $etablissement->id }}" {{ (old('etablissement_id', request('etablissement_id')) == $etablissement->id) ? 'selected' : '' }}>
                    {{ $etablissement->nom }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="menu_id" class="form-label">Menu de la semaine</label>
        <select name="menu_id" id="menu_id" class="form-control" required onchange="this.form.submit()" {{ $menus->isEmpty() ? 'disabled' : '' }}>
            <option value="">-- Sélectionnez un menu --</option>
            @foreach ($menus as $menu)
                @php
                    $dateDebut = \Carbon\Carbon::parse($menu->semaine)->format('d/m/Y');
                    $dateFin = \Carbon\Carbon::parse($menu->semaine)->addDays(6)->format('d/m/Y');
                @endphp
                <option value="{{ $menu->id }}" {{ (old('menu_id', request('menu_id')) == $menu->id) ? 'selected' : '' }}>
                    Semaine du {{ $dateDebut }} au {{ $dateFin }}
                </option>
            @endforeach
        </select>
    </div>
</form>

{{-- Affiche les produits du menu sélectionné --}}
@if(isset($produitsParMenu) && $produitsParMenu->isNotEmpty())
    <h4>Produits du menu sélectionné</h4>
    <ul class="list-group mb-3">
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

{{-- Formulaire final de création de la livraison --}}
<form action="{{ route('admin.livraisons.store') }}" method="POST" class="mt-3">
    @csrf
    <input type="hidden" name="etablissement_id" value="{{ old('etablissement_id', request('etablissement_id')) }}">
    <input type="hidden" name="menu_id" value="{{ old('menu_id', request('menu_id')) }}">

    <div class="mb-3">
        <label for="date_livraison" class="form-label">Date de livraison (optionnelle)</label>
        <input type="date" name="date_livraison" id="date_livraison" class="form-control" value="{{ old('date_livraison') }}">
    </div>

    {{-- Optionnel : statut --}}
    <div class="mb-3">
        <label for="statut" class="form-label">Statut</label>
        <select name="statut" id="statut" class="form-control">
            <option value="en_attente" {{ old('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
            <option value="livrée" {{ old('statut') == 'livrée' ? 'selected' : '' }}>Livrée</option>
            <option value="annulée" {{ old('statut') == 'annulée' ? 'selected' : '' }}>Annulée</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Planifier la livraison</button>
</form>
@endsection
