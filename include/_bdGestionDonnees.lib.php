<?php
/**
 * Regroupe les fonctions d'accès aux données.
 * @package default
 * @author Arthur Martin
 * @todo Fonctions retournant plusieurs lignes sont à réécrire.
 */

/**
 * Se connecte au serveur de données MySql.
 * Se connecte au serveur de données MySql à partir de valeurs
 * prédéfinies de connexion (hôte, compte utilisateur et mot de passe).
 * Retourne l'identifiant de connexion si succès obtenu, le booléen false
 * si problème de connexion.
 * @return resource identifiant de connexion
 */
 function connecterServeurBD() {
     $hote = "localhost";
     $login = "root";
     $mdp = '';
     return new  mysqli($hote, $login, $mdp,"appli_frais");
 }
 /**
  * Sélectionne (rend active) la base de données.
  * Sélectionne (rend active) la BD prédéfinie gsb_frais sur la connexion
  * identifiée par $idCnx. Retourne true si succès, false sinon.
  * @param resource $idCnx identifiant de connexion
  * @return boolean succès ou échec de sélection BD
  */
 function activerBD($idCnx) {
     $bd = "appli_frais";
     $query = "SET CHARACTER SET utf8";
     // Modification du jeu de caractères de la connexion
     $res = $idCnx-> query($query );
     $ok = $idCnx-> select_db($bd);
     return $ok;
 }


/**
 * Ferme la connexion au serveur de données.
 * Ferme la connexion au serveur de données identifiée par l'identifiant de
 * connexion $idCnx.
 * @param resource $idCnx identifiant de connexion
 * @return void
 */
function deconnecterServeurBD($idCnx) {

    $idCnx->close();
}

/**
 * Echappe les caractères spéciaux d'une chaîne.
 * Envoie la chaîne $str échappée, càd avec les caractères considérés spéciaux
 * par MySql (tq la quote simple) précédés d'un \, ce qui annule leur effet spécial
 * @param string $str chaîne à échapper
 * @return string chaîne échappée
 */

function filtrerChainePourBD($str) {
    if ( ! get_magic_quotes_gpc() ) {
        // si la directive de configuration magic_quotes_gpc est activée dans php.ini,
        // toute chaîne reçue par get, post ou cookie est déjà échappée
        // par conséquent, il ne faut pas échapper la chaîne une seconde fois
        $str = connecterServeurBD()->real_escape_string($str);

    }
    return $str;
}

/**
 * Fournit les informations sur un visiteur demandé.
 * Retourne les informations du visiteur d'id $unId sous la forme d'un tableau
 * associatif dont les clés sont les noms des colonnes(id, nom, prenom).
 * @param resource $idCnx identifiant de connexion
 * @param string $unId id de l'utilisateur
 * @return array  tableau associatif du visiteur
 */
function obtenirDetailVisiteur($idCnx, $unId) {
    $id = filtrerChainePourBD($unId);
    $requete = "select id, nom, prenom from visiteur where id='" . $unId . "'";

    $idJeuRes = $idCnx->query($requete);
    $ligne = false;
    if ( $idJeuRes ) {
        $ligne = $idJeuRes->fetch_assoc();
        $idJeuRes->free_result();
    }
    return $ligne ;

}

/**
 * Fournit les informations d'une fiche de frais.
 * Retourne les informations de la fiche de frais du mois de $unMois (MMAAAA)
 * sous la forme d'un tableau associatif dont les clés sont les noms des colonnes
 * (nbJustitificatifs, idEtat, libelleEtat, dateModif, montantValide).
 * @param resource $idCnx identifiant de connexion
 * @param string $unMois mois demandé (MMAAAA)
 * @param string $unIdVisiteur id visiteur
 * @return array tableau associatif de la fiche de frais
 */
