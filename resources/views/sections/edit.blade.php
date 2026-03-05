{{-- resources/views/sections/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Modifier la section')
@section('page_title', 'Modifier la section/classe')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('sections.index') }}">Sections</a></li>
    <li class="breadcrumb-item active">{{ $section->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier la section
                    </h3>
                </div>
                
                <form action="{{ route('sections.update', $section) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        {{-- Nom de la section --}}
                        <div class="form-group">
                            <label for="name">Nom de la section <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $section->name) }}" 
                                   placeholder="Ex: SVT, SMP, SES..."
                                   required>
                            <small class="form-text text-muted">
                                Le nom officiel de la classe (ex: SVT, SMP, SES...)
                            </small>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Description de la section (NOUVEAU) --}}
                        <div class="form-group">
                            <label for="description">Description (optionnel)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Description détaillée de la section...">{{ old('description', $section->description ?? '') }}</textarea>
                            <small class="form-text text-muted">
                                Utilisez ce champ pour expliquer la signification d'un sigle ou donner des informations supplémentaires sur cette section.
                            </small>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Section par défaut --}}
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       class="custom-control-input @error('is_default') is-invalid @enderror" 
                                       id="is_default" 
                                       name="is_default" 
                                       value="1"
                                       {{ old('is_default', $section->is_default) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_default">
                                    <strong>Section par défaut</strong>
                                </label>
                                <small class="form-text text-muted">
                                    Une section par défaut est automatiquement proposée lors de la création d'étudiants.
                                </small>
                            </div>
                            @error('is_default')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Note informative sur les niveaux --}}
                        {{-- <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Note :</strong> Pour associer cette section à des niveaux, utilisez la 
                            <a href="{{ route('levels.index') }}" class="alert-link">gestion des affectations niveau-section</a>.
                            La modification d'une section ne concerne que ses propres informations.
                        </div> --}}
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                        <a href="{{ route('sections.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection