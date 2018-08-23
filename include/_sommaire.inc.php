<?php
/**
 * Contient la division pour le sommaire, sujet à des variations suivant la
 * connexion ou non d'un utilisateur, et dans l'avenir, suivant le type de cet utilisateur
 * @todo  RAS
 */

?>
    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    <?php
      if (estVisiteurConnecte() ) {
          $idUser = obtenirIdUserConnecte() ;
          $lgUser = obtenirDetailVisiteur($idConnexion, $idUser);
          $nom = $lgUser['nom'];
          $prenom = $lgUser['prenom'];
          $idUser=$lgUser['id']

    ?>
        <h2>
    <?php
            echo $nom . " " . $prenom ;
    ?>
        </h2>
        <h3>Visiteur médical</h3>
    <?php
       }
    ?>
      </div>
<?php
  if (estVisiteurConnecte() ) {
?>
        <ul id="menuList">
           <li class="smenu">
              <a href="cAccueil.php" title="Page d'accueil">Accueil</a>
           </li>
           <li class="smenu">
              <a href="cSeDeconnecter.php" title="Se déconnecter">Se déconnecter</a>
           </li>
        
           <li class="smenu">
              <?php echo'<a href="cConsultVehicule.php/?id='.$idUser.'"';?>  title="Consultation de mon véhicule">Mon véhicule</a>
           </li>
           <li class="smenu">
              <a href="cSaisieFicheFrais.php" title="Saisie fiche de frais du mois courant">Saisie fiche de frais</a>
           </li>
           <li class="smenu">
              <a href="cConsultFichesFrais.php" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
           </li>
           <li class="smenu">
              <a href="cFicheRemboursement.php" title="Consultation de mes fiches de remboursement">Mes fiches de remboursement</a>
           </li>
         </ul>
        <?php
          // affichage des éventuelles erreurs déjà détectées
          if ( nbErreurs($tabErreurs) > 0 ) {
              echo toStringErreurs($tabErreurs) ;
          }
  }
        ?>
    </div>
