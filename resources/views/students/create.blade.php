{{-- resources/views/students/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nouvel étudiant')
@section('page_title', 'Ajouter un étudiant')

@section('content')
<div class="container-fluid">
    @include('partials.errors')
    
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">Nouvel étudiant</h3>
                </div>
                
                <form action="{{ route('students.store') }}" method="POST" id="studentForm">
                    @csrf
                    
                    <div class="card-body">
                        {{-- Informations de l'étudiant --}}
                        @include('students.partials.student-info')
                        
                        <hr>
                        
                        {{-- Choix du mode de sélection des tuteurs --}}
                        <div class="form-group">
                            <label>Mode de sélection des tuteurs</label>
                            <div class="btn-group btn-group-toggle d-block" data-toggle="buttons">
                                <label class="btn btn-outline-primary {{ old('guardian_mode', 'existing') == 'existing' ? 'active' : '' }}">
                                    <input type="radio" name="guardian_mode" value="existing" 
                                           {{ old('guardian_mode', 'existing') == 'existing' ? 'checked' : '' }} 
                                           onchange="toggleGuardianMode()"> 
                                    <i class="fas fa-database"></i> Choisir parmi les tuteurs existants
                                </label>
                                <label class="btn btn-outline-success {{ old('guardian_mode') == 'new' ? 'active' : '' }}">
                                    <input type="radio" name="guardian_mode" value="new" 
                                           {{ old('guardian_mode') == 'new' ? 'checked' : '' }} 
                                           onchange="toggleGuardianMode()">
                                    <i class="fas fa-plus-circle"></i> Créer de nouveaux tuteurs
                                </label>
                            </div>
                        </div>
                        
                        {{-- Section Tuteurs existants --}}
                        <div id="existing-guardians-section" 
                             style="{{ old('guardian_mode', 'existing') == 'existing' ? 'display: block;' : 'display: none;' }}">
                            @include('students.partials.existing-guardians')
                        </div>
                        
                        {{-- Section Nouveaux tuteurs --}}
                        <div id="new-guardians-section" 
                             style="{{ old('guardian_mode') == 'new' ? 'display: block;' : 'display: none;' }}">
                            @include('students.partials.new-guardians')
                        </div>
                        
                        <hr>
                        
                        {{-- Inscription optionnelle --}}
                        @include('students.partials.enrollment-optional')
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer l'étudiant
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
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
function toggleGuardianMode() {
    const mode = document.querySelector('input[name="guardian_mode"]:checked').value;
    const existingSection = document.getElementById('existing-guardians-section');
    const newSection = document.getElementById('new-guardians-section');
    
    if (mode === 'existing') {
        // Mode tuteurs existants
        existingSection.style.display = 'block';
        newSection.style.display = 'none';
        
        // Activer les champs des tuteurs existants
        document.querySelectorAll('#existing-guardians-section select').forEach(field => {
            field.disabled = false;
            field.setAttribute('required', 'required');  // Les rendre requis
        });
        
        // Désactiver les champs des nouveaux tuteurs
        document.querySelectorAll('#new-guardians-section input, #new-guardians-section select').forEach(field => {
            field.disabled = true;
            field.removeAttribute('required');  // Enlever required
        });
        
    } else {
        // Mode nouveaux tuteurs
        existingSection.style.display = 'none';
        newSection.style.display = 'block';
        
        // Désactiver les champs des tuteurs existants
        document.querySelectorAll('#existing-guardians-section select').forEach(field => {
            field.disabled = true;
            field.removeAttribute('required');  // ← CRUCIAL : enlever required
        });
        
        // Activer les champs des nouveaux tuteurs
        document.querySelectorAll('#new-guardians-section input, #new-guardians-section select').forEach(field => {
            field.disabled = false;
            
            // Rendre requis les champs obligatoires
            if (field.name.includes('first_name') || 
                field.name.includes('last_name') || 
                field.name.includes('phone')) {
                field.setAttribute('required', 'required');
            }
        });
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    toggleGuardianMode();
    
    // Écouter les changements de mode
    document.querySelectorAll('input[name="guardian_mode"]').forEach(radio => {
        radio.addEventListener('change', toggleGuardianMode);
    });
});
</script>
{{-- <script>
function toggleGuardianMode() {
    const mode = document.querySelector('input[name="guardian_mode"]:checked').value;
    const existingSection = document.getElementById('existing-guardians-section');
    const newSection = document.getElementById('new-guardians-section');
    
    if (mode === 'existing') {
        existingSection.style.display = 'block';
        newSection.style.display = 'none';
    } else {
        existingSection.style.display = 'none';
        newSection.style.display = 'block';
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    toggleGuardianMode();
});
</script> --}}
@endpush