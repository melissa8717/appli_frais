<?php
/**
 * Page d'accueil de l'application web AppliFrais
 * @package default
 * @todo  RAS
 */
  $repInclude = './include/';
  require($repInclude . "_init.inc.php");
  // page inaccessible si visiteur non connecté
  if ( ! estVisiteurConnecte() )
  {
        header("Location: cSeConnecter.php");
  }
    require($repInclude . "_entete.inc.html");
    require($repInclude . "_sommaire.inc.php");
          $idUser = obtenirIdUserConnecte() ;
          $lgUser = obtenirDetailVisiteur($idConnexion, $idUser);
          $nom = $lgUser['nom'];
          $nomU= $lgUser['nom']."/".$prenom."/";
          $prenom = $lgUser['prenom'];
$idHF= $_POST['id'];
$mois = date('Ym');
$date = date("d-m-Y ");
$heure= date('H:i:s');
$login = lireDonneePost("txtLogin");
$dossier_visiteur = 'C:/wamp64/www/appli_frais/upload/'.$idUser."/".$mois."/".$idHF."/";
$fichier = $_FILES['userfile']['name'].$date.$heure;
$taille_maxi = 1000000;
$taille = $_FILES['userfile']['size'];
$extensions = array('.png', '.gif', '.jpg', '.jpeg');
$extension = strrchr($_FILES['userfile']['name'], '.');
//si extension non autorisée
	if(!in_array($extension, $extensions))
	{
	     $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg';
	}
	if($taille>$taille_maxi)
	{
	     $erreur = 'Le fichier est trop gros...';
	}
  //remplacement des caracteres speciaux
if(!isset($erreur)){
  $fichier = strtr($fichier,
  		  'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
  		  'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
        //tout autre caracteres que des lettres ou des chiffres sera remplaces par un tiret
  $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
//creation du dossier si inexistant
  if(is_dir($dossier_visiteur) == FALSE) {
   mkdir($dossier_visiteur, 0777, true);
  }
//deplacement du fichier vers le bon dossier
  if(move_uploaded_file($_FILES['userfile']['tmp_name'], $dossier_visiteur.$fichier)) {
    $url = $dossier_visiteur.$fichier;
    AjoutCheminJustificatif($idConnexion, $url, $idHF);
    //TODO
    //Requete à créer : update sur la table fraisforfait ligne mettre en valeur $dossier_visiteur.$chemin AjoutCheminJustificatif()
    header('Location: cJustificatif.php?id='.$idHF.'');
  }
	else {
	 echo 'Echec de l\'upload !';
  }
}
  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?>
