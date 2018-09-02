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
     $unIdFrais = $_GET["id"];

     $requete = causeRefuse($idConnexion,$unIdFrais, $_POST['txtCause']);
     $file_to_delete = $_GET['delete'];
     unlink($file_to_delete);

  }
  $unIdFrais = $_GET["id2"];
  $unIdV =$_GET["id"];



  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaireComptable.inc.php");

?>

<!-- Division pour le contenu principal -->
    <div id="contenuUpload">
      <div id="imgUpload">

<?php

$mois = date('Ym');


          if ( $etape == "validerConnexionCompta" )
          {
              if ( nbErreurs($tabErreurs) > 0 )
              {
                echo toStringErreurs($tabErreurs);
              }
          }


          $nbFichier = 0;
          $dir = 'C:/wamp64/www/appli_frais/upload/'.$unIdV."/".$mois."/".$unIdFrais."/";

          if($dossier = opendir($dir)){
            $path = $_SERVER['SERVER_NAME'] ;
            $path_file = str_replace($_SERVER['DOCUMENT_ROOT'],$path, $dir);

            while(false !== ($fichier = readdir($dossier))){
              if($fichier !='.' && $fichier !='..' && $fichier != 'index.php'){
                $nbFichier++;
                ?>
                <form action="" method="post">
                  <table class="listeLegere">
                    <tr class="corpsForm">
                      <td>Cause du refus</td></tr>
                    <tr>
                    <td><input type="text" id="txtCause" name="txtCause"[<?php echo $cause ?>]"value=""/></td>
                    </tr>
                    <tr>
                    <td><h2><a href="?id=<?php echo $unIdV;?>&?id2=<?php echo $unIdFrais;?>&delete=<?php echo $dir.$fichier;?>">Refuser</a> </h2><td></tr><br /></table>
                  </table>
                </form>
              <?php }
            }
            closedir($dossier);

          }
          else{
            echo ' Pas de frais ';
          }


    require($repInclude . "_pied.inc.html");
    require($repInclude . "_fin.inc.php");