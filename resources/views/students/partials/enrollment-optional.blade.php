{{-- resources/views/students/partials/enrollment-optional.blade.php --}}
<div class="card">
    <div class="card-header bg-secondary text-white">
        <h4 class="card-title mb-0">📚 Inscription (optionnelle)</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="academic_year_id">Année d'inscription</label>
                    <select class="form-control @error('academic_year_id') is-invalid @enderror" 
                            id="academic_year_id" name="academic_year_id">
                        <option value="">Sélectionnez (optionnel)</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="level_section_id">Niveau / Section</label>
                    <select class="form-control @error('level_section_id') is-invalid @enderror" 
                            id="level_section_id" name="level_section_id" disabled>
                        <option value="">Sélectionnez d'abord une année</option>
                    </select>
                    @error('level_section_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <small class="text-muted">
            <i class="fas fa-info-circle"></i> 
            L'inscription permet d'enregistrer l'étudiant dans une classe pour une année spécifique.
        </small>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.getElementById('academic_year_id');
    const levelSectionSelect = document.getElementById('level_section_id');
    
    if (yearSelect && levelSectionSelect) {
        yearSelect.addEventListener('change', function() {
            const sectionId = document.getElementById('section_id').value; // Récupérer la section sélectionnée
            
            if (sectionId) {
                levelSectionSelect.disabled = false;
                levelSectionSelect.innerHTML = '<option value="">Chargement...</option>';
                
                fetch(`/api/level-sections-by-section/${sectionId}`)
                    .then(response => response.json())
                    .then(data => {
                        levelSectionSelect.innerHTML = '<option value="">Sélectionnez niveau/section</option>';
                        if (data.length === 0) {
                            levelSectionSelect.innerHTML += '<option value="" disabled>Aucune combinaison disponible</option>';
                        } else {
                            data.forEach(item => {
                                levelSectionSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        levelSectionSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    });
            } else {
                levelSectionSelect.disabled = true;
                levelSectionSelect.innerHTML = '<option value="">Sélectionnez d\'abord une année</option>';
            }
        });
    }
        // Si une ancienne valeur existe, la restaurer
    const oldValue = "{{ old('level_section_id') }}";
    if (oldValue) {
        levelSectionSelect.value = oldValue;
    }
});
// document.addEventListener('DOMContentLoaded', function() {
//     const yearSelect = document.getElementById('academic_year_id');
//     const levelSectionSelect = document.getElementById('level_section_id');
    
//     if (yearSelect && levelSectionSelect) {
//         yearSelect.addEventListener('change', function() {
//             const yearId = this.value;
            
//             if (yearId) {
//                 levelSectionSelect.disabled = false;
//                 levelSectionSelect.innerHTML = '<option value="">Chargement...</option>';
                
//                 fetch(`/api/level-sections-by-year/${yearId}`)
//                     .then(response => response.json())
//                     .then(data => {
//                         levelSectionSelect.innerHTML = '<option value="">Sélectionnez niveau/section</option>';
//                         if (data.length === 0) {
//                             levelSectionSelect.innerHTML += '<option value="" disabled>Aucune combinaison disponible</option>';
//                         } else {
//                             data.forEach(item => {
//                                 levelSectionSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
//                             });
//                         }
//                     })
//                     .catch(error => {
//                         console.error('Erreur:', error);
//                         levelSectionSelect.innerHTML = '<option value="">Erreur de chargement</option>';
//                     });
//             } else {
//                 levelSectionSelect.disabled = true;
//                 levelSectionSelect.innerHTML = '<option value="">Sélectionnez d\'abord une année</option>';
//             }
//         });
//     }
// });
</script>
@endpush