{{-- resources/views/subjects/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nouvelle matière')
@section('page_title', 'Créer une matière')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Matières</a></li>
    <li class="breadcrumb-item active">Nouvelle</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Nouvelle matière</h3>
                </div>
                
                <form action="{{ route('subjects.store') }}" method="POST" id="subjectForm">
                    @csrf
                    
                    <div class="card-body">
                        {{-- Informations de base --}}
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">Nom de la matière <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Ex: Mathématiques, Français, Physique..." required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Statut</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status">
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> --}}
                        </div>

                        <hr>
                        
                        {{-- Section d'assignation aux niveaux et sections --}}
                        <h4>Assignation aux niveaux et sections</h4>
                        <p class="text-muted">Sélectionnez les combinaisons niveau-section où cette matière sera enseignée avec leur coefficient.</p>
                        
                        <div id="assignments-container">
                            {{-- Template pour une nouvelle assignation --}}
                            <div class="assignment-row border p-3 mb-3 rounded bg-light">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Niveau</label>
                                            <select class="form-control level-select" name="level_sections[0][level_id]" required>
                                                <option value="">Choisir un niveau</option>
                                                @foreach($levels as $level)
                                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Section</label>
                                            <select class="form-control section-select" name="level_sections[0][section_id]" required disabled>
                                                <option value="">D'abord choisir un niveau</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Coefficient</label>
                                            <input type="number" step="0.5" min="0.5" max="10" 
                                                   class="form-control coefficient-input" 
                                                   name="level_sections[0][coefficient]" 
                                                   value="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm mt-4 remove-assignment" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-sm btn-success" id="add-assignment">
                            <i class="fas fa-plus"></i> Ajouter une assignation
                        </button>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let assignmentCount = 1;
    
    // Fonction pour charger les sections d'un niveau
    function loadSections(levelSelect, sectionSelect) {
        const levelId = levelSelect.value;
        
        if (levelId) {
            sectionSelect.disabled = false;
            sectionSelect.innerHTML = '<option value="">Chargement...</option>';
            
            fetch(`/api/level-sections?level_id=${levelId}`)
                .then(response => response.json())
                .then(data => {
                    sectionSelect.innerHTML = '<option value="">Choisir une section</option>';
                    
                    // Grouper par section
                    const sectionsMap = new Map();
                    data.forEach(ls => {
                        if (!sectionsMap.has(ls.section.id)) {
                            sectionsMap.set(ls.section.id, {
                                id: ls.section.id,
                                name: ls.section.name,
                                levelSectionId: ls.id
                            });
                        }
                    });
                    
                    sectionsMap.forEach(section => {
                        sectionSelect.innerHTML += `<option value="${section.levelSectionId}">${section.name}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    sectionSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                });
        } else {
            sectionSelect.disabled = true;
            sectionSelect.innerHTML = '<option value="">D\'abord choisir un niveau</option>';
        }
    }
    
    // Ajouter une nouvelle ligne d'assignation
    document.getElementById('add-assignment').addEventListener('click', function() {
        const container = document.getElementById('assignments-container');
        const template = container.querySelector('.assignment-row').cloneNode(true);
        
        // Mettre à jour les indices
        template.innerHTML = template.innerHTML.replace(/\[0\]/g, `[${assignmentCount}]`);
        
        // Réinitialiser les valeurs
        template.querySelectorAll('select').forEach(select => select.value = '');
        template.querySelector('.section-select').disabled = true;
        template.querySelector('.coefficient-input').value = '1';
        template.querySelector('.remove-assignment').style.display = 'inline-block';
        
        container.appendChild(template);
        
        // Attacher les événements
        const levelSelect = template.querySelector('.level-select');
        const sectionSelect = template.querySelector('.section-select');
        
        levelSelect.addEventListener('change', function() {
            loadSections(levelSelect, sectionSelect);
        });
        
        template.querySelector('.remove-assignment').addEventListener('click', function() {
            template.remove();
        });
        
        assignmentCount++;
    });
    
    // Gestionnaire pour la première ligne
    const firstLevelSelect = document.querySelector('.level-select');
    const firstSectionSelect = document.querySelector('.section-select');
    
    firstLevelSelect.addEventListener('change', function() {
        loadSections(firstLevelSelect, firstSectionSelect);
    });
});
</script>
@endpush