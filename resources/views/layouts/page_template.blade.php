{{--
page_template.blade.php

Copyright (C) 2024 Floris Robart

Authors: Floris Robart <florisrobart.pro@gmail.com>

This program is free software; you can redistribute it and/or modify it
under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation; either version 2.1 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with this program; if not, write to the Free Software Foundation,
Inc., 51 Franklin Street, Fifth Floor, Boston MA 02110-1301, USA.
--}}

<!-- En-tête de la page -->
@include('components.head')

@section('title')
    @yield('title', 'Finances dashboard')
@endsection

@section('styles')
    @yield('styles')
@endsection

@section('scripts')
    @yield('scripts')
@endsection

    <body class="w-full bgPage">
        <!-- Header de la page -->
        <!----------------------->
        @include('components.header')

        <!-- Contenu de la page -->
        <!------------------------>
        <main class="w-full">
            @yield('content')
        </main>

        <!-- Pied de page de la page -->
        <!----------------------------->
        @include('components.footer')
    </body>
</html>