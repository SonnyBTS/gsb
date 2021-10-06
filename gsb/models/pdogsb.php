<?php
/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb {
	private static $serveur = 'mysql:host=localhost';
	private static $bdd = 'dbname=PPE34';   		
	private static $user = 'root' ;    		
	private static $mdp = '' ;	
	private static $monPdo;
	private static $monPdoGsb = null;

	/**
	 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
	 * pour toutes les méthodes de la classe
	 */				
	private function __construct(){
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}

	public function _destruct(){
		PdoGsb::$monPdo = null;
	}

	/**
	 * Fonction statique qui crée l'unique instance de la classe
	 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
	 * @return l'unique objet de la classe PdoGsb
	 */
	public static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb == null)
			PdoGsb::$monPdoGsb = new PdoGsb();

		return PdoGsb::$monPdoGsb;  
	}
	
	/**
	 * Récupère les informations d'un utilisateur via un identifiant (id)
	 * @param $id 
	 * @return le nom et le prénom sous la forme d'un tableau associatif 
	 */
	public function getInfosVisiteur($id) {
		$stmt = PdoGsb::$monPdo->prepare("SELECT * FROM visiteur WHERE id = :idVisiteur");
		$stmt->bindParam(':idVisiteur', $id);
		$stmt->execute();
		$ligne = $stmt->fetch();
		return $ligne;
	}

	/**
	 * Récupère la liste des visiteurs
	 * @return visiteurs
	 */
	public function getVisiteurs() {
		return PdoGsb::$monPdo
			->query("SELECT * FROM visiteur")
			->fetchAll();
	}

	/**
	 * Récupérer les fichefrais
	 * @param $idVisiteur Correspond à l'identifiant du visiteur
	 * @param $mois Année + mois formation une chaîne de caractère
	 * @return boolean|PDOException Retoure une PDOException en cas d'erreur SQL
	 */
	public function getFicheFrais($id, $date) {
		try {
			$stmt = PdoGsb::$monPdo->prepare("SELECT * FROM fichefrais WHERE idVisiteur = :idVisiteur AND mois = :mois LIMIT 1");
			$stmt->bindParam(':idVisiteur', $id);
			$stmt->bindParam(':mois', $date);
			$stmt->execute();
			return $stmt->fetch();
		} catch (Exception $e) {
			return $e;
		}
	}

	/**
	 * Insérer des données das la table fichefrais
	 * 
	 * @param $idVisiteur Correspond à l'identifiant du visiteur
	 * @param $mois Année + mois formant une chaîne de caractère
	 * @param $nbJustificatif Nombre de justificatif
	 * @param $montantValide Montant valide 
	 * @param $dateModif Date d'insertion
	 * @param $idEtat IdEtat du visiteur
	 * @return boolean|PDOException Retoure une PDOException en cas d'erreur SQL
	 */
	public function insertFicheFrais($idVisiteur, $mois, $nbJustificatif, $montantValide, $dateModif, $idEtat) {
		try {
			$stmt = PdoGsb::$monPdo->prepare("INSERT INTO fichefrais (idVisiteur, mois, nbJustificatifs, montantValide, dateModif, idEtat) VALUES (:idVisiteur, :mois, :nbJustificatifs, :montantValide, :dateModif, :idEtat)");
			$stmt->bindParam(':idVisiteur', $idVisiteur);
			$stmt->bindParam(':mois', $mois);
			$stmt->bindParam(':nbJustificatifs', $nbJustificatif);
			$stmt->bindParam(':montantValide', $montantValide);
			$stmt->bindParam(':dateModif', $dateModif);
			$stmt->bindParam(':idEtat', $idEtat);
			$stmt->execute();
			return true;
		} catch(PDOException $e) {
			return $e;
		}
	}
	
	/**
	 * Insérer des données dans la table lignefraisforfait
	 * @param $visiteur_id Correspond à l'identifiant du visiteur
	 * @param $mois Année + mois formant une chaîne de caractère
	 * @param $quantite Quantitée
	 * @param $idFraisForfait Identifiant du frais forfait
	 * @return boolean|PDOException Retoure une PDOException en cas d'erreur SQL
	 */
	public function insertForfait($visiteur_id, $mois, $quantite, $idFraisForfait) {	
		try {
			$stmt = PdoGsb::$monPdo->prepare("INSERT INTO lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) VALUES (:idVisiteur, :mois, :idFraisForfait, :quantite)");
			$stmt->bindParam(':idVisiteur', $visiteur_id);
			$stmt->bindParam(':mois', $mois);
			$stmt->bindParam(':idFraisForfait', $idFraisForfait);
			$stmt->bindParam(':quantite', $quantite);
			$stmt->execute();
			return true;
		} catch(PDOException $e) {
			return $e;
		}
	}
}
