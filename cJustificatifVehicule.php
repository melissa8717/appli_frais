<?php
/**
 * Script de contrôle et d'affichage du cas d'utilisation "Se connecter"
 * @package default
 * @todo  RAS
 */
  $repInclude = './include/';
  require($repInclude . "_init.inc.php");

  // est-on au 1er appel du programme ou non ?
  $etape=(count($_POST)!=0)?'validerConnexion' : 'demanderConnexion';

  if ($etape=='validerConnexion') { // un client demande à s'authentifier
      // acquisition des données envoyées, ici login et mot de passe
      $login = lireDonneePost("txtLogin");
      $mdp = lireDonneePost("txtMdp");
      $lgUser = verifierInfosConnexion($idConnexion, $login, $mdp) ;
      // si l'id utilisateur a été trouvé, donc informations fournies sous forme de tableau
      if ( is_array($lgUser) ) {
          affecterInfosConnecte($lgUser["id"], $lgUser["login"]);
      }
      else {
          ajouterErreur($tabErreurs, "Pseudo et/ou mot de passe incorrects");
      }
  }
  if ( $etape == "validerConnexion" && nbErreurs($tabErreurs) == 0 ) {
        header("Location:cAccueil.php");
  }

   if(isset($_GET['delete'])){
     $file_to_delete = $_GET['delete'];
     unlink($file_to_delete);
     $idVehi=$_GET['id'];
     header("Location:/appli_frais/cAccueil.php");
  }

  $idVehi=$_GET['id'];



  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaire.inc.php");

?>

<!-- Division pour le contenu principal -->
    <div id="contenuUpload">
      <div id="imgUpload">

<?php
$mois = date('Ym');
$requete = obtenirInfoVH($idConnexion, $lgUser["id"]);
foreach ($requete as  $vehi) {
  $idVehi = $vehi[0];
}

if(isset($_GET['id'])){
  $idVehi = $_GET["id"];

          if ( $etape == "validerConnexionCompta" )
          {
              if ( nbErreurs($tabErreurs) > 0 )
              {
                echo toStringErreurs($tabErreurs);
              }
          }


          $nbFichier = 0;
          $dir = 'C:/wamp64/www/appli_frais/upload/Vehicule/'.$lgUser["id"]."/".$idVehi."/";

          if($dossier = opendir($dir)){
            $path = $_SERVER['SERVER_NAME'] ;
            $path_file = str_replace($_SERVER['DOCUMENT_ROOT'],$path, $dir);

            while(false !== ($fichier = readdir($dossier))){
              if($fichier !='.' && $fichier !='..' && $fichier != 'index.php'){
                $nbFichier++;
                ?>
                <table>
                  <tr>
                <?php echo '<td>'.'<img src="http://'.$path_file.'/'.$fichier.'"/>'.'</td>';?></tr>
                <td><h2><a href="?delete=<?php echo $path_file;?>">Supprimer le fichier</a> </h2>
                    <h2><a href="?valider&id=<?php echo $idVehi;?>">Valider</a></h2>
                </td>

              <?php }
            }
            closedir($dossier);

          }
          else{
            echo ' Pas de frais ';
          }

}
    require($repInclude . "_pied.inc.html");
    require($repInclude . "_fin.inc.php");
