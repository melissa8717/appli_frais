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
?>
  <!-- Division principale -->
  <div id="contenu">
      <h1>Bienvenue sur l'intranet GSB</h1>
      <div id="blockAccueil" >
        <a href="cVehicule.php">
        <h2 >Véhicule</h2>
        <img src="images/car.png" style="padding-left: 50px;padding-top: 10px;"></a>
      </div>
      <div id="blockAccueil2">
       <a href="cGed.php">
        <h2>GED</h2>
        <img src="images/doc.png" style="padding-left: 60px;padding-top: 15px;"></a>

      </div>
  </div>
<?php        
  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?>
