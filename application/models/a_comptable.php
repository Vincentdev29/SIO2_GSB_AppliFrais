<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class A_comptable extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

		// chargement du modèle d'accès aux données qui est utile à toutes les méthodes
		$this->load->model('dataAccess');
    }

	/**
	 * Accueil du comptable
	 * La fonction intègre un mécanisme de contrôle d'existence des
	 * fiches de frais sur le dernier mois.
	 * Si l'une d'elle est absente, elle est créée
	*/
	public function accueil()
	{	// TODO : Contrôler que toutes les valeurs de $unMois sont valides (chaine de caractère dans la BdD)

		// chargement du modèle contenant les fonctions génériques
		$this->load->model('functionsLib');

		// obtention de la liste des 6 derniers mois (y compris celui ci)
		        //$leMois = $this->functionsLib->getDernierMois();

		// obtention de l'id de l'utilisateur mémorisé en session
		$idComptable = $this->session->userdata('idUser');

		// contrôle de l'existence des 6 dernières fiches et création si nécessaire

		// envoie de la vue accueil du visiteur
		$this->templates->load('t_comptable', 'v_visAccueil');
	}

  /**
	 * Présente le détail de la fiche sélectionnée
	 *
	 * @param $idVisiteur : l'id du visiteur
	 * @param $mois : le mois de la fiche à modifier
	*/
	public function voirFiche($idVisiteur, $mois)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session

		$data['numAnnee'] = substr( $mois,0,4);
		$data['numMois'] = substr( $mois,4,2);
		$data['lesFraisHorsForfait'] = $this->dataAccess->getLesLignesHorsForfait($idVisiteur,$mois);
		$data['lesFraisForfait'] = $this->dataAccess->getLesLignesForfait($idVisiteur,$mois);

		$this->templates->load('t_visiteur', 'v_visVoirListeFrais', $data);
	}

	/**
	 * Signe une fiche de frais en changeant son état
	 *
	 * @param $idVisiteur : l'id du comptable
	 * @param $mois : Vois toutes les fiches à valider ce mois ci
   *
	*/
	public function signeFiche($idVisiteur, $mois)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session
		// TODO : intégrer une fonctionnalité d'impression PDF de la fiche

	    $this->dataAccess->signeFiche($idVisiteur, $mois);
	}

	/**
	 * Modifie les quantités associées aux frais forfaitisés dans une fiche donnée
	 *
	 * @param $idVisiteur : l'id du visiteur
	 * @param $mois : le mois de la fiche concernée
	 * @param $lesFrais : les quantités liées à chaque type de frais, sous la forme d'un tableau
	*/
	public function majForfait($idVisiteur, $mois, $lesFrais)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session
		// TODO : valider les données contenues dans $lesFrais ...

		$this->dataAccess->majLignesForfait($idVisiteur,$mois,$lesFrais);
		$this->dataAccess->recalculeMontantFiche($idVisiteur,$mois);
	}

	/**
	 * Ajoute une ligne de frais hors forfait dans une fiche donnée
	 *
	 * @param $idVisiteur : l'id du visiteur
	 * @param $mois : le mois de la fiche concernée
	 * @param $lesFrais : les quantités liées à chaque type de frais, sous la forme d'un tableau
	*/
	public function ajouteFrais($idVisiteur, $mois, $uneLigne)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session
		// TODO : valider la donnée contenues dans $uneLigne ...

		$dateFrais = $uneLigne['dateFrais'];
		$libelle = $uneLigne['libelle'];
		$montant = $uneLigne['montant'];

		$this->dataAccess->creeLigneHorsForfait($idVisiteur,$mois,$libelle,$dateFrais,$montant);
	}

	/**
	 * Supprime une ligne de frais hors forfait dans une fiche donnée
	 *
	 * @param $idVisiteur : l'id du visiteur
	 * @param $mois : le mois de la fiche concernée
	 * @param $idLigneFrais : l'id de la ligne à supprimer
	*/
	public function supprLigneFrais($idVisiteur, $mois, $idLigneFrais)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session et cohérents entre eux

	    $this->dataAccess->supprimerLigneHorsForfait($idLigneFrais);
	}
}
