{{-- resources/views/students/partials/student-info.blade.php --}}
<div class="card mb-3">
    <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">👨‍🎓 Informations de l'étudiant</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="nisu">NISU <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nisu') is-invalid @enderror" 
                           id="nisu" name="nisu" value="{{ old('nisu') }}" placeholder="Ex: JEAD19961102G1500001" required>
                    @error('nisu')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="last_name">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                           id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                    @error('last_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
                        <div class="col-md-4">
                <div class="form-group">
                    <label for="first_name">Prénom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                           id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                    @error('first_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="gender">Genre <span class="text-danger">*</span></label>
                    <select class="form-control @error('gender') is-invalid @enderror" 
                            id="gender" name="gender" required>
                        <option value="">Sélectionnez</option>
                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                    @error('gender')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="birth_date">Date de naissance</label>
                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                           id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                    @error('birth_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="section_id">Section par défaut <span class="text-danger">*</span></label>
                    <select class="form-control @error('section_id') is-invalid @enderror" 
                            id="section_id" name="section_id" required>
                        <option value="">Sélectionnez une section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('section_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="student_status">Statut</label>
                    <select class="form-control" id="student_status" name="student_status">
                        <option value="active" {{ old('student_status') == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="archived" {{ old('student_status') == 'archived' ? 'selected' : '' }}>Archivé</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <small class="text-muted">
        Le NISU doit être unique et suivre le format : 
        4 lettres (initiales du nom et prénom ) + 
        8 chiffres (date de naissance YYYYMMDD) + 
        1 lettre (genre G/F) + 
        7 chiffres séquentiels.
    </small>
</div>