function obtenirDetailFicheFrais($idCnx, $unMois, $unIdVisiteur) {
    $unMois = filtrerChainePourBD($unMois);
    $ligne = false;
    $requete="select IFNULL(nbJustificatifs,0) as nbJustificatifs, Etat.id as idEtat, libelle as libelleEtat, dateModif, montantValide
    from fichefrais inner join Etat on idEtat = Etat.id
    where idVisiteur='" . $unIdVisiteur . "' and mois='" . $unMois . "'";
    $idJeuRes = $idCnx->query($requete);
    if ( $idJeuRes ) {
        $ligne = mysqli_fetch_assoc($idJeuRes);

    }
    $idJeuRes->free_result();


    return $ligne ;
}

/**
 * Vérifie si une fiche de frais existe ou non.
 * Retourne true si la fiche de frais du mois de $unMois (MMAAAA) du visiteur
 * $idVisiteur existe, false sinon.
 * @param resource $idCnx identifiant de connexion
 * @param string $unMois mois demandé (MMAAAA)
 * @param string $unIdVisiteur id visiteur
 * @return booléen existence ou non de la fiche de frais
 */
function existeFicheFrais($idCnx, $unMois, $unIdVisiteur) {
    $unMois = filtrerChainePourBD($unMois);
    $requete = "select idVisiteur from fichefrais where idVisiteur='" . $unIdVisiteur .
              "' and mois='" . $unMois . "'";
    $idJeuRes = $idCnx->query( $requete);
    $ligne = false ;
    if ( $idJeuRes ) {
        $ligne = $idJeuRes->fetch_assoc();
        $idJeuRes->free_result();
    }

    // si $ligne est un tableau, la fiche de frais existe, sinon elle n'exsite pas
    return is_array($ligne) ;
}

/**
 * Fournit le mois de la dernière fiche de frais d'un visiteur.
 * Retourne le mois de la dernière fiche de frais du visiteur d'id $unIdVisiteur.
 * @param resource $idCnx identifiant de connexion
 * @param string $unIdVisiteur id visiteur
 * @return string dernier mois sous la forme AAAAMM
 */
function obtenirDernierMoisSaisi($idCnx, $unIdVisiteur) {
    $requete = "select max(mois) as dernierMois from fichefrais where idVisiteur='" .
            $unIdVisiteur . "'";
    $idJeuRes = $idCnx->query($requete);
    $dernierMois = false ;
    if ( $idJeuRes ) {
        $ligne = $idJeuRes->fetch_assoc();
        $dernierMois = $ligne["dernierMois"];
        $idJeuRes->free_result();
    }
    return $dernierMois;
}

/**
 * Ajoute une nouvelle fiche de frais et les éléments forfaitisés associés,
 * Ajoute la fiche de frais du mois de $unMois (MMAAAA) du visiteur
 * $idVisiteur, avec les éléments forfaitisés associés dont la quantité initiale
 * est affectée à 0. Clôt éventuellement la fiche de frais précédente du visiteur.
 * @param resource $idCnx identifiant de connexion
 * @param string $unMois mois demandé (MMAAAA)
 * @param string $unIdVisiteur id visiteur
 * @return void
 */
