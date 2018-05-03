<?php
    require_once('_bdGestionDonnees.lib.php');
    $db = connecterServeurBD();

    require('/fpdf181/fpdf.php');


    function fraisForfaitPdf($db){
      $requeteForfait = "select * from FraisForfait inner join LigneFraisForfait on LigneFraisForfait.idFraisForfait =  FraisForfait.idFrais";

      $result = $db->query($requeteForfait);
      if($result){
        $ligne= mysqli_fetch_all($result);
      }
      return $ligne;
    }



$unId = $_GET["id"];
$mois = date('m-y');
$requeteVisiteur=infoVisiteur($db, $unId);
  foreach ($requeteVisiteur as $value) {
  $nom=$value[0];
  $prenom=$value[1];}



class PDF extends FPDF
{
// En-tête
function Header()
{
    // Logo
   $this->Image('http://localhost/PPE/images/logo.jpg',10,6,30);
    // Police Arial gras 15
    $this->SetFont('Arial','B',15);
    // Décalage à droite
    $this->Cell(80);
    // Titre
    // Saut de ligne
    $this->Ln(20);
}


// Pied de page
   function Footer()
  {
      // Positionnement à 1,5 cm du bas
      $this->SetY(-15);
      // Police Arial italique 8
      $this->SetFont('Arial','I',8);
      // Numéro de page
      $txt_a_pdf = utf8_decode('Société GSB - Saint Denis');
      $this->Cell(0,0,$txt_a_pdf, 2,0,'C');
      $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
  }


  function LoadData($db, $unId){
   $requeteForfait=  fraisForfait($db);
         $calculFrais = 0;
         $data_table = array();

         $compteur_frais = 0;
         foreach ($requeteForfait as $valeur) {
            $data_table[$compteur_frais]['unIdForfait'] =$valeur[0];
            $data_table[$compteur_frais]['forfait']=$valeur[2];
            $data_table[$compteur_frais]['frais'] = $valeur[3];
            $mois=$valeur[6];
            if($data_table[$compteur_frais]['unIdForfait'] != 'KM'){
              $data_table[$compteur_frais]['total_ligne'] = $data_table[$compteur_frais]['forfait'] * $data_table[$compteur_frais]['frais'];

            }

            else{
              $bareme=calculKM($db, $unId);
              $data_table[$compteur_frais]['total_ligne'] = $bareme * $data_table[$compteur_frais]['frais'];
              $data_table[$compteur_frais]['forfait'] = $bareme;
            }

            $data_table[$compteur_frais]['calculFrais'] = $calculFrais + $data_table[$compteur_frais]['total_ligne'];
            $calculFrais = $data_table[$compteur_frais]['calculFrais'];

            $compteur_frais++;
        }
        return $data_table;
    }



    function LoadHF($db,$unId){

          $requeteHF=  fraisHF($db, $unId);
          $table=array();
          $compteurHF=0;
             foreach ($requeteHF as $valeurHF) {
                                   $table[$compteurHF]['fraisHF'] = $valeurHF[3];
                                   $table[$compteurHF]['$montantFHF'] = $valeurHF[4];
              $compteurHF++;

                 }
          return $table;
      }


    function BasicTable($header, $valeur)
    {
        // En-tête
        foreach($header as $col) {
            $this->Cell(40,7,$col,1);
        }
        $this->Ln();
        // Données
        foreach($valeur as $row)
        {
          unset($row['calculFrais']);

          foreach($row as $col){
            $this->Cell(40,6,$col,1);
          }
          $this->Ln();
        }
    }

    function BasicTable1($header1, $valeurHF)
    {
        // En-tête
        foreach($header1 as $col) {
            $this->Cell(40,7,$col,1);
        }
        $this->Ln();
        // Données
        foreach($valeurHF as $row)
        {

          foreach($row as $col){
            $this->Cell(40,6,$col,1);
          }
          $this->Ln();
        }
    }
}
$requeteCalcul = calculfraisHF($db, $unId);
foreach ($requeteCalcul as $cal) {
  $calcul=$cal[0];
  //$calulTotal = $calcul + $calculFrais;
}
// Instanciation de la classe dérivée
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',18);
$pdf->Cell(0,10,'Fiche de frais du mois de '.$mois. ' de '.$nom.' '.$prenom,2,0,'C');
$pdf->Ln(20);
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,10,utf8_decode('FRAIS FORFATISÉS'),2,0,'C');
$pdf->SetFont('Times','',12);
$pdf->Ln(20);

$header=array('Type',utf8_decode('Quantité'),'Forfait','Total ligne');
$valeur = $pdf->LoadData($db, $unId);
$last_ligne = end($valeur);
$total = $last_ligne['calculFrais'];
$pdf->BasicTable($header, $valeur);
$pdf->Ln(20);
$pdf->Cell(0,10,'Total : '. $total.' euros ',1);
$pdf->SetFont('Times','B',14);
$pdf->Ln(20);
$pdf->Cell(0,10,'FRAIS HORS FORFAIT',2,0,'C');
$pdf->Ln(20);
$pdf->SetFont('Times','',12);
$header1= array('Type', 'Montant en euros');
$valeurHF = $pdf->LoadHF($db, $unId);
$pdf->BasicTable1($header1, $valeurHF);
$pdf->Ln(20);
$pdf->Cell(0,10,'Total : '. $calcul.' euros ',1);
$pdf->Ln(20);
$pdf->SetFont('Times','B',16);
$totalAll = $total+$calcul;

$pdf->Cell(0,10,'Total : '. $totalAll.' euros ',1);

$dossierPDF= '/var/www/html/PPE/PDF_Fiche_Frais/'.$unId.'';
if(is_dir($dossierPDF) == FALSE) {
 mkdir($dossierPDF, 0777, true);
}
$dossier = '/var/www/html/PPE/PDF_Fiche_Frais/'.$unId."/";
$pdf->output($dossier.$nom.$prenom.' '.$mois.' Fiche de remboursement.pdf', 'F');


header('Location:cFicheFrais.php/?id='.$unId.'');


  ?>
