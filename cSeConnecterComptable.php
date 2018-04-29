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

  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaireComptable.inc.php");
  
?>

<!-- Division pour le contenu principal -->
    <div id="contenu">

      <h2 id="frmConnexion">Identification comptable</h2>

<?php
          if ( $etape == "validerConnexionCompta" ) 
          {
              if ( nbErreurs($tabErreurs) > 0 ) 
              {
                echo toStringErreurs($tabErreurs);
              }
          }
?>               
      <form id="frmConnexion" action="" method="post">
      <div class="corpsForm">
        <input type="hidden" name="etape" id="etape" value="validerConnexionCompta" />
      <p>
        <label for="txtLoginCompta" accesskey="n">* Login : </label>
        <input type="text" id="txtLogin" name="txtLoginCompta" maxlength="20" size="15" value="" title="Entrez votre login" />
      </p>
      <p>
        <label for="txtMdpCompta" accesskey="m">* Mot de passe : </label>
        <input type="password" id="txtMdp" name="txtMdpCompta" maxlength="8" size="15" value=""  title="Entrez votre mot de passe"/>
      </p>
      </div>
      <div class="piedForm">
      <p>
        <input type="submit" id="ok" value="Valider" />
        <input type="reset" id="annuler" value="Effacer" />
      </p> 
      </div>
      </form>
    </div>

<?php
    require($repInclude . "_pied.inc.html");
    require($repInclude . "_fin.inc.php");
?>