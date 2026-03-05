{{-- resources/views/levels/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nouveau niveau')
@section('page_title', 'Créer un niveau')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('levels.index') }}">Niveaux</a></li>
    <li class="breadcrumb-item active">Nouveau</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">Informations du niveau</h3>
                </div>
                
                <form action="{{ route('levels.store') }}" method="POST">
                    @csrf
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">Nom du niveau <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Ex: CP1, 6ème, Seconde..." required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cycle">Cycle <span class="text-danger">*</span></label>
                                    <select class="form-control @error('cycle') is-invalid @enderror" 
                                            id="cycle" name="cycle" required>
                                        <option value="">Sélectionnez</option>
                                        <option value="fondamentale" {{ old('cycle') == 'fondamentale' ? 'selected' : '' }}>
                                            Fondamentale
                                        </option>
                                        <option value="secondaire" {{ old('cycle') == 'secondaire' ? 'selected' : '' }}>
                                            Secondaire
                                        </option>
                                    </select>
                                    @error('cycle')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Sections associées</label>
                            <div class="row">
                                @foreach($sections as $section)
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" 
                                                   name="sections[]" value="{{ $section->id }}" 
                                                   id="section_{{ $section->id }}"
                                                   {{ in_array($section->id, old('sections', [])) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="section_{{ $section->id }}">
                                                {{ $section->name }}
                                                @if($section->is_default)
                                                    <span class="badge bg-info">Par défaut</span>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="form-text text-muted">
                                Sélectionnez les sections qui appartiennent à ce niveau.
                            </small>
                            @error('sections')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <a href="{{ route('levels.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection