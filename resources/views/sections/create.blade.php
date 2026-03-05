@extends('layouts.app')

@section('title', 'Nouvelle section')
@section('page_title', 'Créer une section/classe')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('sections.index') }}">Sections</a></li>
    <li class="breadcrumb-item active">Nouvelle</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations de la section</h3>
                </div>
                <form action="{{ route('sections.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nom de la section/classe <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="Ex: CM1 A, 6ème B, Terminale D..." required>
                            <small class="form-text text-muted">
                                Exemples: CP1 A, CE2 B, 6ème A, 3ème B, Seconde C, Terminale D
                            </small>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Description (optionnel)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" 
                                    name="description" 
                                    rows="3"
                                    placeholder="Description détaillée de la section...">{{ old('description') }}</textarea>
                            <small class="form-text text-muted">
                                Utilisez ce champ pour expliquer la signification d'un sigle (ex: CP = Cours Préparatoire)
                            </small>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="level_id">Niveau <span class="text-danger">*</span></label>
                            <select class="form-control @error('level_id') is-invalid @enderror" 
                                    id="level_id" name="level_id" required>
                                <option value="">Sélectionnez un niveau</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('level_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
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