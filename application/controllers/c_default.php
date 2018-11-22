<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Contrôleur par défaut de l'application
 * Si aucune spécification de contrôleur n'est précisée dans l'URL du navigateur
 * c'est le contrôleur par défaut qui sera invoqué. Son rôle est :
 * 		+ d'orienter vers le bon contrôleur selon la situation
 * 		+ de traiter le retour du formulaire de connexion
*/
class C_default extends CI_Controller {

	/**
	 * Fonctionnalité par défaut du contrôleur.
	 * Vérifie l'existence d'une connexion :
	 * Soit elle existe et on redirige vers le contrôleur de VISITEUR,
	 * soit elle n'existe pas et on envoie la vue de connexion
	*/
	public function index()
	{
		$this->load->model('authentif');

		$login = $this->input->post('login');
		$mdp = $this->input->post('mdp');

		$authVisiteur = $this->authentif->authentifierVisiteur($login, $mdp);
		$authComptable = $this->authentif->authentifierComptable($login, $mdp);

		if (!$this->authentif->estConnecte())
		{
			$data = array();
			$this->templates->load('t_connexion', 'v_connexion', $data);
		}
		else
		{
			$this->load->helper('url');

			if ($authVisiteur == true) {
				redirect('/c_visiteur/');
			}else if($authComptable == true){
				redirect('/c_comptable/');
			}
		}
	}

	/**
	 * Traite le retour du formulaire de connexion afin de connecter l'utilisateur
	 * s'il est reconnu
	*/

	public function connecter()
	{	// TODO : conrôler que l'obtention des données postées ne rend pas d'erreurs

		$this->load->model('authentif');

		$login = $this->input->post('login');
		$mdp = $this->input->post('mdp');

		$authVisiteur = $this->authentif->authentifierVisiteur($login, $mdp);
		$authComptable = $this->authentif->authentifierComptable($login, $mdp);

		if(empty($authComptable))
		{
			$data = array('erreur'=>'Login ou mot de passe incorrect');
			$this->templates->load('t_connexion', 'v_connexion', $data);
		}
		else
		{
			$this->authentif->connecter($authComptable['id'], $authComptable['nom'], $authComptable['prenom']);
			$this->index();
		}

		if(empty($authVisiteur))
		{
			$data = array('erreur'=>'Login ou mot de passe incorrect');
			$this->templates->load('t_connexion', 'v_connexion', $data);
		}
		else
		{
			$this->authentif->connecter($authVisiteur['id'], $authVisiteur['nom'], $authVisiteur['prenom']);
			$this->index();
		}

	}



}
