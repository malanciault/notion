<?php
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        //$img_file = FCPATH .'assets/img/Planetair_certificat-001-2019.jpg';
        //$this->Image($img_file, 0, 0, 558, 300, '', '', '', 2, 300, '', false, false, 0, true);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages() . ' ' . 'http://app.illuxi.com', 0, 2, 'C', 0, '', 0, false, 'T', 'M');
    }
}

class Pdf extends MY_Controller {

	private $pdf;

	public function __construct() {
		parent::__construct();

		//define ('K_PATH_IMAGES', FCPATH . 'assets/img/');
		//define ('PDF_HEADER_LOGO', 'illuxi-logo-couleurs-126.png');
		$this->pdf = new MYPDF('L', PDF_UNIT, 'USLETTER', true, 'UTF-8', false);
		// set document information
		$this->pdf->SetCreator(PDF_CREATOR);
		$this->pdf->SetAuthor('Planetair');
		$this->pdf->SetSubject('');
		$this->pdf->SetKeywords('');

		// set default header data
		//$this->pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));

		$this->pdf->SetHeaderData(false, false, '', '');
		//$this->pdf->setFooterData(array(0,64,0), array(0,64,128));

		// set header and footer fonts
		$this->pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);



		// set auto page breaks
		$this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		    require_once(dirname(__FILE__).'/lang/eng.php');
		    $this->pdf->setLanguageArray($l);
		}

		// ---------------------------------------------------------

		// set default font subsetting mode
		$this->pdf->setFontSubsetting(true);

		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$this->pdf->SetFont('helvetica', '', 10, '', true);
	}

	private function writeHTMLCell($w, $h='', $x='', $y='', $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true) {
		if (is_array($w)) {
			$wi = isset($w['width']) ? $w['width'] : '';
			$h = isset($w['height']) ? $w['height'] : '';
			$x = isset($w['x']) ? $w['x'] : '';
			$y = isset($w['y']) ? $w['y'] : '';
			$html = isset($w['html']) ? $w['html'] : '';
			$border = isset($w['border']) ? $w['border'] : 0;
			$ln = isset($w['ln']) ? $w['ln'] : 0;
			$fill = isset($w['fill']) ? $w['fill'] : 0;
			$reseth = isset($w['reseth']) ? $w['reseth'] : true;
			$align = isset($w['align']) ? $w['align'] : '';
			$autopadding = isset($w['autopadding']) ? $w['autopadding'] : true;
			$valign = isset($w['valign']) ? $w['valign'] : 'T';
			$stretch = isset($w['stretch']) ? $w['stretch'] : 0;
			$maxh = isset($w['maxh']) ? $w['maxh'] : 0;
			$fitcell = isset($w['fitcell']) ? $w['fitcell'] : false;
		} else {
			$wi = $w;
			$stretch = 0;
			$maxh = 0;
			$fitcell = false;
			$valign = 'T';
		}
		
		$this->pdf->MultiCell($wi, $h, $html, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, true, $autopadding, $maxh, $valign, $fitcell=false);
	}

	function padding($h=5) {
		$this->writeHTMLCell(array(
			'width' 		=> 180, 
			'height' 		=> $h,
			'x' 			=> '',
			'y' 			=> '',
			'html' 			=> '',
			'border'		=> 0,
			'ln' 			=> 1,
			'fill' 			=> 0,
			'reseth' 		=> true,
			'align' 		=> 'C',
			'autopadding'	=> true,
			)
		); 
	}


	public function certificate() {
        // Add a page
        // This method has several options, check the source code documentation for more information.
        $this->pdf->SetTitle(__("Certificat"));
        $this->pdf->AddPage('L', 'A4');
        
        $order = $this->order_model->get_by_hash($this->uri->segment(3));

        $img_file = FCPATH . __('assets/img/Planetair_certificat-001-2019.jpg');
        $this->pdf->Image($img_file, 44, 24, 0, 1000, '', '', '',true, 300, '', false, false, 0);

        $this->pdf->SetTextColor(0, 69, 124);

        $leftpos = 84;
        $this->writeHTMLCell(array(
                'width' 		=> 80,
                'height' 		=> '',
                'x' 			=> $leftpos,
                'y' 			=> 62,
                'html' 			=> '<h2 style="font-weight: 200">' . __("PLANETAIR certifie que l'empreinte climatique de") . '</h2>',
                'border'		=> 0,
                'ln' 			=> 1,
                'fill' 			=> 0,
                'reseth' 		=> false,
                'align' 		=> 'L',
                'autopadding'	=> true,
            )
        );

        $this->writeHTMLCell(array(
                'width' 		=> 180,
                'height' 		=> '',
                'x' 			=> $leftpos,
                'y' 			=> '',
                'html' 			=> '<h1>' . $order['order_text'] . '</h1>',
                'border'		=> 0,
                'ln' 			=> 1,
                'fill' 			=> 0,
                'reseth' 		=> true,
                'align' 		=> 'L',
                'autopadding'	=> true,
            )
        );

        $this->writeHTMLCell(array(
                'width' 		=> 70,
                'height' 		=> '',
                'x' 			=> $leftpos,
                'y' 			=> '',
                'html' 			=> '<h2 style="font-weight: 200">' . __("a été réduite par le biais de crédits-carbone «Gold Standard»") . '</h2>',
                'border'		=> 0,
                'ln' 			=> 1,
                'fill' 			=> 0,
                'reseth' 		=> false,
                'align' 		=> 'L',
                'autopadding'	=> true,
            )
        );

        $this->writeHTMLCell(array(
                'width' 		=> 100,
                'height' 		=> '',
                'x' 			=> $leftpos,
                'y' 			=> 105,
                'html' 			=> '<h3 style="font-weight: 200">' . __("représentant") . '</h3>',
                'border'		=> 0,
                'ln' 			=> 1,
                'fill' 			=> 0,
                'reseth' 		=> true,
                'align' 		=> 'l',
                'autopadding'	=> true,
            )
        );

        $this->writeHTMLCell(array(
                'width' 		=> 80,
                'height' 		=> '',
                'x' 			=> $leftpos,
                'y' 			=> '',
                'html' 			=> '<h1 style="font-size: 40pt">' . format_decimal($order['order_co2']) . '</h1>',
                'border'		=> 0,
                'ln' 			=> 1,
                'fill' 			=> 0,
                'reseth' 		=> true,
                'align' 		=> 'l',
                'autopadding'	=> true,
            )
        );
        $certificate_number = 'C' . date_format(date_create($order['order_date']),"Ymd") . '-' . $order['order_id'];
        $this->writeHTMLCell(array(
                'width' 		=> 80,
                'height' 		=> '',
                'x' 			=> $leftpos,
                'y' 			=> 133,
                'html' 			=> '<div style="font-size: 8pt"> ' . __("Certificat") . ' #' . $certificate_number . __(" - Émis le ") . date_format(date_create($order['order_date']),"Y-m-d") . ' </div>',
                'border'		=> 0,
                'ln' 			=> 1,
                'fill' 			=> 0,
                'reseth' 		=> true,
                'align' 		=> 'l',
                'autopadding'	=> true,
            )
        );
        $this->writeHTMLCell(array(
                'width' 		=> 120,
                'height' 		=> '',
                'x' 			=> $leftpos,
                'y' 			=> '',
                'html' 			=> '<div style="font-size: 8pt"> ' . __("Ce certificat n'a aucune valeur monétaire et ne peut ni être échangé, ni transféré") . '.</div>',
                'border'		=> 0,
                'ln' 			=> 1,
                'fill' 			=> 0,
                'reseth' 		=> true,
                'align' 		=> 'l',
                'autopadding'	=> true,
            )
        );

		// move pointer to last page
		$this->pdf->lastPage();

		// ---------------------------------------------------------

		//Close and output PDF document
		$file_name = 'planetair-certificat-' . $certificate_number;
		$this->pdf->Output($file_name . '.pdf', 'D');



		// Print text using writeHTMLCell()
		//$this->pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.

	//	$this->pdf->Output('example_001.pdf', 'D');			
    }
    
    
    public function receipt(){
        $order = $this->order_model->get_by_hash($this->uri->segment(3));
        $project = $this->project_model->get($order["order_project_id"]);

        // x($project);

        $this->pdf->SetTitle(__("Reçu Planetair"));
        $this->pdf->AddPage('P', 'A4');

        $img_file = FCPATH . __('assets/img/planetair-receipt-template.jpg');
        $this->pdf->Image($img_file, 10, 0, 0, 1000, '', '', '',true, 300, '', false, false, 0);

        $leftMargin = 20;
        $topMargin = 50;
        $secondBoxMargin =  $topMargin + 20;
        $thirdBoxMargin =  $secondBoxMargin + 55;
        $boxWidth = 175;
        $boxHeight = 170;

        $marginFirstColumn = $leftMargin + 9; 

        // Text "Recu"
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->writeHTMLCell(array(
            'width' 		=> $boxWidth / 2,
            'height' 		=> '',
            'x' 			=> $marginFirstColumn,
            'y' 			=> $topMargin + 1,
            'html' 			=> __("Reçu"),
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );

        
        // Text "Nom client"
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('helvetica', '', 10);
        $this->writeHTMLCell(array(
            'width' 		=> $boxWidth / 2,
            'height' 		=> '',
            'x' 			=> $marginFirstColumn,
            'y' 			=> $topMargin + 15,
            'html' 			=> $order['firstname'] . " " . $order['lastname'],
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );

        // Text "Couriel client"
        $this->writeHTMLCell(array(
            'width' 		=> $boxWidth / 2,
            'height' 		=> '',
            'x' 			=> $marginFirstColumn,
            'y' 			=> $topMargin + 20,
            'html' 			=> $order['email'],
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );
        
        // Text "Paiement en ligne"
        $this->pdf->SetFont('helvetica', 'B', 9);
        $this->writeHTMLCell(array(
            'width' 		=> $boxWidth / 2,
            'height' 		=> '',
            'x' 			=> $boxWidth / 2 + 10,
            'y' 			=> $topMargin + 12,
            'html' 			=> __("Paiement en ligne"),
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );
        
        // Text "Numero de transaction"
        $this->pdf->SetFont('helvetica', 'B', 9);
        $this->writeHTMLCell(array(
            'width' 		=> $boxWidth / 2,
            'height' 		=> '',
            'x' 			=> $boxWidth / 2 + 10,
            'y' 			=> $topMargin + 17,
            'html' 			=> __("Numéro de transaction: "),
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );
        
        
        //Numero de transaction
        $this->pdf->SetFont('helvetica', '', 9);
        $this->writeHTMLCell(array(
            'width' 		=> $boxWidth / 2,
            'height' 		=> '',
            'x' 			=> $boxWidth / 2,
            'y' 			=> $topMargin + 17,
            'html' 			=> $order["order_txn_id"],
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'R',
            'autopadding'	=> true,
            )
        );
        
        // Text "Date de transaction"
        $this->pdf->SetFont('helvetica', 'B', 9);
        $this->writeHTMLCell(array(
            'width' 		=> $boxWidth / 2,
            'height' 		=> '',
            'x' 			=> $boxWidth / 2 + 10,
            'y' 			=> $topMargin + 22,
            'html' 			=> __("Date de transaction: "),
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );
        
        //Date de transaction
        $date = substr($order["order_date"], 0, -9);
        $this->pdf->SetFont('helvetica', '', 9);
        $this->writeHTMLCell(array(
            'width' 		=> $boxWidth / 2,
            'height' 		=> '',
            'x' 			=> $boxWidth / 2,
            'y' 			=> $topMargin + 22,
            'html' 			=> $date,
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'R',
            'autopadding'	=> true,
            )
        );
        
        //Text "Compensation-carbone"
        $this->pdf->SetFont('helvetica', '', 9);
        $this->writeHTMLCell(array(
            'width' 		=> $boxWidth / 2,
            'height' 		=> '',
            'x' 			=> $marginFirstColumn,
            'y' 			=> $secondBoxMargin + 22,
            'html' 			=> __("Compensation-carbone"),
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );
 
        //Text "Portefeuille / projet"
        $this->pdf->SetFont('helvetica', 'B', 9);
        $this->writeHTMLCell(array(
            'width' 		=> '',
            'height' 		=> '',
            'x' 			=> $marginFirstColumn + 15,
            'y' 			=> $secondBoxMargin + 31,
            'html' 			=> __("Portefeuille / projet"),
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );

        //Text "$/tonne"
        $this->pdf->SetFont('helvetica', 'B', 9);
        $this->writeHTMLCell(array(
            'width' 		=> '',
            'height' 		=> '',
            'x' 			=> $marginFirstColumn + 65,
            'y' 			=> $secondBoxMargin + 31,
            'html' 			=> __("| $/tonne |"),
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );

        //Text "CO2 compensé"
        $this->pdf->SetFont('helvetica', 'B', 9);
        $this->writeHTMLCell(array(
            'width' 		=> '',
            'height' 		=> '',
            'x' 			=> $marginFirstColumn + 81,
            'y' 			=> $secondBoxMargin + 31,
            'html' 			=> __(" CO<sub>2</sub> compensé |"),
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );

        //Text "Montant de la contribution"
        $this->pdf->SetFont('helvetica', 'B', 9);
        $this->writeHTMLCell(array(
            'width' 		=> '',
            'height' 		=> '',
            'x' 			=> $marginFirstColumn + 106,
            'y' 			=> $secondBoxMargin + 31,
            'html' 		=> __("Montant de la contribution"),
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );

        //"Portefeuille / projet"
        $this->pdf->SetFont('helvetica', '', 9);
        $this->writeHTMLCell(array(
            'width' 		=> 50,
            'height' 		=> '',
            'x' 			=> $marginFirstColumn + 15,
            'y' 			=> $secondBoxMargin + 37,
            'html' 			=> $project["project_i18n_title"],
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );


        if($this->i18n->current() == "french"){
            $project_price = str_replace(".", ",", $project["project_price"]);
            $orderCo2 = str_replace(".", ",", $order['order_co2']);
        }
        else{
            $project_price = $project["project_price"];
            $orderCo2 = $order['order_co2'];

        }

        //"$/tonne"
        $this->pdf->SetFont('helvetica', '', 9);
        $this->writeHTMLCell(array(
            'width' 		=> '',
            'height' 		=> '',
            'x' 			=> $marginFirstColumn + 69,
            'y' 			=> $secondBoxMargin + 37,
            'html' 			=> $project_price,
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );

        //"CO2 compense"
        $this->pdf->SetFont('helvetica', '', 9);
        $this->writeHTMLCell(array(
            'width' 		=> '',
            'height' 		=> '',
            'x' 			=> $marginFirstColumn + 82,
            'y' 			=> $secondBoxMargin + 37,
            'html' 		    => $orderCo2 . " tonnes",
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );

        //"CO2 compense"
        $this->pdf->SetFont('helvetica', '', 9);
        $this->writeHTMLCell(array(
            'width' 		=> $boxWidth - 12,
            'height' 		=> '',
            'x' 			=> '',
            'y' 			=> $secondBoxMargin + 37,
            'html' 		    => format_dollar((float)$order["order_total"]),
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'R',
            'autopadding'	=> true,
            )
        );

        
        //Text infos Planetair
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->SetTextColor(244, 153, 31);
        $this->writeHTMLCell(array(
            'width' 		=> '',
            'height' 		=> '',
            'x' 			=> $marginFirstColumn,
            'y' 			=> $thirdBoxMargin,
            'html' 		    => "Planetair",
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );

        //Text infos Planetair
        $this->pdf->SetFont('helvetica', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->writeHTMLCell(array(
            'width' 		=> '',
            'height' 		=> '',
            'x' 			=> $marginFirstColumn,
            'y' 			=> $thirdBoxMargin + 5,
            'html' 		    => "info@planetair.ca",
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'l',
            'autopadding'	=> true,
            )
        );

        //Text infos Planetair
        $this->pdf->SetFont('helvetica', '', 9);
        $this->writeHTMLCell(array(
            'width' 		=> $boxWidth - 20,
            'height' 		=> '',
            'x' 			=> '',
            'y' 			=> $thirdBoxMargin + 2,
            'html' 		    => __("Merci de contribuer à la lutte aux changements climatiques !"),
            'border'		=> '',
            'ln' 			=> 1,
            'fill' 			=> '',
            'reseth' 		=> true,
            'align' 		=> 'R',
            'autopadding'	=> true,
            )
        );
 

        $this->pdf->lastPage();
		$file_name = __('recu-Planetair');
		$this->pdf->Output($file_name . '.pdf', 'D');
    } 

  
}