function ajouterFicheFrais($idCnx, $unMois, $unIdVisiteur) {
    $unMois = filtrerChainePourBD($unMois);
    // modification de la dernière fiche de frais du visiteur
    /*$dernierMois = obtenirDernierMoisSaisi($idCnx, $unIdVisiteur);
    $laDerniereFiche = obtenirDetailFicheFrais($idCnx, $dernierMois, $unIdVisiteur);
    die();
    if ( is_array($laDerniereFiche) && $laDerniereFiche['idEtat']=='CR'){
        modifierEtatFicheFrais($idCnx, $dernierMois, $unIdVisiteur, 'CL');
    }*/


    // ajout de la fiche de frais à l'état Créé
    $requete = "insert into fichefrais (idVisiteur, mois, nbJustificatifs, montantValide, idEtat, dateModif) values ('"
              . $unIdVisiteur
              . "','" . $unMois . "',0,NULL, 'CR', '" . date("Y-m-d") . "')";
    $idCnx->query($requete);

    // ajout des éléments forfaitisés
    $requete_fraisforfait = "select idFrais from FraisForfait";
    $idJeuRes= $idCnx->query($requete_fraisforfait);
    if ( $idJeuRes ) {
        $ligne = $idJeuRes->fetch_assoc();
        while ( is_array($ligne) ) {
            $idFraisForfait = $ligne["idFrais"];
            // insertion d'une ligne frais forfait dans la base
            $requete = "insert into LigneFraisForfait (idVisiteur, mois, idFraisForfait, quantite)
                        values ('" . $unIdVisiteur . "','" . $unMois . "','" . $idFraisForfait . "',0)";


            $idCnx->query( $requete);
            // passage au frais forfait suivant
            $ligne = $idJeuRes->fetch_assoc ();
        }
        $idJeuRes->free_result();
    }
}

/**
 * Retourne le texte de la requête select concernant les mois pour lesquels un
 * visiteur a une fiche de frais.
 *
 * La requête de sélection fournie permettra d'obtenir les mois (AAAAMM) pour
 * lesquels le visiteur $unIdVisiteur a une fiche de frais.
 * @param string $unIdVisiteur id visiteur
 * @return string texte de la requête select
 */
function obtenirReqMoisFicheFrais($unIdVisiteur) {
    $req = "select fichefrais.mois from  fichefrais where fichefrais.idvisiteur ='"
            . $unIdVisiteur . "' order by fichefrais.mois asc ";
    return $req ;
}

/**
 * Retourne le texte de la requête select concernant les éléments forfaitisés
 * d'un visiteur pour un mois donnés.
 *
 * La requête de sélection fournie permettra d'obtenir l'id, le libellé et la
 * quantité des éléments forfaitisés de la fiche de frais du visiteur
 * d'id $idVisiteur pour le mois $mois
 * @param string $unMois mois demandé (MMAAAA)
 * @param string $unIdVisiteur id visiteur
 * @return string texte de la requête select
 */
function obtenirReqTypeFrais() {
    $requete = "select idFrais, libelle, montant from FraisForfait";
    return $requete;
}

/**
 * Retourne le texte de la requête select concernant les éléments forfaitisés
 * d'un visiteur pour un mois donnés.
 *
 * La requête de sélection fournie permettra d'obtenir l'id, le libellé et la
 * quantité des éléments forfaitisés de la fiche de frais du visiteur
 * d'id $idVisiteur pour le mois $mois
 * @param string $unMois mois demandé (MMAAAA)
 * @param string $unIdVisiteur id visiteur
 * @return string texte de la requête select
 */
function obtenirReqEltsForfaitFicheFrais( $unMois, $unIdVisiteur) {
    $unMois = filtrerChainePourBD( $unMois);
    $requete = "select idFraisForfait, libelle, quantite from LigneFraisForfait
              inner join FraisForfait on FraisForfait.idFrais = LigneFraisForfait.idFraisForfait
              where idVisiteur='" . $unIdVisiteur . "' and mois='" . $unMois . "'";
    return $requete;
}

/**
 * Retourne le texte de la requête select concernant les éléments hors forfait
 * d'un visiteur pour un mois donnés.
 *
 * La requête de sélection fournie permettra d'obtenir l'id, la date, le libellé
 * et le montant des éléments hors forfait de la fiche de frais du visiteur
 * d'id $idVisiteur pour le mois $mois
 * @param string $unMois mois demandé (MMAAAA)
 * @param string $unIdVisiteur id visiteur
 * @return string texte de la requête select
 */
