{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Connectez-vous pour démarrer votre session</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="input-group mb-3">
                <input id="email" type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" 
                       placeholder="Adresse email" 
                       required autocomplete="email" autofocus>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="input-group mb-3">
                <input id="password" type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       name="password" 
                       placeholder="Mot de passe" 
                       required autocomplete="current-password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">
                            Se souvenir de moi
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Connexion</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        @if (Route::has('password.request'))
        <p class="mb-1">
            <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
        </p>
        @endif
        
        @if (Route::has('register'))
        <p class="mb-0">
            <a href="{{ route('register') }}" class="text-center">Créer un nouveau compte</a>
        </p>
        @endif
    </div>
    <!-- /.login-card-body -->
</div>
@endsection