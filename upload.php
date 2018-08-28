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


      if (estVisiteurConnecte() ) {
          $idUser = obtenirIdUserConnecte() ;
          $lgUser = obtenirDetailVisiteur($idConnexion, $idUser);
          $nom = $lgUser['nom'];
          $nomU= $lgUser['nom']."/".$prenom."/";
          $prenom = $lgUser['prenom'];

		}


$idHF= $_POST['id'];

$mois = date('Ym');
$date = date("d-m-Y ");
$heure= date('H:i:s');
$login = lireDonneePost("txtLogin");
$dossier_visiteur = 'C:/wamp64/www/appli_frais/upload/'.$idUser."/".$mois."/";
$fichier = $_FILES['userfile']['name'].$date.$heure;
$taille_maxi = 1000000;
$taille = $_FILES['userfile']['size'];
$extensions = array('.png', '.gif', '.jpg', '.jpeg');
$extension = strrchr($_FILES['userfile']['name'], '.');
if (empty($FILES) && empty($POST) && isset($SERVER['REQUEST_METHOD']) && strtolower($SERVER['REQUEST_METHOD']) == 'post') {

$poidsMax = ini_get('post_max_size');
$oElement->addError("fileoverload", "Your feet is too big, maximum allowed size here is $poidsMax.");
}

	if(!in_array($extension, $extensions))
	{
	     $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg';
	}
	if($taille>$taille_maxi)
	{
	     $erreur = 'Le fichier est trop gros...';
	}
if(!isset($erreur)){
  header('Location: cJustificatif.php');


$fichier = strtr($fichier,
		  'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
		  'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
$fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);



if(is_dir($dossier_visiteur) == FALSE) {
 mkdir($dossier_visiteur, 0777, true);


}

header('Location: cJustificatif.php');

	    if(move_uploaded_file($_FILES['userfile']['tmp_name'], $dossier_visiteur.$fichier)) {
        $_SESSION['url']=$dossier_visiteur.$fichier;
        print'$fichier';
        header('Location: cJustificatif.php');
        }
	     else
	     {
		  echo 'Echec de l\'upload !';
    }
  }



  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?>
