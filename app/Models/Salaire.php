<?php
namespace App\Models;

/*
 * Ce fichier fait partie du projet Finance Dashboard
 * Copyright (C) 2024 Floris Robart <florobart.github.com>
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salaire extends Model
{
    use HasFactory;

    protected $connection = 'finance_dashboard';
    protected $table = 'salaires';

    protected $fillable = [
        'id',
        'user_id',
        'date_transaction',
        'montant_transaction',
        'employeur'
    ];
}
