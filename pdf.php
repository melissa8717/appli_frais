
  class myFPDF extends FPDF {



    var $db;



    function myFPDF ($orientation='P',$unit='mm',$format='A4',$db){

      parent::FPDF($orientation='P',$unit='mm',$format='A4');

      $this->db = $db;

    }
    $pdf = new myFPDF;
    //création d'une instance de classe, P pour portrait, pt pour point en unité de mesure, A4 pour le format
    $pdf ->Open(); //indique que l'on crée un fichier PDF
    $pdf ->AddPage(); //permet d'ajouter une page
    $pdf ->SetFont('Helvetica','B',11); //choix de la police
    $pdf ->SetXY(330, 25); // indique des coordonnées pourplacer un élément
    $pdf ->Cell(190,50,"texte dans le cadre",0,0, "L");
    //création d'une cellule
    $pdf ->Text(498,20, "texte"); //insertion d'une ligne de texte
    $pdf ->Output(); //génère le PDF et l'affiche
