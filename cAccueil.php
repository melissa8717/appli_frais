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
  <div id="contenuAccueil">
      <h1>Bienvenue sur l'intranet GSB</h1>
      <h3>Dans cet espace vous pouvez enrgistrer, consulter et modifier toutes vos fiches de frais,
      qu'elles soient en forfait ou en hors forfait.<br />
      Utilisez simplement le menu situé à gauche.
      <div style="height:500px;"><img src="images/comm.jpg"  /></div>

    </h3>
  </div>

<?php
  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?>
