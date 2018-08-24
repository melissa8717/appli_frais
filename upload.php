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
    $req = obtenirReqEltsHorsForfaitFicheFrais($moisSaisi);
    $idJeuEltsHorsForfait = $idConnexion->query($req);
    $lgEltHorsForfait = $idJeuEltsHorsForfait->fetch_assoc();
    $idFraisHF = $lgEltHorsForfait["id"];
    var_dump($idFraisHF);
?>
  <!-- Division principale -->
 <?php
      if (estVisiteurConnecte() ) {
          $idUser = obtenirIdUserConnecte() ;
          var_dump($idUser);
          $lgUser = obtenirDetailVisiteur($idConnexion, $idUser);
          $nom = $lgUser['nom'];
          var_dump($nom);
          $nomU= $lgUser['nom']."/".$prenom."/";
          $prenom = $lgUser['prenom'];

		}

    ?>

<?php
$idHF= $_GET['id'];
var_dump($idHF);
$mois = date('Ym');
var_dump($mois);
$date = date("d-m-Y ");
$heure= date('H:i:s');
$login = lireDonneePost("txtLogin");
$dossier_visiteur = 'C:\wamp64\www\appli_frais\upload'.$idUser."/".$mois."/".$idHF"/";
var_dump($dossier_visiteur);
$fichier = $_FILES['userfile']['name'].$date.$heure;
$taille_maxi = 1000000;
$taille = $_FILES['userfile']['size'];
$extensions = array('.png', '.gif', '.jpg', '.jpeg');
$extension = strrchr($_FILES['userfile']['name'], '.');


	if(!in_array($extension, $extensions))
	{
	     $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg';
	}
	if($taille>$taille_maxi)
	{
	     $erreur = 'Le fichier est trop gros...';
	}
if(!isset($erreur)){
$fichier = strtr($fichier,
		  'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
		  'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
$fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);



if(is_dir($dossier_visiteur) == FALSE) {
 mkdir($dossier_visiteur, 0777, true);


}
	    if(move_uploaded_file($_FILES['userfile']['tmp_name'], $dossier_visiteur.$fichier)) {
        $_SESSION['url']=$dossier_visiteur.$fichier;

        header('Location: cJustificatif.php');
        }
	     else
	     {
		  echo 'Echec de l\'upload !';
    }
  }


?>
<?php
  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?>
