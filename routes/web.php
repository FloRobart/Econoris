<?php
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\PrivateController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

/*
 * Ce fichier fait partie du projet Finance Dashboard
 * Copyright (C) 2024 Floris Robart <florisrobart.pro@gmail.com>
 */



/*---------------------------------*/
/* Route pour la gestion du profil */
/*        ProfilController         */
/*---------------------------------*/
/* Inscription */
Route::get('/inscription', [ProfilController::class, 'inscription'])->name('inscription');
Route::post('/inscription', [ProfilController::class, 'inscriptionSave'])->name('inscriptionSave');

/* Profil */
Route::get('/profil', [ProfilController::class, 'profil'])->middleware('auth')->name('profil');
Route::post('/profil', [ProfilController::class, 'profilSave'])->middleware('auth')->name('profilSave');

/* Connexion */
Route::get('/connexion', [ProfilController::class, 'connexion'])->middleware('guest')->name('login');
Route::post('/connexion', [ProfilController::class, 'connexionSave'])->middleware('throttle:5,1')->name('connexionSave'); // 5 tentatives de connexion par minute, puis blocage pendant 1 minute
Route::post('/connexionPost', [ProfilController::class, 'connexionPost'])->name('connexionPost');

/* Déconnexion */
Route::get('/deconnexion', [ProfilController::class, 'deconnexion'])->middleware('auth')->name('deconnexion');

/* Suppression de compte */
Route::get('/supprimerCompte', [ProfilController::class, 'supprimerCompte'])->middleware('auth')->name('supprimerCompte');



/*------------------------------------------------*/
/* Route pour la réinitialisation du mot de passe */
/*            ResetPasswordController             */
/*------------------------------------------------*/
/* Réinitialisation du mot de passe */
Route::get('/resetPassword/emailRequest', [ResetPasswordController::class, 'emailRequest'])->middleware('guest')->name('resetPassword.emailRequest');
Route::post('/resetPassword/emailRequest', [ResetPasswordController::class, 'emailRequestSave'])->middleware('guest')->name('resetPassword.emailRequestSave');
Route::get('/resetPasswordSave/{token}', [ResetPasswordController::class, 'resetPassword'])->middleware('guest')->name('password.reset'); // Ne surtout pas changer le nom ni l'URL de cette route
Route::post('/resetPasswordSave', [ResetPasswordController::class, 'resetPasswordSave'])->middleware('guest')->name('password.reset.save');



