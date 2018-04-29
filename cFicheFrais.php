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
<?php echo '<h1>'.'Fiche de frais du mois de '.$mois.' pour '.$nom.' '.$prenom.'</h1>';?>
<h2>Frais forfaitisés</h2>

     <table>

       <tr>
        <td>Type</td><td>Quantité</td><td>Forfait</td><td>Total ligne</td>
      </tr>

       <?php $requeteForfait=  fraisForfait($idConnexion, $mois);
       $calculFrais = 0;

       foreach ($requeteForfait as $valeur) { ?>

       <tr>
         <?php
                             $unIdForfait =$valeur[0];
                             $forfait=$valeur[2];
                             $frais = $valeur[3];
                             $idLigne=$valeur[4];
                             $mois=$valeur[6];
                             $total_ligne = $forfait * $frais;
         	?>

         <td><?php echo $unIdForfait ; ?></td><td><?php echo $frais ; ?></td><td><?php echo $forfait;?></td><td><?php echo $total_ligne;?></td>
       </tr>
     <?php
            $calculFrais = $calculFrais + $total_ligne;
     }

     ?>
      <tr>
        <td><h3>Total</h3></td><td colspan="3"><h3 style="text-align:center;"><?php echo $calculFrais;?></h3></td>
      </tr>

     </table>
     <br />
     <h2>Frais hors forfait</h2>
     <table>
       <tr>
        <td>Type</td><td>Montant en €</td></tr>


       <?php $requeteHF=  fraisHF($idConnexion, $unId);
       foreach ($requeteHF as $valeur) { ?>

       <tr>
         <?php
                             $fraisHF = $valeur[3];
                             $montantFHF = $valeur[4];

           ?>
         <td><?php echo $fraisHF ; ?></td><td><?php echo $montantFHF ; ?></td>
       </tr>

     <?php
          $calulTotal = $calcul + $calculFrais; }

          $requeteCalcul = calculfraisHF($idConnexion, $unId);
          foreach ($requeteCalcul as $cal) {
            $calcul=$cal[0];

          } ?>

          <tr>
            <td><h3>Total</h3></td><td><h3><?php echo $calcul;?></h3></td>
          </tr>
     </table>
     <br />
     <table>
       <tr>
         <td>Totaux</td><td><?php echo $calulTotal;?></td>
       </tr>
    </table>
     <form id="" action="" method="post">
    <div >
      <input type="hidden" name="etape" id="etape" value="validerConnexion" />
    <p>
      <label for="txtNbrJustificatif" >Nombre de justificatifs validés :</label>
      <input type="text" id="txtNbrJustificatif" name="txtNbrJustificatif"  />
    </p>
    <a>Mise en paiement</a>
    <?php
    require($repInclude . "_pied.inc.html");
    require($repInclude . "_fin.inc.php");
