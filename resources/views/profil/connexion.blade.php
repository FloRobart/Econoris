{{--
Ce fichier fait partie du projet Finance Dashboard
Copyright (C) 2024 Floris Robart <florisrobart.pro@gmail.com>
--}}

<!-- Page de connexion -->
@extends('layouts.page_template')
@section('title')
    Connexion
@endsection

@section('content')
<!-- Titre de la page -->
<livewire:page-title :title="'Connexion'" />

<!-- Lien vers l'inscription -->
<div class="rowCenterContainer mt-8 px-4">
    <span class="normalText">
        Vous n'avez pas de compte ? <a href="{{ route('inscription') }}" class="font-bold hoverText">Inscrivez-vous</a>
    </span>
</div>

<!-- Messages d'erreur et de succès -->
<div class="colCenterContainer mt-8 px-4">
    @if ($errors->any())
        <div class="rowCenterContainer">
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="normalTextError text-center">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <livewire:information-message />
</div>

<!-- Formulaire de connexion -->
<section class="bgPage py-6 lg:py-12 px-4 mx-auto max-w-screen-md">
    <form action="{{ route('connexionSave') }}" method="POST" class="space-y-10">
        @csrf
        <!-- Adresse email -->
        <div>
            <label for="email" class="labelForm">Adresse email <livewire:asterisque /></label>
            @php
                $emailValue = session()->get('email') ? session()->get('email') : '';
            @endphp
            <input name="email" type="email" id="email" autocomplete="email" class="inputForm" placeholder="nom@mail.com" required value="{{ $emailValue }}">
        </div>

        <!-- Mot de passe -->
        <div>
            <livewire:password-input :confirmation="false" :newPassword="false" />

            <!-- lien vers la page de mot de passe oublié -->
            <div class="smallRowEndContainer">
                <a href="{{ route('resetPassword.emailRequest') }}" class="font fontSizeSmall colorFontBleuLogo font-bold hover:underline" title="Cliquez si vous avez oublié votre mot de passe">Mot de passe oublié ?</a>
            </div>
        </div>

        <!-- bouton d'inscription -->
        <div class="smallRowStartContainer">
            <button type="submit" class="buttonForm">Connexion</button>
        </div>
    </form>

    <!-- précision -->
    <div class="smallRowStartContainer mt-3">
        <livewire:asterisque />
        <span class="smallText ml-1">Champs obligatoires</span>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('js/showPassword.js') }}"></script>
@endsection
