{{-- resources/views/students/partials/new-guardians.blade.php --}}
<div class="card mb-3">
    <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">👤 Création des nouveaux tuteurs</h5>
    </div>
    <div class="card-body">
        {{-- Tuteur 1 --}}
        <div class="border p-3 mb-3 rounded">
            <h6 class="bg-light p-2">Responsable 1 (Principal)</h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('guardian1_last_name') is-invalid @enderror" 
                               name="guardian1_last_name" value="{{ old('guardian1_last_name') }}">
                        @error('guardian1_last_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Prénom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('guardian1_first_name') is-invalid @enderror" 
                               name="guardian1_first_name" value="{{ old('guardian1_first_name') }}">
                        @error('guardian1_first_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Téléphone <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control @error('guardian1_phone') is-invalid @enderror" 
                               name="guardian1_phone" value="{{ old('guardian1_phone') }}">
                        @error('guardian1_phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control @error('guardian1_email') is-invalid @enderror" 
                               name="guardian1_email" value="{{ old('guardian1_email') }}">
                        @error('guardian1_email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Lien de parenté</label>
                        <select class="form-control" name="guardian1_relationship">
                            <option value="pere" {{ old('guardian1_relationship') == 'pere' ? 'selected' : '' }}>Père</option>
                            <option value="mere" {{ old('guardian1_relationship') == 'mere' ? 'selected' : '' }}>Mère</option>
                            <option value="tuteur" {{ old('guardian1_relationship') == 'tuteur' ? 'selected' : '' }}>Tuteur</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Adresse</label>
                        <input type="text" class="form-control" name="guardian1_address" value="{{ old('guardian1_address') }}">
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Tuteur 2 --}}
        <div class="border p-3 rounded">
            <h6 class="bg-light p-2">Responsable 2 (Secondaire)</h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('guardian2_last_name') is-invalid @enderror" 
                               name="guardian2_last_name" value="{{ old('guardian2_last_name') }}">
                        @error('guardian2_last_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Prénom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('guardian2_first_name') is-invalid @enderror" 
                               name="guardian2_first_name" value="{{ old('guardian2_first_name') }}">
                        @error('guardian2_first_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Téléphone <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control @error('guardian2_phone') is-invalid @enderror" 
                               name="guardian2_phone" value="{{ old('guardian2_phone') }}">
                        @error('guardian2_phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control @error('guardian2_email') is-invalid @enderror" 
                               name="guardian2_email" value="{{ old('guardian2_email') }}">
                        @error('guardian2_email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Lien de parenté</label>
                        <select class="form-control" name="guardian2_relationship">
                            <option value="pere" {{ old('guardian2_relationship') == 'pere' ? 'selected' : '' }}>Père</option>
                            <option value="mere" {{ old('guardian2_relationship') == 'mere' ? 'selected' : '' }}>Mère</option>
                            <option value="tuteur" {{ old('guardian2_relationship') == 'tuteur' ? 'selected' : '' }}>Tuteur</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Adresse</label>
                        <input type="text" class="form-control" name="guardian2_address" value="{{ old('guardian2_address') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>