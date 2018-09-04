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
    ?>
        <h2>
    <?php
            echo $nom . " " . $prenom ;
    ?>
        </h2>
        <h3>Comptable</h3>
    <?php
       }
    ?>
      </div>
<?php
  if (estVisiteurConnecte() ) {
?>
        <ul id="menuList">
           <li class="smenu">
              <a href="cValideFrais.php" title="Page d'accueil">Accueil</a>
           </li>
		   <li class="smenu">
              <a href="cValideFrais.php" title="Voir tous les visiteurs">Voir tous les visiteurs</a>
           </li>
		   <li class="smenu">
              <a href="cConsultFrais.php" title="Voir tous les visiteurs">Voir les forfaits des frais</a>
           </li>
           <li class="smenu">
              <a href="cSeDeconnecter.php" title="Se déconnecter">Se déconnecter</a>
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
