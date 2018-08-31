<?php
/**
 * Script de contrôle et d'affichage du cas d'utilisation "Se connecter"
 * @package default
 * @todo  RAS
 */
  $repInclude = './include/';
  require($repInclude . "_init.inc.php");
  if ( ! estVisiteurConnecte() )
  {
        header("Location:cValideFrais.php");
  }

  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaireComptable.inc.php");


		  
		    $idFrais=$_GET["id"];
  if ($_POST){
    $requete = modifierFrais($idConnexion, $idFrais, $_POST['txtLibelle'], $_POST['txtMontant']);
    //header("Location: ../cConsultFrais.php");

}
		    if (isset($_POST['ok'])){
    echo 'Frais modifié correctement';
  }


?>
<div id="contenu">

<h2>Frais forfaitisés</h2>



  
       <form id="" action="" method="post">
      <div class="corpsForm">
        <input type="hidden" name="etape" id="etape" value="validerConnexion" />
      <p>
        <label for="txtMarque" >* Libellé :</label>
        <input type="text" id="txtLibelle" name="txtLibelle"  />
      </p>
      <p>
        <label for="txtModel" >* Montant : </label>
        <input type="text" id="txtMontant" name="txtMontant"/>
      </p>
    
      </div>

      <div class="piedForm">

      <p>
        <input type="submit" id="ok" value="Valider" name="ok" />
        <input type="reset" id="annuler" value="Effacer" />
      </p>
      </div>
      </form>

  </div>
    <?php
    require($repInclude . "_pied.inc.html");
    require($repInclude . "_fin.inc.php");
