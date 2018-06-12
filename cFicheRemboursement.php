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
  require($repInclude . "_sommaire.inc.php");

  // acquisition des données entrées, ici le numéro de mois et l'étape du traitement
  $moisSaisi=lireDonneePost("lstMois", "");
  $etape=lireDonneePost("etape","");

  if ($etape != "demanderConsult" && $etape != "validerConsult") {
      // si autre valeur, on considère que c'est le début du traitement
      $etape = "demanderConsult";
  }
  if ($etape == "validerConsult") { // l'utilisateur valide ses nouvelles données

      // vérification de l'existence de la fiche de frais pour le mois demandé
      $existeFicheFrais = existeFicheFrais($idConnexion, $moisSaisi, obtenirIdUserConnecte());

      // si elle n'existe pas, on la crée avec les élets frais forfaitisés à 0
      if ( !$existeFicheFrais ) {
          ajouterErreur($tabErreurs, "Le mois demandé est invalide");
      }
      else {
          // récupération des données sur la fiche de frais demandée
          $tabFicheFrais = obtenirDetailFicheFrais($idConnexion, $moisSaisi, obtenirIdUserConnecte());
      }
  }
  $unId = $_SESSION["idUser"];

?>
  <!-- Division principale -->
  <div id="contenu">
      <h2>Mes fiches de remboursement</h2>
    <?php  $nbFichier = 0;
      $dir = '/var/www/html/PPE/PDF_Fiche_Frais/'.$unId;
    if($dossier = opendir($dir)){
        $path = $_SERVER['SERVER_NAME'] ;
        $path_file = str_replace($_SERVER['DOCUMENT_ROOT'],$path, $dir);

        while(false !== ($fichier = readdir($dossier))){
          if($fichier !='.' && $fichier !='..' && $fichier != 'index.php'){
            $nbFichier++;
            ?>
            <table>
              <tr>
            <?php echo '<td>'.'<a href="http://'.$path_file.'/'.$fichier.'">Télécharger le PDF</a>'.'</td>';?></tr>


          <?php }
        }
        closedir($dossier);
      }
      else {
        echo 'Pas de fiche de remboursement';
      }
