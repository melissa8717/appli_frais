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

  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaireComptable.inc.php");

          if ( $etape == "validerConnexionCompta" )
          {
              if ( nbErreurs($tabErreurs) > 0 )
              {
                echo toStringErreurs($tabErreurs);
              }
          }
?>
<div id="contenu">

<h2>Frais forfaitisés</h2>

     <table>

       <tr>
        <td>Type</td><td>Libellé</td><td>Montant</td><td>Modifier</td>
      </tr>

       <?php $requeteForfait= listeFrais($idConnexion);
	   

       foreach ($requeteForfait as $valeur ) { ?>

       <tr>
         <?php
                             $unIdForfait =$valeur[0];
							 $libelle = $valeur[1];
							 $montant = $valeur[2];
                        
         	?>

         <td><?php echo $unIdForfait ; ?></td><td><?php echo $libelle ; ?></td><td><?php echo $montant;?></td><?php echo"".'<td><a href="cModifierFrais.php/?id='.$unIdForfait.'">Modifier ce frais</a></td>';?>
     </tr>
     <?php
     }

     ?>
     

     </table>
     <br />
     
     </table>
     <br />
   
 <form id="" action="" method="post">
    <div >
      <input type="hidden" name="etape" id="etape" value="validerConnexion" />
 <br />

  <div>
</form>
    <?php
    require($repInclude . "_pied.inc.html");
    require($repInclude . "_fin.inc.php");
