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

  if (isset($_POST['txtMarque'])){
    //Fonction de validation / enregistrement
    ajoutVehicule($idConnexion, obtenirIdUserConnecte(), $_POST['txtMarque'], $_POST['txtModele'], $_POST['txtPuissance']);

  }

?>
  <!-- Division principale -->
  <div id="contenu">
      <h1>Fiche Véhicule</h1>
       <form id="" action="" method="post">
      <div class="corpsForm">
        <input type="hidden" name="etape" id="etape" value="validerConnexion" />
      <p>
        <label for="txtMarque" >Marque :</label>
        <input type="text" id="txtMarque" name="txtMarque"  />
      </p>
      <p>
        <label for="txtModel" >Modèle : </label>
        <input type="text" id="txtModele" name="txtModele"/>
      </p>
       <p>
        <label for="txtPuissance" >Puissance fiscale : </label>
        <input type="text" id="txtPuissance" name="txtPuissance" />
      </p>

      </div>

      <div class="piedForm">

      <p>
        <input type="submit" id="ok" value="Valider" />
        <input type="reset" id="annuler" value="Effacer" />
      </p>
      </div>
      </form>

  </div>

<?php
  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?>
