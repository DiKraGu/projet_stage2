@extends('admin.layouts.app')

@section('title', isset($fournisseur) ? 'Modifier Fournisseur' : 'Ajouter Fournisseur')

@section('content')
    <h1>{{ isset($fournisseur) ? 'Modifier' : 'Ajouter' }} Fournisseur</h1>

    <form action="{{ isset($fournisseur) ? route('admin.fournisseurs.update', $fournisseur) : route('admin.fournisseurs.store') }}" method="POST">
        @csrf
        @if(isset($fournisseur)) @method('PUT') @endif

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom', $fournisseur->nom ?? '') }}" required>

            @error('nom')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="contact" class="form-label">Contact</label>
            <input type="text" name="contact" class="form-control" value="{{ old('contact', $fournisseur->contact ?? '') }}">

            @error('contact')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>


        <button type="submit" class="btn btn-success">Enregistrer</button>
    </form>
@endsection
