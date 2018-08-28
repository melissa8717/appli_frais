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
  $idHF= $_GET['id'];

  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaire.inc.php");

?>
  <!-- Division principale -->
  <div id="contenu">
      <h1>GED</h1>

 <form  action="../upload.php" method="post" enctype="multipart/form-data" >

      Transfèrer le fichier<br /><br /><br />
      <input type="hidden" name="id" value="<?php echo $idHF;?>" />
       <input type="file" name="userfile" value="userfile" /><br /><br />
      <input type="submit"  value="Valider" />
    </form>

  </div>
<?php
  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?>
