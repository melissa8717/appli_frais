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

  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaire.inc.php");

?>

<!-- Division pour le contenu principal -->
    <div id="contenuUpload">


<?php
          if ( $etape == "validerConnexionCompta" )
          {
              if ( nbErreurs($tabErreurs) > 0 )
              {
                echo toStringErreurs($tabErreurs);
              }
          }



          $path = $_SERVER['SERVER_NAME'] ;
          $path_file = str_replace($_SERVER['DOCUMENT_ROOT'],$path, $_SESSION['url']);

          ?>
        <?php  if(isset($_GET['delete'])){
            unlink($_SESSION['url']);
            echo '<h3>Fichier supprimé</h3>';
            header('Refresh: 4; cGed.php');
          }
          if(isset($_GET['valider'])){
              echo '<h3>Fichier validé</h3>';
              header('Refresh: 4; cGed.php');
            }?>
          <table>
          <tr>
            <h1>Vérification du fichier</h1>
            <h2>Merci de valider ou non le fichier téléchargé</h2>
        </tr>

            <tr >
              <td ><img src="http://<?php echo $path_file;?>" style="padding-left:130px;"/></td>
            </tr>
            <tr>
              <td><h2><a href="?delete=<?php echo $path_file;?>">Supprimer le fichier</a> </h2><h2><a href="?valider=cGed.php">Valider</a></h2></td>

            </tr>
          </table>

<?php


    require($repInclude . "_pied.inc.html");
    require($repInclude . "_fin.inc.php");