function obtenirReqEltsHorsForfaitFicheFrais( $unMois, $unIdVisiteur) {
    $unMois = filtrerChainePourBD( $unMois);
    $requete = "select id, date, libelle, montant from LigneFraisHorsForfait
              where idVisiteur='" . $unIdVisiteur
              . "' and mois='" . $unMois . "'";
    return $requete;
}

/**
 * Supprime une ligne hors forfait.
 * Supprime dans la BD la ligne hors forfait d'id $unIdLigneHF
 * @param resource $idCnx identifiant de connexion
 * @param string $idLigneHF id de la ligne hors forfait
 * @return void
 */
function supprimerLigneHF($idCnx, $unIdLigneHF) {
    $requete = "delete from LigneFraisHorsForfait where id = " . $unIdLigneHF;
    $idCnx->query($requete);
}

/**
 * Ajoute une nouvelle ligne hors forfait.
 * Insère dans la BD la ligne hors forfait de libellé $unLibelleHF du montant
 * $unMontantHF ayant eu lieu à la date $uneDateHF pour la fiche de frais du mois
 * $unMois du visiteur d'id $unIdVisiteur
 * @param resource $idCnx identifiant de connexion
 * @param string $unMois mois demandé (AAMMMM)
 * @param string $unIdVisiteur id du visiteur
 * @param string $uneDateHF date du frais hors forfait
 * @param string $unLibelleHF libellé du frais hors forfait
 * @param double $unMontantHF montant du frais hors forfait
 * @return void
 */
function ajouterLigneHF($idCnx, $unMois, $unIdVisiteur, $uneDateHF, $unLibelleHF, $unMontantHF) {
    $unLibelleHF = filtrerChainePourBD($unLibelleHF);
    $uneDateHF = filtrerChainePourBD(convertirDateFrancaisVersAnglais($uneDateHF));
    $unMois = filtrerChainePourBD($unMois);
    $requete = "insert into LigneFraisHorsForfait(idVisiteur, mois, date, libelle, montant)
                values ('" . $unIdVisiteur . "','" . $unMois . "','" . $uneDateHF . "','" . $unLibelleHF . "'," . $unMontantHF .")";
    $idCnx->query($requete);
}

/**
 * Modifie les quantités des éléments forfaitisés d'une fiche de frais.
 * Met à jour les éléments forfaitisés contenus
 * dans $desEltsForfaits pour le visiteur $unIdVisiteur et
 * le mois $unMois dans la table LigneFraisForfait, après avoir filtré
 * (annulé l'effet de certains caractères considérés comme spéciaux par
 *  MySql) chaque donnée
 * @param resource $idCnx identifiant de connexion
 * @param string $unMois mois demandé (MMAAAA)
 * @param string $unIdVisiteur  id visiteur
 * @param array $desEltsForfait tableau des quantités des éléments hors forfait
 * avec pour clés les identifiants des frais forfaitisés
 * @return void
 */
function modifierEltsForfait($idCnx, $unMois, $unIdVisiteur, $desEltsForfait) {
    $unMois=filtrerChainePourBD($unMois);
    $unIdVisiteur=filtrerChainePourBD($unIdVisiteur);
    foreach ($desEltsForfait as $idFraisForfait => $quantite) {
        $requete = "update LigneFraisForfait set quantite = " . $quantite
                    . " where idVisiteur = '" . $unIdVisiteur . "' and mois = '"
                    . $unMois . "' and idFraisForfait='" . $idFraisForfait . "'";
      $idCnx->query($requete);
    }
}

/**
 * Contrôle les informations de connexionn d'un utilisateur.
 * Vérifie si les informations de connexion $unLogin, $unMdp sont ou non valides.
 * Retourne les informations de l'utilisateur sous forme de tableau associatif
 * dont les clés sont les noms des colonnes (id, nom, prenom, login, mdp)
 * si login et mot de passe existent, le booléen false sinon.
 * @param resource $idCnx identifiant de connexion
 * @param string $unLogin login
 * @param string $unMdp mot de passe
 * @return array tableau associatif ou booléen false

 */

