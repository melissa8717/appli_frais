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
            header('Refresh: 3; cGed.php');
          }?>
          <h1>Vérification du fichier</h1>
          <h2>Merci de valider ou non le fichier téléchargé</h2>
          <table>
            <tr>
              <td><h2><a href="?delete=<?php echo $path_file;?>">Supprimer le fichier</a> </h2></td>
              <td></td>
              <td><h2><a href="cGed.php">Valider</a></h2></td>
            </tr>
          </table>
          <img src="http://<?php echo $path_file;?>"/>

<?php


    require($repInclude . "_pied.inc.html");
    require($repInclude . "_fin.inc.php");
