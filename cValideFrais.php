<?php
/**
 * Script de contrôle et d'affichage du cas d'utilisation "Se connecter"
 * @package default
 * @todo  RAS
 */
    // Afficher les erreurs à l'écran
    ini_set('display_errors', 1);
    // Enregistrer les erreurs dans un fichier de log
    ini_set('log_errors', 1);
    // Nom du fichier qui enregistre les logs (attention aux droits à l'écriture)
    ini_set('error_log', dirname(__file__) . '/log_error_php.txt');
    // Afficher les erreurs et les avertissements
    error_reporting(E_ALL);

  $repInclude = './include/';
  require($repInclude . "_init.inc.php");
  // est-on au 1er appel du programme ou non ?
  $etape=(count($_POST)!=0)?'validerConnexionCompta' : 'demanderConnexionCompta';

  if ($etape=='validerConnexionCompta') { // un client demande à s'authentifier
      // acquisition des données envoyées, ici login et mot de passe
      $login = lireDonneePost("txtLoginCompta");
      $mdp = lireDonneePost("txtMdpCompta");
      $lgUser = verifierInfosConnexionComptable($idConnexion, $login, $mdp) ;
      // si l'id utilisateur a été trouvé, donc informations fournies sous forme de tableau
      if ( is_array($lgUser) ) {
          affecterInfosConnecte($lgUser["id"], $lgUser["login"]);
      }
      else {
          ajouterErreur($tabErreurs, "Pseudo et/ou mot de passe incorrects");
      }
  }
  if ( $etape == "validerConnexionCompta" && nbErreurs($tabErreurs) == 0 ) {
        header("Location:cValideFrais.php");
  }

  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaireComptable.inc.php");
  //$unId = $_GET["id"];

?>

<!-- Division pour le contenu principal -->
    <div id="contenu">


<?php
          if ( $etape == "validerConnexionCompta" )
          {
              if ( nbErreurs($tabErreurs) > 0 )
              {
                echo toStringErreurs($tabErreurs);
              }
          }
?>


     <table class="listeLegere">
       <tr class="corpsForm">
         <td style="visibility: hidden;">id</td><td>Nom </td><td>Prénom</td><td>Téléphone</td><td>Justificatifs</td></tr>

      <?php
	  



$req= listeVisiteur($idConnexion, $lgUser["id"]);


	  foreach ( $req as $value )  {
      ?>
         <tr>
<?php
                    $nom = $value[0];
                    $prenom = $value[1];
                    $unId = $value[2];
                    $tel=$value[3];



	?>
           <td style="visibility: hidden;"><?php echo $unId ; ?></td><td><?php echo $nom ; ?></td><td><?php echo $prenom ; ?></td><td><?php echo $tel;?></td><?php echo"".'<td><a href="cVoirfrais.php/?id='.$unId.'">Voir les frais</a></td>';?>
     </tr>
   <?php }?>



     </table>


<?php
    require($repInclude . "_pied.inc.html");
    require($repInclude . "_fin.inc.php");
