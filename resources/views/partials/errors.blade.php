{{-- resources/views/partials/errors.blade.php --}}

{{-- Affichage des erreurs de validation --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> 
            @if($errors->count() == 1)
                1 erreur a été détectée
            @else
                {{ $errors->count() }} erreurs ont été détectées
            @endif
        </h5>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Messages de succès --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Succès !</h5>
        <p class="mb-0">{{ session('success') }}</p>
    </div>
@endif

{{-- Messages d'erreur --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Erreur</h5>
        <p class="mb-0">{{ session('error') }}</p>
    </div>
@endif

{{-- Messages d'information --}}
@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-info-circle"></i> Information</h5>
        <p class="mb-0">{{ session('info') }}</p>
    </div>
@endif

{{-- Messages d'avertissement --}}
@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-exclamation-triangle"></i> Attention</h5>
        <p class="mb-0">{{ session('warning') }}</p>
    </div>
@endif