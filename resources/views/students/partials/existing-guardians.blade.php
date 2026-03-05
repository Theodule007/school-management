{{-- resources/views/students/partials/existing-guardians.blade.php --}}
<div class="card mb-3">
    <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">👥 Sélection des tuteurs existants</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="guardian_id_1">Tuteur 1 (Principal) <span class="text-danger">*</span></label>
                    <select class="form-control @error('guardian_id_1') is-invalid @enderror" 
                            id="guardian_id_1" name="guardian_id_1" required>
                        <option value="">Sélectionnez un tuteur</option>
                        @foreach($guardians as $guardian)
                            <option value="{{ $guardian->id }}" {{ old('guardian_id_1') == $guardian->id ? 'selected' : '' }}>
                                {{ $guardian->full_name }} - {{ $guardian->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('guardian_id_1')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="guardian_id_2">Tuteur 2 (Secondaire) <span class="text-danger">*</span></label>
                    <select class="form-control @error('guardian_id_2') is-invalid @enderror" 
                            id="guardian_id_2" name="guardian_id_2" required>
                        <option value="">Sélectionnez un tuteur</option>
                        @foreach($guardians as $guardian)
                            <option value="{{ $guardian->id }}" {{ old('guardian_id_2') == $guardian->id ? 'selected' : '' }}>
                                {{ $guardian->full_name }} - {{ $guardian->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('guardian_id_2')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> 
                    Vous ne trouvez pas un tuteur ? 
                    <a href="{{ route('guardians.create') }}" target="_blank">Créez-le d'abord ici</a> 
                    puis revenez sur cette page.
                </small>
            </div>
        </div>
    </div>
</div>