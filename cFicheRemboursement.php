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
  $requete = fraisMois($idConnexion, obtenirIdUserConnecte());

?>
  <!-- Division principale -->
  <div id="contenu">
      <h2>Mes fiches de remboursement</h2>
    <?php  $nbFichier = 0;
    $mois = date('Ym');
    ?>
  <table>
    <tr>
      <td>Mois</td>
      <td>Lien</td>
    </tr>

<?php

  foreach($requete->fetch_all() as $result){
    $dir = 'C:/wamp64/www/appli_frais/PDF_Fiche_Frais/'.$unId."/".$result[0];

    ?>
    <tr>
      <td><?php echo $result[0];?></td>
      <?php
      if(is_dir($dir)){
        $dossier = opendir($dir);
          $path = $_SERVER['SERVER_NAME'] ;
          $path_file = str_replace($_SERVER['DOCUMENT_ROOT'],$path, $dir);
          ?>
          <td>
            <?php
          while (false !== ($fichier = readdir($dossier))) {
            if ($fichier != "." && $fichier != "..") {
              echo '<a href="http://'.$path_file.'/'.$fichier.'">Télécharger le PDF</a>';
            }
          }?>
        </td>
        <?php
          closedir($dossier);
      }
      else {
        echo '<td>Pas de fiche de remboursement</td>';
      }
      ?>
    </tr>
  <?php
  }
