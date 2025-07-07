@extends('admin.layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    <h1 class="mb-4">Bienvenue sur le tableau de bord Admin</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="card text-bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Régions</h5>
                    <p class="card-text fs-2">{{ $nbRegions }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Villes</h5>
                    <p class="card-text fs-2">{{ $nbVilles }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Provinces</h5>
                    <p class="card-text fs-2">{{ $nbProvinces }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Établissements</h5>
                    <p class="card-text fs-2">{{ $nbEtablissements }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Fournisseurs</h5>
                    <p class="card-text fs-2">{{ $nbFournisseurs }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Categories</h5>
                    <p class="card-text fs-2">{{ $nbCategories }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Produits</h5>
                    <p class="card-text fs-2">{{ $nbProduits }}</p>
                </div>
            </div>
        </div>

    </div>
@endsection
