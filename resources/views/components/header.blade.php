{{--
Ce fichier fait partie du projet Finance Dashboard
Copyright (C) 2024 Floris Robart <florisrobart.pro@gmail.com>
--}}

<header id="top" class="colCenterContainer space-y-6 pt-4 bgBleuSombre">
    <!-- Partie haute du header -->
    <div class="rowBetweenContainer">
        <!-- Logo + lien vers la page d'accueil -->
        <div class="smallRowCenterContainer mx-6 min-[400px]:mx-14">
            <a href="{{ route('accueil') }}" class="logo">
                <img class="w-20 sm:w-28" src="{{ asset('img/logo/logo_white.png') }}" alt="Logo">
            </a>
        </div>

        <!-- Profil -->
        <div class="smallRowCenterContainer mx-6 min-[400px]:mx-14">
            @if (auth()->check())
                <a href="{{ route('profil') }}" class="colorFontReverse hoverText" title="Profil">
            @endif

            <div class="smallRowCenterContainer">
                <!-- Icône du profil -->
                <svg class="normalIcons colorFontBleuLogo" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>

                @if (auth()->check())
                    <span class="normalTextReverse ml-2 flex">
                        {{ auth()->user()->name }}
                    </span>
                @else
                    <!-- Connexion -->
                    <a href="{{ route('accueil') }}" class="hover:underline colorFontReverse" title="Connexion">
                        <span class="smallTextReverse ml-2 flex">Connexion</span>
                    </a>
                @endif
            </div>

            @if (auth()->check())
                </a>
            @endif
        </div>
    </div>

    <!-- Partie basse du header -->
    <div class="rowBetweenContainer bgBleuFonce2 py-3">
        <!-- Fil d'ariane -->
        @php
            $urlInfo = parse_url(URL::current());
            $urlPath = $urlInfo['path'] ?? null;
            $breadcrumb = [];

            if ($urlPath !== null)
            {
                $breadcrumb = [
                    /* Niveau 1 */
                    'connexion' => route('login'),
                    'inscription' => route('inscription'),
                    'profil' => route('profil'),
                    'salaires' => route('salaires'),
                    'epargnes' => route('epargnes'),
                    'investissements' => route('investissements'),
                    'abonnements' => route('abonnements'),
                    'emprunts' => route('emprunts'),
                    'depenses' => route('depenses'),
                    'prets' => route('prets'),
                    
                    /* Niveau 2 */
                    'abonnements_histories' => route('abonnements_histories'),
                    'emprunts_histories' => route('emprunts_histories'),
                    'date' => parseUrl($urlPath, 'date'),

                    /* Niveau 3 */
                    'employeur' => parseUrl($urlPath, 'employeur'),
                    'banque' => parseUrl($urlPath, 'banque'),
                    'compte' => parseUrl($urlPath, 'compte'),
                    'type' => parseUrl($urlPath, 'type'),
                    'nom_actif' => parseUrl($urlPath, 'nom_actif'),
                    'nom_emprunteur' => parseUrl($urlPath, 'nom_emprunteur'),

                    /* Niveau 4 */
                    'abonnement_actif' => parseUrl($urlPath, 'abonnement_actif'),
                ];
            }


            function parseUrl(string $url, string $limit)
            {
                $urlArray = explode('/', $url);
                $limitNumber = array_search($limit, $urlArray) == false ? count($urlArray) : (array_search($limit, $urlArray) + 2);

                $urlArray = array_slice($urlArray, 0, $limitNumber);
                $urlArray = implode('/', $urlArray);

                return $urlArray;
            }
        @endphp

        <div id="breadcrumb" class="rowStartContainer px-4 min-[550px]:px-6 sm:px-14">
            <!-- Accueil -->
            <a href="{{ route('accueil') }}" class="tinyTextReverse sm:smallTextReverse">Accueil</a>

            @foreach ($breadcrumb as $key => $value)
                @if (str_contains(strtolower($urlPath), $key))
                    <livewire:breadcrumb-link name="{{ ucfirst($key) }}" link="{{ $value }}" />
                @endif
            @endforeach
        </div>
    </div>
</header>