function hashAllMDP($idCnx){
	$req ="SELECT mdp FROM visiteur";
  $results = $idCnx->query($req);
  while($mdp = $results->fetch_assoc()){
    $mot_passe[] = $mdp['mdp'];

  }
  foreach ($mot_passe as $key => $value_mdp){
    if (strlen($value_mdp) < 60) {
      $hash = hashMDP($value_mdp);
      $req_update = 'UPDATE visiteur SET mdp="'.$hash.'" WHERE mdp="'.$value_mdp.'"';
     $idCnx->query($req_update);
          return $req_update;


    }
  }

}
function hashMDP($unMdp){
    $hash = password_hash($unMdp, PASSWORD_DEFAULT);
    return $hash;
}


function verifierInfosConnexion($idCnx, $unLogin, $unMdp) {
    $unLogin = filtrerChainePourBD($unLogin);
    //$unMdp = filtrerChainePourBD($unMdp);
    $req = "select id, nom, prenom, login, mdp from visiteur where  login='".$unLogin."' and mdp='".$unMdp."'";
    var_dump($req);
    $idJeuRes = $idCnx->query($req);
    $ligne = false;
    if ( $idJeuRes ) {
        $ligne = $idJeuRes->fetch_assoc();
        $idJeuRes->free_result();
    }
    // on vérifie le mot de passe
    if($unMdp == $ligne['mdp']){
      return $ligne;
    }
    else {
      //le mot de passe ne correspond pas
      return NULL;
    }
}

function verifierInfosConnexionComptable($idCnx, $unLogin, $unMdp) {
    $unLogin = filtrerChainePourBD($unLogin);
    //$unMdp = filtrerChainePourBD($unMdp);
    // le mot de passe est crypté dans la base avec la fonction de hachage md5
    $req = "select id, nom, prenom, login, mdp from Visiteur where type='comptable' and  login='".$unLogin."'";
    $idJeuRes = $idCnx->query($req);
    $ligne = false;
    if ( $idJeuRes ) {
        $ligne = $idJeuRes->fetch_assoc();
        $idJeuRes->free_result();

      // on vérifie le mot de passe
      if($unMdp == $ligne['mdp']){
        return $ligne;
      }
      else {
        //le mot de passe ne correspond pas
        return NULL;
      }

  }
}

/**
 * Modifie l'état et la date de modification d'une fiche de frais

 * Met à jour l'état de la fiche de frais du visiteur $unIdVisiteur pour
 * le mois $unMois à la nouvelle valeur $unEtat et passe la date de modif à
 * la date d'aujourd'hui
 * @param resource $idCnx identifiant de connexion
 * @param string $unIdVisiteur
 * @param string $unMois mois sous la forme aaaamm
 * @return void
 */
function modifierEtatFicheFrais($idCnx,$unIdVisiteur, $nbrJustficatif, $calculTotal) {
    $requete = "update fichefrais set idEtat = 'VA', dateModif = now(), nbJustificatifs ='".$nbrJustficatif."', montantValide = '".$calculTotal."' where idVisiteur ='" .
               $unIdVisiteur . "'";
    $idCnx->query($requete);
}



function ajoutVehicule($idCnx, $unIdVisiteur, $marque, $modele, $puissance) {

    $requete  = "INSERT into Vehicule (idVisiteur, marque, modele, puissance) values ('". $unIdVisiteur . "','" .$marque. "','" . $modele. "','". $puissance . "')";

    $idCnx->query($requete);

}

function  obtenirInfoVH($idCnx, $unId){
        $requete = "select * FROM Vehicule WHERE idVisiteur = '" . $unId .  "'";

            $result = $idCnx->query($requete);
          if ( $result) {
               $ligne = mysqli_fetch_all($result);
            }


            return $ligne;
}

