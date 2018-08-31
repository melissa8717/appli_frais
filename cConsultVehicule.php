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


  require($repInclude . "_entete.inc.html");
  require($repInclude . "sommaire_up.inc.php");


  $unId = $_GET["id"];

   if($_POST && isset($_POST['ok'])){
     if($_POST['txtIdVehicule']){
       $requeteVehi = modifVH($idConnexion, $_POST['txtMarque'], $_POST['txtModele'], $_POST['txtPuissance'], $_POST['txtIdVehicule']);
     }
     else {
      $requeteVehi=ajoutVehicule($idConnexion, $unId, $_POST['txtMarque'], $_POST['txtModele'], $_POST['txtPuissance'], $_POST['txtImmatriculation']);
    }
  }

?>
<div id="contenu" >
  <form action="" method="post">

<table class="listeLegere">
  <tr class="corpsForm">
    <td>Marque *</td><td>Modèle</td><td>Puissance fiscale</td><td>Immatriculation</td>
  </tr>
  <?php
  $requete=obtenirInfoVH($idConnexion, $unId);

  if($requete){

  foreach ($requete as $vehi) {?>

    <tr>

      <?php
        $idVehi=$vehi[0];
        $marque=$vehi[2];
        $modele=$vehi[3];
        $puissance=$vehi[4];
        $immatriculation=$vehi[5];

       ?>

       <td>
         <input type="hidden" id="<?php echo $idVehi;?>" name="txtIdVehicule" value="<?php echo $idVehi;?>" />
         <input type="text" id="<?php echo $marque;?>" name="txtMarque" value="<?php echo $marque;?>" /></td>
       <td>    <input type="text" id="<?php echo $modele;?>" name="txtModele" value="<?php echo $modele;?>" /></td>
       <td>    <input style="width:30px;" type="text" id="<?php echo $puissance;?>" name="txtPuissance" value="<?php echo $puissance;?>" /></td>
       <td>    <input type="text" id="<?php echo $immatriculation;?>" name="txtImmatriculation" value="<?php echo $immatriculation;?>" /></td>


       <td>
    <div class="piedForm">


    <input type="submit" name="ok" value="Modifier" />

  </div>
</form>
</td>
</tr>
<tr >
<td colspan="4">

 <form  action="../uploadCG.php" method="post" enctype="multipart/form-data" >

      Transfèrer la carte grise<br /><br /><br />
      <input type="hidden" name="idVehi" value="<?php echo $idVehi; ?>" />
       <input type="file" name="userfile" value="userfile" /><br /><br />
      <input type="submit"  value="Valider" />
    </form>
  </td>
</tr>
<?php }
}
else {
  ?>
  <tr>
     <td>    <input type="text"  name="txtMarque" value="" /></td>
     <td>    <input type="text" name="txtModele" value="" /></td>
     <td>    <input type="text" name="txtPuissance" value="" /></td>
     <td>    <input type="text" name="txtImmatriculation" value="" /></td>

     <td>
  <div class="piedForm">


  <input type="submit" name="ok" value="Modifier" />

</div>
</form>
</td>
</tr>
<?php
}
    ?>
  </table>
</div>
<?php
require($repInclude . "_pied.inc.html");
require($repInclude . "_fin.inc.php");
