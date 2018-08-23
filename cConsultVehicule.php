<?php
/**
 * Script de contrôle et d'affichage du cas d'utilisation "Consulter une fiche de frais"
 * @package default
 * @todo  RAS
 */
  $repInclude = './include/';
  require($repInclude . "_init.inc.php");

  // page inaccessible si visiteur non connecté
  if ( ! estVisiteurConnecte() ) {
      header("Location: cSeConnecter.php");
  }
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

  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaire.inc.php");


  $unId = $_GET["id"];
  $requete=obtenirInfoVH($idConnexion, $unId);

  if($_POST){
  modifVH($idConnexion, $_POST['txtMarque'], $_POST['txtModele'], $_POST['txtPuissance'], $unId);
  }

?>
<div id="contenu" >
  <form action="" method="post">

<table class="listeLegere">
  <tr class="corpsForm">
    <td>Marque *</td><td>Modèle</td><td>Puissance fiscale</td>
  </tr>
  <?php foreach ($requete as $vehi) {?>

    <tr>

      <?php
        $marque=$vehi[2];
        $modele=$vehi[3];
        $puissance=$vehi[4];

       ?>

       <td>    <input type="text" id="<?php echo $marque;?>" name="txtMarque" value="<?php echo $marque;?>" /></td>
       <td>    <input type="text" id="<?php echo $modele;?>" name="txtModele" value="<?php echo $modele;?>" /></td>
       <td>    <input type="text" id="<?php echo $puissance;?>" name="txtPuissance" value="<?php echo $puissance;?>" /></td>
     </tr>
   </table>





<?php }

  ?>
<br />
    <div class="piedForm">


    <input type="submit" id="ok" value="Modifier" />
  </div>
</form>
</div>
<?php
require($repInclude . "_pied.inc.html");
require($repInclude . "_fin.inc.php");
