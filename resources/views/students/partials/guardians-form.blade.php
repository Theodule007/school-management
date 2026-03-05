{{-- Responsable 1 --}}
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h4 class="card-title">👤 Responsable 1 (Principal)</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="guardian1_last_name">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('guardian1_last_name') is-invalid @enderror" 
                           id="guardian1_last_name" name="guardian1_last_name" 
                           value="{{ old('guardian1_last_name') }}" required>
                    @error('guardian1_last_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>            
            <div class="col-md-4">
                <div class="form-group">
                    <label for="guardian1_first_name">Prénom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('guardian1_first_name') is-invalid @enderror" 
                           id="guardian1_first_name" name="guardian1_first_name" 
                           value="{{ old('guardian1_first_name') }}" required>
                    @error('guardian1_first_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="guardian1_phone">Téléphone <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control @error('guardian1_phone') is-invalid @enderror" 
                           id="guardian1_phone" name="guardian1_phone" 
                           value="{{ old('guardian1_phone') }}" placeholder="77 123 45 67" required>
                    @error('guardian1_phone')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="guardian1_email">Email</label>
                    <input type="email" class="form-control @error('guardian1_email') is-invalid @enderror" 
                           id="guardian1_email" name="guardian1_email" 
                           value="{{ old('guardian1_email') }}">
                    @error('guardian1_email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="guardian1_relationship">Lien de parenté <span class="text-danger">*</span></label>
                    <select class="form-control @error('guardian1_relationship') is-invalid @enderror" 
                            id="guardian1_relationship" name="guardian1_relationship" required>
                        <option value="pere" {{ old('guardian1_relationship') == 'pere' ? 'selected' : '' }}>Père</option>
                        <option value="mere" {{ old('guardian1_relationship') == 'mere' ? 'selected' : '' }}>Mère</option>
                        <option value="tuteur" {{ old('guardian1_relationship') == 'tuteur' ? 'selected' : '' }}>Tuteur</option>
                        <option value="autre" {{ old('guardian1_relationship') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('guardian1_relationship')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="guardian1_address">Adresse</label>
                    <input type="text" class="form-control @error('guardian1_address') is-invalid @enderror" 
                           id="guardian1_address" name="guardian1_address" 
                           value="{{ old('guardian1_address') }}">
                    @error('guardian1_address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Responsable 2 --}}
<div class="card mb-4">
    <div class="card-header bg-warning">
        <h4 class="card-title">👤 Responsable 2 (Secondaire)</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="guardian2_last_name">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('guardian2_last_name') is-invalid @enderror" 
                           id="guardian2_last_name" name="guardian2_last_name" 
                           value="{{ old('guardian2_last_name') }}" required>
                    @error('guardian2_last_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>            
            <div class="col-md-4">
                <div class="form-group">
                    <label for="guardian2_first_name">Prénom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('guardian2_first_name') is-invalid @enderror" 
                           id="guardian2_first_name" name="guardian2_first_name" 
                           value="{{ old('guardian2_first_name') }}" required>
                    @error('guardian2_first_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="guardian2_phone">Téléphone <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control @error('guardian2_phone') is-invalid @enderror" 
                           id="guardian2_phone" name="guardian2_phone" 
                           value="{{ old('guardian2_phone') }}" placeholder="77 123 45 67" required>
                    @error('guardian2_phone')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="guardian2_email">Email</label>
                    <input type="email" class="form-control @error('guardian2_email') is-invalid @enderror" 
                           id="guardian2_email" name="guardian2_email" 
                           value="{{ old('guardian2_email') }}">
                    @error('guardian2_email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="guardian2_relationship">Lien de parenté <span class="text-danger">*</span></label>
                    <select class="form-control @error('guardian2_relationship') is-invalid @enderror" 
                            id="guardian2_relationship" name="guardian2_relationship" required>
                        <option value="pere" {{ old('guardian2_relationship') == 'pere' ? 'selected' : '' }}>Père</option>
                        <option value="mere" {{ old('guardian2_relationship') == 'mere' ? 'selected' : '' }}>Mère</option>
                        <option value="tuteur" {{ old('guardian2_relationship') == 'tuteur' ? 'selected' : '' }}>Tuteur</option>
                        <option value="autre" {{ old('guardian2_relationship') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('guardian2_relationship')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="guardian2_address">Adresse</label>
                    <input type="text" class="form-control @error('guardian2_address') is-invalid @enderror" 
                           id="guardian2_address" name="guardian2_address" 
                           value="{{ old('guardian2_address') }}">
                    @error('guardian2_address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>