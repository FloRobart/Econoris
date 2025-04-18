<?php
namespace App\Http\Middleware;

/*
 * Ce fichier fait partie du projet Finance Dashboard
 * Copyright (C) 2024 Floris Robart <florobart.github@gmail.com>
 */

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AdresseIP;
use Illuminate\Support\Facades\Auth;


class VerifIP
{
    /**
     * Vérifie si l'adresse IP est sur la liste blanche et n'est pas bannie
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /* Vérifie que l'utilisateur est connecté */
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /* Vérification de l'adresse IP */
        $saveIp = AdresseIP::where('adresse_ip', $request->ip())->where('user_id', Auth::id())->first();
        if ($saveIp == null) {
            abort(403, "Vous n'avez pas accès à cette page");
        } elseif ($saveIp->est_bannie == 1) {
            abort(403, "Vous avez été bannie, en continuant vous vous exposez à des poursuites judiciaires");
        }

        return $next($request);
    }
}