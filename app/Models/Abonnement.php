<?php
namespace App\Models;

/*
 * Ce fichier fait partie du projet Finance Dashboard
 * Copyright (C) 2024 Floris Robart <florobart.github@gmail.com>
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    use HasFactory;

    protected $connection = 'finance_dashboard';
    protected $table = 'abonnements';

    protected $fillable = [
        'nom_actif',
        'montant_transaction',
        'date_transaction',
        'abonnement_actif',
        'mensuel',
    ];
}
