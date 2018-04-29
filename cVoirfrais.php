<?php
/**
 * Script de contrôle et d'affichage du cas d'utilisation "Se connecter"
 * @package default
 * @todo  RAS
 */
  $repInclude = './include/';
  require($repInclude . "_init.inc.php");

  // est-on au 1er appel du programme ou non ?
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
  if ( $etape == "validerConnexionCompta" && nbErreurs($tabErreurs) == 0 ) {
        header("Location:cValideFrais.php");
  }
  $moisSaisi=lireDonneePost("lstMois", "");

  $unId = $_GET["id"];
  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaireComptable.inc.php");

?>



<?php
          if ( $etape == "validerConnexionCompta" )
          {
              if ( nbErreurs($tabErreurs) > 0 )
              {
                echo toStringErreurs($tabErreurs);
              }
          }
$mois = date("m-y");
?>
<div id="contenu">
<?php $requeteVisiteur=infoVisiteur($idConnexion, $unId);
foreach ($requeteVisiteur as $value) {
  $nom=$value[0];
  $prenom=$value[1];
}



 ?>
<?php echo '<h1>'.'Mois de '.$mois.' pour '.$nom.' '.$prenom.'</h1>';?>
<h2>Frais forfaitisés</h2>

     <table>

       <tr>
        <td>Type</td><td>Quantité</td><td>Justificatifs</td></tr>

       <?php $requete=  fraisAll($idConnexion, $unId, $mois);
       foreach ($requete as $valeur) { ?>

       <tr>
         <?php
                             $idFrais =$valeur[4];
                             $frais = $valeur[0];
                             $unId =$valeur[2];
                             $idLigne=$valeur[1];
                             $mois=$valeur[3];

         	?>

         <td><?php echo $idFrais ; ?></td><td><?php echo $frais ; ?></td><?php echo"".'<td><a href="../cAllJustificatif.php/?id='.$unId.'" target="_blank">Voir les justificatifs</a></td>';?>
       </tr>
     <?php }?>

     </table>
     <br />
     <h2>Frais hors forfait</h2>
     <table>
       <tr>
        <td>Type</td><td>Montant en €</td><td>Justificatifs</td></tr>

       <?php $requeteHF=  fraisHF($idConnexion, $unId);
       foreach ($requeteHF as $valeur) { ?>

       <tr>
         <?php
                             $fraisHF = $valeur[3];
                             $montantFHF = $valeur[4];
                             $unId =$valeur[1];
                             $nomU = $valeur[0];

           ?>
         <td><?php echo $fraisHF ; ?></td><td><?php echo $montantFHF ; ?></td><?php echo"".'<td><a href="../cAllJustificatif.php/?id='.$unId.'" target="_blank">Voir les justificatifs</a></td>';?>
       </tr>
     <?php }?>

     </table>
<br />
<?php echo"".'<a href="../cFicheFrais.php/?id='.$unId.'">Créer la fiche de frais</a>';?><?php
    require($repInclude . "_pied.inc.html");
    require($repInclude . "_fin.inc.php");
