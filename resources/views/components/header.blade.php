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
                @if (auth()->check() && auth()->user()->pathImgProfil != null)
                    <img class="normalIcons rounded-full" src="{{ asset('img/' . auth()->user()->pathImgProfil) }}" alt="Image de profil">
                @else
                    @if (auth()->check())
                        <svg class="normalIcons colorFontBleuLogo" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    @endif
                @endif

                @if (auth()->check())
                    <span class="normalTextReverse ml-2 flex">
                        {{ auth()->user()->name }}
                    </span>
                @else
                    <!-- Connexion -->
                    <div class="smallRowCenterContainer">
                        <a href="{{ route('accueil') }}" class="hover:underline colorFontReverse" title="Connexion">
                            <span class="smallTextReverse ml-2 flex">Connexion</span>
                        </a>
                    </div>
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
        <div id="breadcrumb" class="rowStartContainer ml-20">
            <span class="smallTextReverse">Fil d'ariane</span>
        </div>
    </div>
</header>
