@extends('admin.layouts.app')

@section('title', 'Créer un menu de la semaine')

@section('content')
<h1 class="mb-4">Créer un menu de la semaine</h1>

<form method="POST" action="{{ route('admin.menus.store') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Établissement</label>
        <select name="etablissement_id" class="form-select" required>
            @foreach($etablissements as $e)
                <option value="{{ $e->id }}">{{ $e->nom }}</option>
            @endforeach
        </select>
    </div>

    <div class="row">
        {{-- <div class="col-md-6 mb-3">
            <label class="form-label">Date de début de semaine</label>
            <input type="date" name="semaine" class="form-control" required>
        </div> --}}
        <div class="col-md-6 mb-3">
            <label class="form-label">Date de début de semaine</label>
            <input type="date" name="semaine" class="form-control @error('semaine') is-invalid @enderror" value="{{ old('semaine') }}" required>
            @error('semaine')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- <div class="col-md-6 mb-3">
            <label class="form-label">Date de fin de semaine</label>
            <input type="date" name="semaine_fin" class="form-control" required>
        </div> --}}

        <div class="col-md-6 mb-3">
    <label class="form-label">Date de fin de semaine</label>
    <input type="date" name="semaine_fin"
           class="form-control @error('semaine_fin') is-invalid @enderror"
           value="{{ old('semaine_fin') }}"
           required>
    {{-- @error('semaine_fin')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror --}}
</div>
    </div>

    @foreach(['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'] as $jour)
        <h4 class="mt-4 text-primary">{{ ucfirst($jour) }}</h4>

        @foreach(['Petit Déjeuner'=>'petit_dejeuner','Déjeuner'=>'dejeuner','Dîner'=>'diner'] as $label => $type)
            <div class="mb-4 border p-3 rounded">
                <h5 class="text-secondary">{{ $label }}</h5>

                <div class="row mb-2">
                    <div class="col-md-4">
                        <select class="form-select filter-categorie" data-target="{{ $jour }}-{{ $type }}">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control filter-search" placeholder="Rechercher un produit..."
                               data-target="{{ $jour }}-{{ $type }}">
                    </div>
                </div>

                <div class="row produits-zone" id="zone-{{ $jour }}-{{ $type }}">
                    @foreach($categories as $cat)
                        @foreach($produits->where('categorie_id', $cat->id) as $p)
                            <div class="col-md-4 produit-item"
                                 style="display: none;"
                                 data-categorie="{{ $cat->id }}"
                                 data-nom="{{ strtolower($p->nom) }}"
                                 data-section="{{ $jour }}-{{ $type }}">
                                <div class="form-check mb-2">
                                    <input class="form-check-input produit-checkbox" type="checkbox"
                                           id="check_{{ $jour }}_{{ $type }}_{{ $p->id }}"
                                           name="menus[{{ $jour }}][{{ $type }}][{{ $p->id }}]" value="1"
                                           data-quantite-id="quant_{{ $jour }}_{{ $type }}_{{ $p->id }}">
                                    <label class="form-check-label" for="check_{{ $jour }}_{{ $type }}_{{ $p->id }}">
                                        {{ $p->nom }} ({{ $cat->nom }})
                                    </label>
                                    <input type="number"
                                           id="quant_{{ $jour }}_{{ $type }}_{{ $p->id }}"
                                           name="quantites[{{ $jour }}][{{ $type }}][{{ $p->id }}]"
                                           class="form-control form-control-sm mt-1"
                                           placeholder="Quantité"
                                           disabled>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endforeach
    @endforeach

    <button type="submit" class="btn btn-success mt-4">Enregistrer</button>
</form>

{{-- Scripts JS --}}
<script>
    const filterInputs = document.querySelectorAll('.filter-search, .filter-categorie');

    function filtrer(sectionId) {
        const search = document.querySelector(`.filter-search[data-target="${sectionId}"]`).value.toLowerCase();
        const categorie = document.querySelector(`.filter-categorie[data-target="${sectionId}"]`).value;

        const items = document.querySelectorAll(`.produit-item[data-section="${sectionId}"]`);

        items.forEach(item => {
            const nom = item.dataset.nom;
            const cat = item.dataset.categorie;

            const matchNom = nom.includes(search);
            const matchCat = !categorie || cat === categorie;

            // Afficher si un des deux filtres est utilisé et correspond
            if ((search || categorie) && matchNom && matchCat) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    filterInputs.forEach(input => {
        input.addEventListener('input', () => {
            const sectionId = input.dataset.target;
            filtrer(sectionId);
        });
    });

    // Activer le champ quantité uniquement si case cochée
    document.querySelectorAll('.produit-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const inputId = this.dataset.quantiteId;
            const quantInput = document.getElementById(inputId);
            quantInput.disabled = !this.checked;
            if (!this.checked) quantInput.value = '';
        });
    });
</script>
@endsection
