{{--
Ce fichier fait partie du projet Finance Dashboard
Copyright (C) 2024 Floris Robart <florisrobart.pro@gmail.com>
--}}

<div class="colCenterContainer">
    @if (session()->has('error'))
        <div class="rowCenterContainer">
            <span class="normalTextError text-center">{{ session()->get('error') }}</span>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="rowCenterContainer">
            <span class="normalTextValid text-center">{{ session()->get('success') }}</span>
        </div>
    @endif

    @if (session()->has('message'))
        <div class="rowCenterContainer">
            <span class="normalTextAlert text-center">{{ session()->get('message') }}</span>
        </div>
    @endif
</div>
