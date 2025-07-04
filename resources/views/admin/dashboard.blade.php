@extends('admin.layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    <h1 class="mb-4">Bienvenue sur le tableau de bord Admin</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card text-bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Nombre de Régions</h5>
                    <p class="card-text fs-2">{{ $nbRegions }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Nombre d'Établissements</h5>
                    <p class="card-text fs-2">{{ $nbEtablissements }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