function modifVH($idCnx, $marque, $modele, $puissance, $unIdVisiteur){
$requeteMVH= "update Vehicule set  marque ='".$marque."', modele='".$modele."', puissance='".$puissance."' where idVisiteur ='" .$unIdVisiteur . "'";
$idCnx->query($requeteMVH);


}

function listeVisiteur($idCnx, $unId){
    $req ="select nom, prenom, id, telephone from visiteur";
    $result = $idCnx->query($req);
  if ( $result) {
       $ligne = mysqli_fetch_all($result);

    }
      $ligne;
}



function fraisAll($idCnx, $unId,$mois){
    $requete ="select * from LigneFraisForfait where LigneFraisForfait.idVisiteur ='". $unId . "' and mois='".$mois."'";
    $result = $idCnx->query($requete);
  if ( $result) {
       $ligne = mysqli_fetch_all($result);
    }


    return $ligne;


}

function fraisHF($idCnx, $unId,$mois){
    $requeteHF ="select * from  LigneFraisHorsForfait where LigneFraisHorsForfait.idVisiteur ='". $unId . "' and mois='".$mois."'";

    $result = $idCnx->query($requeteHF);
  if ( $result) {
       $ligne = mysqli_fetch_all($result);
    }


    return $ligne;


}
function infoVisiteur($idCnx, $unId){
    $requeteVisiteur = "select nom, prenom, id from Visiteur where id='". $unId . "'";
  $result = $idCnx->query($requeteVisiteur);
  if($result){
    $ligne = mysqli_fetch_all($result);
  }
  return $ligne;
}

function fraisForfait($idCnx, $unId,$mois){
  $requeteForfait = "select * from FraisForfait inner join LigneFraisForfait on LigneFraisForfait.idFraisForfait =  FraisForfait.idFrais where idVisiteur='". $unId . "' and mois='".$mois."'";
  $result = $idCnx->query($requeteForfait);
  if($result){
    $ligne= mysqli_fetch_all($result);
  }
  return $ligne;
}

function calculfraisHF($idCnx, $unId, $unMois){
    $unMois = filtrerChainePourBD( $unMois);
  $requeteCalcul ="select sum(montant) from LigneFraisHorsForfait where idVisiteur='". $unId . "' and date ='". $unMois . "'";
  $result=$idCnx->query($requeteCalcul);
  if($result){
    $ligne=mysqli_fetch_all($result);
  }
  return $ligne;
}

function modifierEtatRefus($idCnx,$unIdVisiteur, $nbrJustficatif, $calulTotal) {
    $requete = "update fichefrais set idEtat = 'RE', dateModif = now(), nbJustificatifs ='".$nbrJustficatif."', montantValide = '".$calulTotal."' where idVisiteur ='" .
               $unIdVisiteur . "'";
    $idCnx->query($requete);
}

function modifierEtatRB($idCnx,$unIdVisiteur) {
    $requete = "update fichefrais set idEtat = 'RB', dateModif = now() where idVisiteur ='" .
               $unIdVisiteur . "'";
    $idCnx->query($requete);
}

function calculKM($idCnx, $unId){
  $requete_puissance = 'SELECT puissance FROM Vehicule WHERE idVisiteur="'.$unId.'"';
  $result_puissance = $idCnx->query($requete_puissance);
  if($result_puissance){
    $puissance = mysqli_fetch_row($result_puissance);
  }
  switch($puissance[0]){
    case '3':
      $bareme = 0.286;
      break;
    case '4':
      $bareme = 0.332;
      break;
    case '5':
      $bareme = 0.364;
      break;
    case '6':
      $bareme = 0.382;
      break;
    default:
      $bareme = 0.401;
  }
  return $bareme;
}

function contact($idCnx,$nom,$mail,$texte) {
  $requete = "insert into Contact (nom,mail,texte) values ('"
            . $nom. "','" . $mail . "', '".$texte."' )";
  $idCnx->query($requete);
}