/*-----------------------------*/
/* Route pour les utilisateurs */
/*      PrivateController      */
/*-----------------------------*/
Route::middleware(['auth'])->group(function () {
    /*=========*/
    /* Accueil */
    /*=========*/
    /* Route vers l'accueil du dashboard */
    Route::get('/', [PrivateController::class, 'accueil'])->name('accueil');
    Route::get('/accueil/general', [PrivateController::class, 'accueil'])->name('accueil.general');
    Route::get('/accueil/private', [PrivateController::class, 'accueil'])->name('private.accueil');



    /*===========*/
    /* Dashboard */
    /*===========*/
    /*----------*/
    /* Salaires */
    /*----------*/
    /* Affichage des salaires */
    Route::get('/salaires', [PrivateController::class, 'salaires'])->name('salaires');
    Route::get('/salaires/date/{date}', [PrivateController::class, 'salairesDate'])->name('salaires.date');
    Route::get('/salaires/employeur/{employeur}', [PrivateController::class, 'salairesEmployeur'])->name('salaires.employeur');
    Route::get('/salaires/date/{date}/employeur/{employeur}', [PrivateController::class, 'salairesDateEmployeur'])->name('salaires.date.employeur');

    /* Édition des salaires */
    Route::post('/salaire/add', [PrivateController::class, 'addSalaire'])->name('salaire.add');
    Route::post('/salaire/edit', [PrivateController::class, 'editSalaire'])->name('salaire.edit');
    Route::get('/salaire/remove/{id}', [PrivateController::class, 'removeSalaire'])->name('salaire.remove');


    /*----------*/
    /* Épargnes */
    /*----------*/
    /* Affichage des épargnes */
    Route::get('/epargnes', [PrivateController::class, 'epargnes'])->name('epargnes');
    Route::get('/epargnes/date/{date}', [PrivateController::class, 'epargnesDate'])->name('epargnes.date');
    Route::get('/epargnes/banque/{banque}', [PrivateController::class, 'epargnesBanque'])->name('epargnes.banque');
    route::get('/epargnes/compte/{compte}', [PrivateController::class, 'epargnesCompte'])->name('epargnes.compte');
    Route::get('/epargnes/date/{date}/banque/{banque}', [PrivateController::class, 'epargnesDateBanque'])->name('epargnes.date.banque');
    Route::get('/epargnes/date/{date}/compte/{compte}', [PrivateController::class, 'epargnesDateCompte'])->name('epargnes.date.compte');
    Route::get('/epargnes/banque/{banque}/compte/{compte}', [PrivateController::class, 'epargnesBanqueCompte'])->name('epargnes.banque.compte');
    Route::get('/epargnes/date/{date}/banque/{banque}/compte/{compte}', [PrivateController::class, 'epargnesDateBanqueCompte'])->name('epargnes.date.banque.compte');

    /* Édition des épargnes */
    Route::post('/epargne/add', [PrivateController::class, 'addEpargne'])->name('epargne.add');
    Route::post('/epargne/edit', [PrivateController::class, 'editEpargne'])->name('epargne.edit');
    Route::get('/epargne/remove/{id}', [PrivateController::class, 'removeEpargne'])->name('epargne.remove');


    /*-----------------*/
    /* Investissements */
    /*-----------------*/
    /* Affichage des investissements */
    Route::get('/investissements', function () { return redirect(route('investissements.type', 'investissements')); })->name('investissements');
    Route::get('/investissements/date/{date}', [PrivateController::class, 'investissementsDate'])->name('investissements.date');
    Route::get('/investissements/type/{type}', [PrivateController::class, 'investissementsType'])->name('investissements.type');
    Route::get('/investissements/nom_actif/{nom_actif}', [PrivateController::class, 'investissementsNom'])->name('investissements.nom_actif');
    Route::get('/investissements/date/{date}/type/{type}', [PrivateController::class, 'investissementsDateType'])->name('investissements.date.type');
    Route::get('/investissements/date/{date}/nom_actif/{nom_actif}', [PrivateController::class, 'investissementsDateNom'])->name('investissements.date.nom_actif');
    Route::get('/investissements/type/{type}/nom_actif/{nom_actif}', [PrivateController::class, 'investissementsTypeNom'])->name('investissements.type.nom_actif');
    Route::get('/investissements/date/{date}/type/{type}/nom_actif/{nom_actif}', [PrivateController::class, 'investissementsDateTypeNom'])->name('investissements.date.type.nom_actif');

    /* Édition des investissements */
    Route::post('/investissement/add', [PrivateController::class, 'addInvestissement'])->name('investissement.add');
    Route::post('/investissement/edit', [PrivateController::class, 'editInvestissement'])->name('investissement.edit');
    Route::get('/investissement/remove/{id}', [PrivateController::class, 'removeInvestissement'])->name('investissement.remove');

    /* Ajout de type d'investissement */
    Route::post('/investissement/type/add', [PrivateController::class, 'addTypeInvestissement'])->name('investissement.type.add');


    /*-------------*/
    /* Abonnements */
    /*-------------*/
    /* Affichage des abonnements */
    Route::get('/abonnements', [PrivateController::class, 'abonnements'])->name('abonnements');
    Route::get('/abonnements/date/{date}', [PrivateController::class, 'abonnementsDate'])->name('abonnements.date');
    Route::get('/abonnements/nom_actif/{nom_actif}', [PrivateController::class, 'abonnementsNom'])->name('abonnements.nom_actif');
    Route::get('/abonnements/abonnement_actif/{abonnement_actif}', [PrivateController::class, 'abonnementsActif'])->name('abonnements.abonnement_actif');
    Route::get('/abonnements/date/{date}/nom_actif/{nom_actif}', [PrivateController::class, 'abonnementsDateNom'])->name('abonnements.date.nom_actif');
    Route::get('/abonnements/date/{date}/abonnement_actif/{abonnement_actif}', [PrivateController::class, 'abonnementsDateActif'])->name('abonnements.date.abonnement_actif');
    Route::get('/abonnements/nom_actif/{nom_actif}/abonnement_actif/{abonnement_actif}', [PrivateController::class, 'abonnementsNomActif'])->name('abonnements.nom_actif.abonnement_actif');
    Route::get('/abonnements/date/{date}/nom_actif/{nom_actif}/abonnement_actif/{abonnement_actif}', [PrivateController::class, 'abonnementsDateNomActif'])->name('abonnements.date.nom_actif.abonnement_actif');

    /* Édition des abonnements */
    Route::post('/abonnement/add', [PrivateController::class, 'addAbonnement'])->name('abonnement.add');
    Route::post('/abonnement/edit', [PrivateController::class, 'editAbonnement'])->name('abonnement.edit');
    Route::get('/abonnement/remove/{id}', [PrivateController::class, 'removeAbonnement'])->name('abonnement.remove');


    /*-------------------------------------------------*/
    /* Historique des transactions lié aux abonnements */
    /*-------------------------------------------------*/
    /* Affichage des abonnements */
    Route::get('/abonnements_histories', [PrivateController::class, 'abonnementsHistories'])->name('abonnements_histories');
    Route::get('/abonnements_histories/date/{date}', [PrivateController::class, 'abonnementsHistoriesDate'])->name('abonnements_histories.date');
    Route::get('/abonnements_histories/nom_actif/{nom_actif}', [PrivateController::class, 'abonnementsHistoriesNom'])->name('abonnements_histories.nom_actif');
    Route::get('/abonnements_histories/date/{date}/nom_actif/{nom_actif}', [PrivateController::class, 'abonnementsHistoriesDateNom'])->name('abonnements_histories.date.nom_actif');

    /* Édition des abonnements */
    Route::post('/abonnements_history/add', [PrivateController::class, 'addAbonnementHistory'])->name('abonnement_history.add');
    Route::post('/abonnements_history/edit', [PrivateController::class, 'editAbonnementHistory'])->name('abonnement_history.edit');
    Route::get('/abonnements_history/remove/{id}', [PrivateController::class, 'removeAbonnementHistory'])->name('abonnement_history.remove');


    /*----------*/
    /* Emprunts */
    /*----------*/
    /* Affichage des emprunts */
    Route::get('/emprunts', [PrivateController::class, 'emprunts'])->name('emprunts');
    Route::get('/emprunts/banque/{banque}', [PrivateController::class, 'empruntsBanque'])->name('emprunts.banque');

    /* Édition des emprunts */
    Route::post('/emprunt/add', [PrivateController::class, 'addEmprunt'])->name('emprunt.add');
    Route::post('/emprunt/edit', [PrivateController::class, 'editEmprunt'])->name('emprunt.edit');
    Route::get('/emprunt/remove/{id}', [PrivateController::class, 'removeEmprunt'])->name('emprunt.remove');


    /*----------------------------------------------*/
    /* Historique des transactions lié aux emprunts */
    /*----------------------------------------------*/
    /* Affichage des emprunts */
    Route::get('/emprunts_histories', [PrivateController::class, 'empruntsHistories'])->name('emprunts_histories');
    Route::get('/emprunts_histories/nom_actif/{nom_actif}', [PrivateController::class, 'empruntsHistoriesNom'])->name('emprunts_histories.nom_actif');
    Route::get('/emprunts_histories/banque/{banque}', [PrivateController::class, 'empruntsHistoriesBanque'])->name('emprunts_histories.banque');
    Route::get('/emprunts_histories/nom_actif/{nom_actif}/banque/{banque}', [PrivateController::class, 'empruntsHistoriesNomBanque'])->name('emprunts_histories.nom_actif.banque');

    /* Édition des emprunts */
    Route::post('/emprunts_history/add', [PrivateController::class, 'addEmpruntHistory'])->name('emprunt_history.add');
    Route::post('/emprunts_history/edit', [PrivateController::class, 'editEmpruntHistory'])->name('emprunt_history.edit');
    Route::get('/emprunts_history/remove/{id}', [PrivateController::class, 'removeEmpruntHistory'])->name('emprunt_history.remove');


    /*----------*/
    /* Dépenses */
    /*----------*/
    /* Affichage des dépenses */
    Route::get('/depenses', [PrivateController::class, 'depenses'])->name('depenses');
    Route::get('/depenses/date/{date}', [PrivateController::class, 'depensesDate'])->name('depenses.date');
    Route::get('/depenses/nom_actif/{nom_actif}', [PrivateController::class, 'depensesNom'])->name('depenses.nom_actif');
    Route::get('/depenses/date/{date}/nom_actif/{nom_actif}', [PrivateController::class, 'depensesDateNom'])->name('depenses.date.nom_actif');

    /* Édition des dépenses */
    Route::post('/depense/add', [PrivateController::class, 'addDepense'])->name('depense.add');
    Route::post('/depense/edit', [PrivateController::class, 'editDepense'])->name('depense.edit');
    Route::get('/depense/remove/{id}', [PrivateController::class, 'removeDepense'])->name('depense.remove');


    /*-------*/
    /* Prêts */
    /*-------*/
    /* Affichage des prêts */
    Route::get('/prets', [PrivateController::class, 'prets'])->name('prets');
    Route::get('/prets/date/{date}', [PrivateController::class, 'pretsDate'])->name('prets.date');
    Route::get('/prets/nom_emprunteur/{nom_emprunteur}', [PrivateController::class, 'pretsNom'])->name('prets.nom_emprunteur');
    Route::get('/prets/date/{date}/nom_emprunteur/{nom_emprunteur}', [PrivateController::class, 'pretsDateNom'])->name('prets.date.nom_emprunteur');

    /* Édition des prêts */
    Route::post('/pret/add', [PrivateController::class, 'addPret'])->name('pret.add');
    Route::post('/pret/edit', [PrivateController::class, 'editPret'])->name('pret.edit');
    Route::get('/pret/remove/{id}', [PrivateController::class, 'removePret'])->name('pret.remove');
});
