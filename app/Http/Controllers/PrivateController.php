<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salaire;
use App\Models\Epargne;
use App\Models\Investissement;


class PrivateController extends Controller
{
    /*=========*/
    /* Accueil */
    /*=========*/
    /**
     * Affiche la page d'accueil
     */
    public function accueil()
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');
        return view('private.accueil');
    }




    /*===========*/
    /* Dashboard */
    /*===========*/
    /*----------*/
    /* Salaires */
    /*----------*/
    /* Affiche des salaires */
    /**
     * Affiche la page des salaires
     */
    public function salaire()
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Récupération des salaires */
        $salaires = PrivateController::getSalaires('date_transaction');
        $nombreSalaires = $salaires->count();
        $montantSalaires = $salaires->sum('montant_transaction');
        
        /* Récupération des épargnes */
        $epargnes = PrivateController::getEpargnes('date_transaction');
        $montantEpargne = $epargnes->sum('montant_transaction');
        
        /* Récupération des investissements */
        $investissements = PrivateController::getInvestissements('date_transaction');
        $montantInvestissement = $investissements->sum('montant_transaction');

        return view('private.salaire', compact('salaires', 'montantSalaires', 'nombreSalaires', 'montantEpargne', 'montantInvestissement', 'epargnes', 'investissements'));
    }

    /**
     * Affiche les salaires d'un même mois
     */
    public function salairesDate(string $date)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Récupération des salaires du mois */
        $salaires = PrivateController::getSalairesDate($date, 'date_transaction');
        $montantSalaires = $salaires->sum('montant_transaction');
        $nombreSalaires = $salaires->count();

        /* Récupération des épargnes du mois */
        $epargnes = PrivateController::getEpargnesDate($date, 'date_transaction');
        $montantEpargne = $epargnes->sum('montant_transaction');
        
        /* Récupération des investissements du mois */
        $investissements = PrivateController::getInvestissementsDate($date, 'date_transaction');
        $montantInvestissement = $investissements->sum('montant_transaction');

        return view('private.salaire', compact('salaires', 'montantSalaires', 'nombreSalaires', 'montantEpargne', 'montantInvestissement', 'epargnes', 'investissements'));
    }

    /**
     * Affiche les salaires d'un même employeur
     */
    public function salairesEmployeur(string $employeur)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Récupération des salaires du mois */
        $salaires = PrivateController::getSalairesEmployeur($employeur, 'date_transaction');
        $montantSalaires = $salaires->sum('montant_transaction');
        $nombreSalaires = $salaires->count();

        /* Récupération des épargnes du mois */
        $epargnes = PrivateController::getEpargnes('date_transaction');
        $montantEpargne = $epargnes->sum('montant_transaction');
        
        /* Récupération des investissements du mois */
        $investissements = PrivateController::getInvestissements('date_transaction');
        $montantInvestissement = $investissements->sum('montant_transaction');

        return view('private.salaire', compact('salaires', 'montantSalaires', 'nombreSalaires', 'montantEpargne', 'montantInvestissement', 'epargnes', 'investissements'));
    }

    /**
     * Affiche les salaires d'un même mois et d'un même employeur
     */
    public function salairesDateEmployeur(string $date, string $employeur)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Récupération des salaires du mois */
        $salaires = PrivateController::getSalairesDateEmployeur($date, $employeur, 'date_transaction');
        $montantSalaires = $salaires->sum('montant_transaction');
        $nombreSalaires = $salaires->count();

        /* Récupération des épargnes du mois */
        $epargnes = PrivateController::getEpargnesDate($date, 'date_transaction');
        $montantEpargne = $epargnes->sum('montant_transaction');
        
        /* Récupération des investissements du mois */
        $investissements = PrivateController::getInvestissementsDate($date, 'date_transaction');
        $montantInvestissement = $investissements->sum('montant_transaction');

        return view('private.salaire', compact('salaires', 'montantSalaires', 'nombreSalaires', 'montantEpargne', 'montantInvestissement', 'epargnes', 'investissements'));
    }


    /* Édition des salaires */
    /**
     * Ajoute un salaire
     */
    public function addSalaire(Request $request)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Validation des données */
        $request->validate([
            'date_transaction' => 'required|date|before:tomorrow',
            'montant_transaction' => 'required|numeric|min:0',
            'employeur' => 'required|string|max:255'
        ], [
            'date_transaction.required' => 'La date est obligatoire.',
            'date_transaction.date' => 'La date doit être une date.',
            'date_transaction.before' => 'La date doit être antérieure à la date du jour.',
            'montant_transaction.required' => 'Le montant est obligatoire.',
            'montant_transaction.numeric' => 'Le montant doit être un nombre.',
            'montant_transaction.min' => 'Le montant doit être supérieur ou égal à 0.',
            'employeur.required' => 'L\'employeur est obligatoire.',
            'employeur.string' => 'L\'employeur doit être une chaîne de caractères.',
            'employeur.max' => 'L\'employeur ne doit pas dépasser 255 caractères.'
        ]);

        /* Message de confirmation */
        if (Salaire::where('date_transaction', $request->date_transaction)->first()) {
            $message = 'Attention, un salaire a déjà été ajouté pour cette date. 🤔';
        } else {
            $message = '';
        }

        /* Ajout du salaire */
        $salaire = new Salaire();
        $salaire->user_id = auth()->user()->id;
        $salaire->date_transaction = $request->date_transaction;
        $salaire->montant_transaction = $request->montant_transaction;
        $salaire->employeur = $request->employeur;
        
        if ($salaire->save()) {
            return back()->with('success', 'Le salaire a bien été ajouté 👍.')->with('message', $message);
        } else {
            return back()->with('error', 'Une erreur est survenue lors de l\'ajout du salaire ❌.');
        }
    }

    /**
     * Modifie un salaire
     */
    public function editSalaire(Request $request)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Validation des données */
        $request->validate([
            'id' => 'required|numeric|min:1|exists:finance_dashboard.salaires,id',
            'date_transaction' => 'required|date|before:tomorrow',
            'montant_transaction' => 'required|numeric|min:0',
            'employeur' => 'required|string|max:255'
        ], [
            'id.required' => 'L\'id est obligatoire.',
            'id.numeric' => 'L\'id doit être un nombre.',
            'id.min' => 'L\'id doit être supérieur ou égal à 1.',
            'id.exists' => 'L\'id n\'existe pas.',
            'date_transaction.required' => 'La date est obligatoire.',
            'date_transaction.date' => 'La date doit être une date.',
            'date_transaction.before' => 'La date doit être antérieure à la date du jour.',
            'montant_transaction.required' => 'Le montant est obligatoire.',
            'montant_transaction.numeric' => 'Le montant doit être un nombre.',
            'montant_transaction.min' => 'Le montant doit être supérieur ou égal à 0.',
            'employeur.required' => 'L\'employeur est obligatoire.',
            'employeur.string' => 'L\'employeur doit être une chaîne de caractères.',
            'employeur.max' => 'L\'employeur ne doit pas dépasser 255 caractères.'
        ]);

        /* Message de confirmation */
        if (Salaire::where('date_transaction', $request->date_transaction)->first()) {
            $message = 'Attention, un salaire similaire a déjà été ajouté pour cette date. 🤔';
        } else {
            $message = '';
        }

        /* Modification du salaire */
        $salaire = Salaire::find($request->id);
        if ($salaire->user_id != auth()->user()->id) { back()->with('error', 'Le salaire ne vous appartient pas ❌.'); }

        $salaire->date_transaction = $request->date_transaction;
        $salaire->montant_transaction = $request->montant_transaction;
        $salaire->employeur = $request->employeur;

        if ($salaire->save()) {
            return back()->with('success', 'Le salaire a bien été modifié 👍.')->with('message', $message);
        } else {
            return back()->with('error', 'Une erreur est sur venue lors de la modification du salaire ❌.');
        }
    }

    /**
     * Supprime un salaire
     */
    public function removeSalaire(string $id)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Validation des données */
        if ($id == null) { back()->with('error', 'l\'id est null ❌.'); }
        if (!is_numeric($id)) { back()->with('error', 'l\'id n\'est pas un nombre ❌.'); }
        if ($id <= 0) { back()->with('error', 'l\'id est inférieur ou égal à 0 ❌.'); }

        $salaire = Salaire::find($id);
        if (!$salaire) { back()->with('error', 'Le salaire n\'existe pas ❌.'); }
        if ($salaire->user_id != auth()->user()->id) { back()->with('error', 'Le salaire ne vous appartient pas ❌.'); }

        /* Suppression du salaire */
        if ($salaire->delete()) {
            return back()->with('success', 'Le salaire a bien été supprimé 👍.');
        } else {
            return back()->with('error', 'Une erreur est survenue lors de la suppression du salaire ❌.');
        }
    }



    /*----------*/
    /* Épargnes */
    /*----------*/
    /* Affiche des épargnes */
    /**
     * Affiche la page des épargnes
     */
    public function epargne()
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Récupération des épargnes */
        $epargnes = PrivateController::getEpargnes('date_transaction');
        $montantEpargnes = $epargnes->sum('montant_transaction');
        $nombreEpargnes = $epargnes->count();

        return view('private.epargne', compact('epargnes', 'montantEpargnes', 'nombreEpargnes'));
    }

    /**
     * Affiche les épargnes d'un même mois
     */
    public function epargnesDate(string $date)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Récupération des épargnes du mois */
        $epargnes = PrivateController::getEpargnesDate($date, 'date_transaction');
        $montantEpargnes = $epargnes->sum('montant_transaction');
        $nombreEpargnes = $epargnes->count();

        return view('private.epargne', compact('epargnes', 'montantEpargnes', 'nombreEpargnes'));
    }

    /**
     * Affiche les épargnes d'une même banque
     */
    public function epargnesBanque(string $banque)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Récupération des épargnes du mois */
        $epargnes = PrivateController::getEpargnesBanque($banque, 'date_transaction');
        $montantEpargnes = $epargnes->sum('montant_transaction');
        $nombreEpargnes = $epargnes->count();

        return view('private.epargne', compact('epargnes', 'montantEpargnes', 'nombreEpargnes'));
    }

    /**
     * Affiche les épargnes d'un même mois et d'une même banque
     */
    public function epargnesDateBanque(string $date, string $banque)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Récupération des épargnes du mois */
        $epargnes = PrivateController::getEpargnesDateBanque($date, $banque, 'date_transaction');
        $montantEpargnes = $epargnes->sum('montant_transaction');
        $nombreEpargnes = $epargnes->count();

        return view('private.epargne', compact('epargnes', 'montantEpargnes', 'nombreEpargnes'));
    }


    /* Édition des épargnes */
    /**
     * Ajoute une épargne
     */
    public function addEpargne(Request $request)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Validation des données */
        $request->validate([
            'date_transaction' => 'required|date|before:tomorrow',
            'montant_transaction' => 'required|numeric|min:0',
            'banque' => 'required|string|max:255',
            'compte' => 'required|string|max:255'
        ], [
            'date_transaction.required' => 'La date est obligatoire.',
            'date_transaction.date' => 'La date doit être une date.',
            'date_transaction.before' => 'La date doit être antérieure à la date du jour.',
            'montant_transaction.required' => 'Le montant est obligatoire.',
            'montant_transaction.numeric' => 'Le montant doit être un nombre.',
            'montant_transaction.min' => 'Le montant doit être supérieur ou égal à 0.',
            'banque.required' => 'La banque est obligatoire.',
            'banque.string' => 'La banque doit être une chaîne de caractères.',
            'banque.max' => 'La banque ne doit pas dépasser 255 caractères.',
            'compte.required' => 'Le compte est obligatoire.',
            'compte.string' => 'Le compte doit être une chaîne de caractères.',
            'compte.max' => 'Le compte ne doit pas dépasser 255 caractères.'
        ]);

        /* Message de confirmation */
        if (Epargne::where('date_transaction', $request->date_transaction)->where('montant_transaction', $request->montant_transaction)->where('banque', $request->banque)->where('compte', $request->compte)->first()) {
            $message = 'Attention, une épargne similaire a déjà été ajoutée pour cette date. 🤔';
        } else {
            $message = '';
        }

        /* Ajout de l'épargne */
        $epargne = new Epargne();
        $epargne->user_id = auth()->user()->id;
        $epargne->date_transaction = $request->date_transaction;
        $epargne->montant_transaction = $request->montant_transaction;
        $epargne->banque = $request->banque;
        $epargne->compte = $request->compte;
        
        if ($epargne->save()) {
            return back()->with('success', 'L\'épargne a bien été ajoutée 👍.')->with('message', $message);
        } else {
            return back()->with('error', 'Une erreur est survenue lors de l\'ajout de l\'épargne ❌.');
        }
    }

    /**
     * Modifie une épargne
     */
    public function editEpargne(Request $request)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Validation des données */
        $request->validate([
            'id' => 'required|numeric|min:1|exists:finance_dashboard.epargnes,id',
            'date_transaction' => 'required|date|before:tomorrow',
            'montant_transaction' => 'required|numeric|min:0',
            'banque' => 'required|string|max:255',
            'compte' => 'required|string|max:255'
        ], [
            'id.required' => 'L\'id est obligatoire.',
            'id.numeric' => 'L\'id doit être un nombre.',
            'id.min' => 'L\'id doit être supérieur ou égal à 1.',
            'id.exists' => 'L\'id n\'existe pas.',
            'date_transaction.required' => 'La date est obligatoire.',
            'date_transaction.date' => 'La date doit être une date.',
            'date_transaction.before' => 'La date doit être antérieure à la date du jour.',
            'montant_transaction.required' => 'Le montant est obligatoire.',
            'montant_transaction.numeric' => 'Le montant doit être un nombre.',
            'montant_transaction.min' => 'Le montant doit être supérieur ou égal à 0.',
            'banque.required' => 'La banque est obligatoire.',
            'banque.string' => 'La banque doit être une chaîne de caractères.',
            'banque.max' => 'La banque ne doit pas dépasser 255 caractères.',
            'compte.required' => 'Le compte est obligatoire.',
            'compte.string' => 'Le compte doit être une chaîne de caractères.',
            'compte.max' => 'Le compte ne doit pas dépasser 255 caractères.'
        ]);

        /* Message de confirmation */
        if (Epargne::where('date_transaction', $request->date_transaction)->where('montant_transaction', $request->montant_transaction)->where('banque', $request->banque)->where('compte', $request->compte)->first()) {
            $message = 'Attention, une épargne similaire a déjà été ajoutée pour cette date. 🤔';
        } else {
            $message = '';
        }

        /* Modification de l'épargne */
        $epargne = Epargne::find($request->id);
        if ($epargne->user_id != auth()->user()->id) { back()->with('error', 'L\'épargne ne vous appartient pas ❌.'); }

        $epargne->date_transaction = $request->date_transaction;
        $epargne->montant_transaction = $request->montant_transaction;
        $epargne->banque = $request->banque;
        $epargne->compte = $request->compte;

        if ($epargne->save()) {
            return back()->with('success', 'L\'épargne a bien été modifiée 👍.')->with('message', $message);
        } else {
            return back()->with('error', 'Une erreur est survenue lors de la modification de l\'épargne ❌.');
        }
    }

    /**
     * Supprime une épargne
     */
    public function removeEpargne(string $id)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Validation des données */
        if ($id == null) { back()->with('error', 'l\'id est null ❌.'); }
        if (!is_numeric($id)) { back()->with('error', 'l\'id n\'est pas un nombre ❌.'); }
        if ($id <= 0) { back()->with('error', 'l\'id est inférieur ou égal à 0 ❌.'); }

        $epargne = Epargne::find($id);
        if (!$epargne) { back()->with('error', 'L\'épargne n\'existe pas ❌.'); }
        if ($epargne->user_id != auth()->user()->id) { back()->with('error', 'L\'épargne ne vous appartient pas ❌.'); }

        /* Suppression de l'épargne */
        if ($epargne->delete()) {
            return back()->with('success', 'L\'épargne a bien été supprimée 👍.');
        } else {
            return back()->with('error', 'Une erreur est survenue lors de la suppression de l\'épargne ❌.');
        }
    }



    /*-----------------*/
    /* Investissements */
    /*-----------------*/
    /* Affiche des investissements */
    /**
     * Affiche tous les investissements
     */
    public function investissements()
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Récupération des investissements */
        $type_investissement  = 'investissements';
        $investissements      = PrivateController::getInvestissements('date_transaction');

        return view('private.investissement', compact('investissements', 'type_investissement'));
    }

    public function investissementsDate(string $date)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        $type_investissement  = 'investissements';
        $investissements      = PrivateController::getInvestissementsDate($date, 'date_transaction');

        return view('private.investissement', compact('investissements', 'type_investissement'));
    }

    /**
     * Affiche tous les investissements d'un même type
     */
    public function investissementsType(string $type)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        if ($type == 'investissements')
        {
            $type_investissement  = 'investissements';
            $investissements      = PrivateController::getInvestissements('date_transaction');
        }
        else
        {
            $type_investissement  = $type;
            $investissements      = PrivateController::getInvestissementsType($type_investissement, 'date_transaction');
        }

        return view('private.investissement', compact('investissements', 'type_investissement'));
    }

    /**
     * Affiche tous les investissements d'un même nom d'actif
     */
    public function investissementsNom(string $nom_actif)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        $type_investissement  = 'investissements';
        $investissements      = PrivateController::getInvestissementsNom($nom_actif, 'date_transaction');

        return view('private.investissement', compact('investissements', 'type_investissement'));
    }

    /**
     * Affiche les investissements d'une même date et d'un même type
     */
    public function investissementsDateType(string $date, string $type)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        if ($type == 'investissements')
        {
            $type_investissement  = 'investissements';
            $investissements      = PrivateController::getInvestissementsDate($date, 'date_transaction');
        }
        else
        {
            $type_investissement  = $type;
            $investissements      = PrivateController::getInvestissementsDateType($date, $type_investissement, 'date_transaction');
        }

        return view('private.investissement', compact('investissements', 'type_investissement'));
    }

    /**
     * Affiche les investissements d'une même date et d'un même nom d'actif
     */
    public function investissementsDateNom(string $date, string $nom_actif)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        $type_investissement  = 'investissements';
        $investissements      = PrivateController::getInvestissementsDateNom($date, $nom_actif, 'date_transaction');

        return view('private.investissement', compact('investissements', 'type_investissement'));
    }

    /**
     * Affiche les investissements d'un même type et d'un même nom d'actif
     */
    public function investissementsTypeNom(string $type, string $nom_actif)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        if ($type == 'investissements')
        {
            $type_investissement  = 'investissements';
            $investissements      = PrivateController::getInvestissementsNom($nom_actif, 'date_transaction');
        }
        else
        {
            $type_investissement  = $type;
            $investissements      = PrivateController::getInvestissementsTypeNom($type_investissement, $nom_actif, 'date_transaction');
        }

        return view('private.investissement', compact('investissements', 'type_investissement'));
    }

    /**
     * Affiche les détails d'un investissement d'un même mois et d'un même type
     */
    public function investissementDateTypeNom(string $date, string $type, string $nom_actif)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        $investissements = PrivateController::getInvestissementsDateTypeNom($date, $type, $nom_actif, 'date_transaction');
        return view('private.investissement', compact('investissements', 'type'));
    }


    /* Édition des investissements */
    /**
     * Ajoute un investissement
     */
    public function addInvestissement(Request $request)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Validation des données */
        $request->validate([
            'date_transaction' => 'required|date|before:tomorrow',
            'type_investissement' => 'required|string|max:255',
            'nom_actif' => 'required|string|max:255',
            'montant_transaction' => 'required|numeric|min:0',
            'frais_transaction' => 'required|numeric|min:0',
        ], [
            'date_transaction.required' => 'La date est obligatoire.',
            'date_transaction.date' => 'La date doit être une date.',
            'date_transaction.before' => 'La date doit être antérieure à la date du jour.',
            'type_investissement.required' => 'Le type d\'investissement est obligatoire.',
            'type_investissement.string' => 'Le type d\'investissement doit être une chaîne de caractères.',
            'type_investissement.max' => 'Le type d\'investissement ne doit pas dépasser 255 caractères.',
            'nom_actif.required' => 'Le nom de l\'actif est obligatoire.',
            'nom_actif.string' => 'Le nom de l\'actif doit être une chaîne de caractères.',
            'nom_actif.max' => 'Le nom de l\'actif ne doit pas dépasser 255 caractères.',
            'montant_transaction.required' => 'Le montant est obligatoire.',
            'montant_transaction.numeric' => 'Le montant doit être un nombre.',
            'montant_transaction.min' => 'Le montant doit être supérieur ou égal à 0.',
            'frais_transaction.required' => 'Les frais sont obligatoires.',
            'frais_transaction.numeric' => 'Les frais doivent être un nombre.',
            'frais_transaction.min' => 'Les frais doivent être supérieurs ou égaux à 0.'
        ]);

        /* Message de confirmation */
        if (Investissement::where('date_transaction', $request->date_transaction)->where('type_investissement', $request->type_investissement)->where('nom_actif', $request->nom_actif)->where('montant_transaction', $request->montant_transaction)->where('frais_transaction', $request->frais_transaction)->first()) {
            $message = 'Attention, un investissement en ' . $request->type_investissement . ' similaire a déjà été ajouté pour cette date. 🤔';
        } else {
            $message = '';
        }

        /* Vérification du type d'investissement */
        $type_investissement = $request->type_investissement;
        if ($type_investissement == 'investissements')
        {
            /* Récupération d'un investissement avec le même nom d'actif */
            $investissement = Investissement::where('nom_actif', $request->nom_actif)->first();
            $type_investissement = $investissement->type_investissement ?? 'crypto';
        }
        

        /* Ajout de l'investissement */
        $investissement = new Investissement();
        $investissement->user_id             = auth()->user()->id;
        $investissement->date_transaction    = $request->date_transaction;
        $investissement->type_investissement = $type_investissement;
        $investissement->nom_actif           = $request->nom_actif;
        $investissement->montant_transaction = $request->montant_transaction;
        $investissement->frais_transaction   = $request->frais_transaction;

        if ($investissement->save()) {
            return back()->with('success', 'L\'investissement a bien été ajouté 👍.')->with('message', $message);
        } else {
            return back()->with('error', 'Une erreur est survenue lors de l\'ajout de l\'investissement ❌.');
        }
    }

    /**
     * Modifie un investissement
     */
    public function editInvestissement(Request $request)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Validation des données */
        $request->validate([
            'id' => 'required|numeric|min:1|exists:finance_dashboard.investissements,id',
            'date_transaction' => 'required|date|before:tomorrow',
            'type_investissement' => 'required|string|max:255',
            'nom_actif' => 'required|string|max:255',
            'montant_transaction' => 'required|numeric|min:0',
            'frais_transaction' => 'required|numeric|min:0',
        ], [
            'id.required' => 'L\'id est obligatoire.',
            'id.numeric' => 'L\'id doit être un nombre.',
            'id.min' => 'L\'id doit être supérieur ou égal à 1.',
            'id.exists' => 'L\'id n\'existe pas.',
            'date_transaction.required' => 'La date est obligatoire.',
            'date_transaction.date' => 'La date doit être une date.',
            'date_transaction.before' => 'La date doit être antérieure à la date du jour.',
            'type_investissement.required' => 'Le type d\'investissement est obligatoire.',
            'type_investissement.string' => 'Le type d\'investissement doit être une chaîne de caractères.',
            'type_investissement.max' => 'Le type d\'investissement ne doit pas dépasser 255 caractères.',
            'nom_actif.required' => 'Le nom de l\'actif est obligatoire.',
            'nom_actif.string' => 'Le nom de l\'actif doit être une chaîne de caractères.',
            'nom_actif.max' => 'Le nom de l\'actif ne doit pas dépasser 255 caractères.',
            'montant_transaction.required' => 'Le montant est obligatoire.',
            'montant_transaction.numeric' => 'Le montant doit être un nombre.',
            'montant_transaction.min' => 'Le montant doit être supérieur ou égal à 0.',
            'frais_transaction.required' => 'Les frais sont obligatoires.',
            'frais_transaction.numeric' => 'Les frais doivent être un nombre.',
            'frais_transaction.min' => 'Les frais doivent être supérieurs ou égaux à 0.'
        ]);

        /* Message de confirmation */
        if (Investissement::where('date_transaction', $request->date_transaction)->where('type_investissement', $request->type_investissement)->where('nom_actif', $request->nom_actif)->where('montant_transaction', $request->montant_transaction)->where('frais_transaction', $request->frais_transaction)->first()) {
            $message = 'Attention, un investissement en ' . $request->type_investissement . ' similaire a déjà été ajouté pour cette date. 🤔';
        } else {
            $message = '';
        }

        /* Modification de l'investissement */
        $investissement = Investissement::find($request->id);
        if ($investissement->user_id != auth()->user()->id) { back()->with('error', 'L\'investissement ne vous appartient pas ❌.'); }

        $investissement->date_transaction    = $request->date_transaction;
        $investissement->type_investissement = $request->type_investissement;
        $investissement->nom_actif           = $request->nom_actif;
        $investissement->montant_transaction = $request->montant_transaction;
        $investissement->frais_transaction   = $request->frais_transaction;

        if ($investissement->save()) {
            return back()->with('success', 'L\'investissement a bien été modifié 👍.')->with('message', $message);
        } else {
            return back()->with('error', 'Une erreur est survenue lors de la modification de l\'investissement ❌.');
        }
    }

    /**
     * Supprime un investissement
     */
    public function removeInvestissement(string $id)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Validation des données */
        if ($id == null) { back()->with('error', 'l\'id est null ❌.'); }
        if (!is_numeric($id)) { back()->with('error', 'l\'id n\'est pas un nombre ❌.'); }
        if ($id <= 0) { back()->with('error', 'l\'id est inférieur ou égal à 0 ❌.'); }

        $investissement = Investissement::find($id);
        if (!$investissement) { back()->with('error', 'L\'investissement n\'existe pas ❌.'); }
        if ($investissement->user_id != auth()->user()->id) { back()->with('error', 'L\'investissement ne vous appartient pas ❌.'); }

        /* Suppression de l'investissement */
        if ($investissement->delete()) {
            return back()->with('success', 'L\'investissement a bien été supprimé 👍.');
        } else {
            return back()->with('error', 'Une erreur est survenue lors de la suppression de l\'investissement ❌.');
        }
    }

    /**
     * Affiche les détails d'un investissement
     */
    public function detailsInvestissement(string $type, string $nom_actif)
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Récupération des investissements */
        $type_investissement = $type;
        $investissements = PrivateController::getInvestissementsTypeNom($type_investissement, $nom_actif, 'date_transaction');
        $montantInvesties     = $investissements->sum('montant_transaction');
        $montantFrais         = $investissements->sum('frais_transaction');
        $nombreInvestissement = $investissements->count();

        return view('private.investissement', compact('investissements', 'montantInvesties', 'nombreInvestissement', 'montantFrais', 'type_investissement'));
    }



    /*-----------------*/
    /* Crypto-monnaies */
    /*-----------------*/
    /* Affiche des investissements en crypto-monnaies */
    /**
     * Affiche la page des investissements en crypto-monnaies
     */
    public function crypto()
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Récupération des investissements en crypto-monnaies */
        $type_investissement  = 'crypto';
        $investissements      = PrivateController::getInvestissementsType($type_investissement, 'date_transaction');
        $montantInvesties     = $investissements->sum('montant_transaction');
        $montantFrais         = $investissements->sum('frais_transaction');
        $nombreInvestissement = $investissements->count();

        return view('private.investissement', compact('investissements', 'montantInvesties', 'nombreInvestissement', 'montantFrais', 'type_investissement'));
    }



    /*--------*/
    /* Bourse */
    /*--------*/
    /* Affiche des investissements en bourse */
    /**
     * Affiche la page des investissements en bourse
     */
    public function bourse()
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Récupération des investissements en bourse */
        $type_investissement  = 'bourse';
        $investissements      = PrivateController::getInvestissementsType($type_investissement, 'date_transaction');
        $montantInvesties     = $investissements->sum('montant_transaction');
        $montantFrais         = $investissements->sum('frais_transaction');
        $nombreInvestissement = $investissements->count();

        return view('private.investissement', compact('investissements', 'montantInvesties', 'nombreInvestissement', 'montantFrais', 'type_investissement'));
    }



    /*------------*/
    /* Immobilier */
    /*------------*/
    /* Affiche des investissements en immobilier */
    /**
     * Affiche la page des investissements en immobilier
     */
    public function immobilier()
    {
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');

        /* Récupération des investissements en immobilier */
        $type_investissement  = 'immobilier';
        $investissements      = PrivateController::getInvestissementsType($type_investissement, 'date_transaction');
        $montantInvesties     = $investissements->sum('montant_transaction');
        $montantFrais         = $investissements->sum('frais_transaction');
        $nombreInvestissement = $investissements->count();

        return view('private.investissement', compact('investissements', 'montantInvesties', 'nombreInvestissement', 'montantFrais', 'type_investissement'));
    }




    /*======================*/
    /* Fonction Utilitaires */
    /*======================*/
    /*------*/
    /* Date */
    /*------*/
    /**
     * Récupère la première date d'un mois
     */
    public function getFirstDay(string $date) { return date('Y-m-01', strtotime($date)); }

    /**
     * Récupère la dernière date d'un mois
     */
    public function getLastDay(string $date) { return date('Y-m-t', strtotime($date)); }


    /*---------*/
    /* Salaire */
    /*---------*/
    /**
     * Récupère tous les salaires
     */
    public function getSalaires(string $sort)
    {
        return Salaire::all()->where('user_id', auth()->user()->id)->sortByDesc($sort);
    }

    /**
     * Récupère les salaires d'une même date
     */
    public function getSalairesDate(string $date, string $sort)
    {
        return Salaire::all()->where('user_id', auth()->user()->id)
                             ->where('date_transaction', '>=', PrivateController::getFirstDay($date))
                             ->where('date_transaction', '<=', PrivateController::getLastDay($date))
                             ->sortByDesc($sort);
    }

    /**
     * Récupère les salaires d'un même employeur
     */
    public function getSalairesEmployeur(string $employeur, string $sort)
    {
        return Salaire::all()->where('user_id', auth()->user()->id)
                             ->where('employeur', $employeur)
                             ->sortByDesc($sort);
    }

    /**
     * Récupère les salaires d'une même date et d'un même employeur
     */
    public function getSalairesDateEmployeur(string $date, string $employeur, string $sort)
    {
        return Salaire::all()->where('user_id', auth()->user()->id)
                             ->where('date_transaction', '>=', PrivateController::getFirstDay($date))
                             ->where('date_transaction', '<=', PrivateController::getLastDay($date))
                             ->where('employeur', $employeur)
                             ->sortByDesc($sort);
    }


    /*---------*/
    /* Épargne */
    /*---------*/
    /**
     * Récupère toutes les épargnes
     */
    public function getEpargnes(string $sort)
    {
        return Epargne::all()->where('user_id', auth()->user()->id)->sortByDesc($sort);
    }

    /**
     * Récupère les épargnes d'une même date
     */
    public function getEpargnesDate(string $date, string $sort)
    {
        return Epargne::all()->where('user_id', auth()->user()->id)
                             ->where('date_transaction', '>=', PrivateController::getFirstDay($date))
                             ->where('date_transaction', '<=', PrivateController::getLastDay($date))
                             ->sortByDesc($sort);
    }

    /**
     * Récupère les épargnes d'une même banque
     */
    public function getEpargnesBanque(string $banque, string $sort)
    {
        return Epargne::all()->where('user_id', auth()->user()->id)
                             ->where('banque', $banque)
                             ->sortByDesc($sort);
    }

    /**
     * Récupère les épargnes d'une même date et d'une même banque
     */
    public function getEpargnesDateBanque(string $date, string $banque, string $sort)
    {
        return Epargne::all()->where('user_id', auth()->user()->id)
                             ->where('date_transaction', '>=', PrivateController::getFirstDay($date))
                             ->where('date_transaction', '<=', PrivateController::getLastDay($date))
                             ->where('banque', $banque)
                             ->sortByDesc($sort);
    }


    /*----------------*/
    /* Investissement */
    /*----------------*/
    /**
     * Récupère tous les investissements
     */
    public function getInvestissements(string $sort)
    {
        return Investissement::all()->where('user_id', auth()->user()->id)->sortByDesc($sort);
    }

    /**
     * Récupère tous les investissements d'une même date
     */
    public function getInvestissementsDate(string $date, string $sort)
    {
        return Investissement::all()->where('user_id', auth()->user()->id)
                                    ->where('date_transaction', '>=', PrivateController::getFirstDay($date))
                                    ->where('date_transaction', '<=', PrivateController::getLastDay($date))
                                    ->sortByDesc($sort);
    }

    /**
     * Récupère tous les investissements d'un même type
     */
    public function getInvestissementsType(string $type, string $sort)
    {
        return Investissement::all()->where('user_id', auth()->user()->id)
                                    ->where('type_investissement', $type)
                                    ->sortByDesc($sort);
    }

    /**
     * Récupère tous les investissements d'un même nom d'actif
     */
    public function getInvestissementsNom(string $nom_actif, string $sort)
    {
        return Investissement::all()->where('user_id', auth()->user()->id)
                                    ->where('nom_actif', $nom_actif)
                                    ->sortByDesc($sort);
    }

    /**
     * Récupère les investissements d'une même date et d'un même type
     */
    public function getInvestissementsDateType(string $date, string $type, string $sort)
    {
        return Investissement::all()->where('user_id', auth()->user()->id)
                                    ->where('date_transaction', '>=', PrivateController::getFirstDay($date))
                                    ->where('date_transaction', '<=', PrivateController::getLastDay($date))
                                    ->where('type_investissement', $type)
                                    ->sortByDesc($sort);
    }

    /**
     * Récupère les investissements d'une même date et d'un même nom d'actif
     */
    public function getInvestissementsDateNom(string $date, string $nom_actif, string $sort)
    {
        return Investissement::all()->where('user_id', auth()->user()->id)
                                    ->where('date_transaction', '>=', PrivateController::getFirstDay($date))
                                    ->where('date_transaction', '<=', PrivateController::getLastDay($date))
                                    ->where('nom_actif', $nom_actif)
                                    ->sortByDesc($sort);
    }

    /**
     * Récupère les investissements d'un même type et d'un même nom d'actif
     */
    public function getInvestissementsTypeNom(string $type, string $nom_actif, string $sort)
    {
        return Investissement::all()->where('user_id', auth()->user()->id)
                                    ->where('type_investissement', $type)
                                    ->where('nom_actif', $nom_actif)
                                    ->sortByDesc($sort);
    }

    /**
     * Récupère les investissements d'une même date, d'un même type et d'un même nom d'actif
     */
    public function getInvestissementsDateTypeNom(string $date, string $type, string $nom_actif, string $sort)
    {
        return Investissement::all()->where('user_id', auth()->user()->id)
                                    ->where('date_transaction', '>=', PrivateController::getFirstDay($date))
                                    ->where('date_transaction', '<=', PrivateController::getLastDay($date))
                                    ->where('type_investissement', $type)
                                    ->where('nom_actif', $nom_actif)
                                    ->sortByDesc($sort);
    }
}