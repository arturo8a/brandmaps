<?php
//<!--DECLARACIONES GLOBALES -->
//Habilitar el reemplazo de la Empresa (si esta deshabilitado se lee desde el PDF);
$forceFirm = false;
//Número de Empresa que será reemplazado
$forcedFirmNumber = 4;
//array de datos de productos
$products = array();
//trimestre procesando
$quarter = 0;
//carácter nueva linea
$newLine = "\r\n";
//empresa
$firm = 0;
//regiones
$regions = array();



include("vendor/autoload.php");
include("include/util.php");
include("include/financial_results_parser.php");



$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile('pdf.pdf');
 
$text = str_replace("	","",$pdf->getText());
$text = str_replace("&#34;","\"",$text); // &#34



// Dividimos el documento en chunks

$data = preg_split('/^\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\* $/mis',$text,-1, PREG_SPLIT_NO_EMPTY);
foreach($data as $chunk)
{
	$line = strtok($chunk, $newLine);
	$line = strtok($newLine);
	
	$datosHoja = explode(',',$line);
	if(count($datosHoja) > 0)
	{
		$title = trim($datosHoja[0]);
		$current_quarter = trim($datosHoja[2]);
		if(isZero($quarter))
		{
			$current_quarter = str_replace("QUARTER ","",substr($current_quarter,0,10));
			$quarter = $current_quarter;
		}
		
		switch($title)
		{
			case 'CURRENT PRODUCT OPERATING STATEMENT': CurrentProductOperatingStatement($chunk);break;
			case 'CURRENT DIVISIONAL OPERATING STATEMENT': CurrentDivisionalOperatingStatement($chunk);break;
			case 'CUMULATIVE DIVISIONAL OPERATING STATEMENT': CumulativeDivisionalOperatingStatement($chunk);break;
			case 'DIVISIONAL BALANCE SHEET': DivisionalBalanceSheet($chunk);break;
			case 'CASH FLOW ANALYSIS REPORT': CashFlowAnalysysReport($chunk);break;
			case 'DETAILED VARIABLE COST CALCULATIONS': DetailedVariableCostCalculations($chunk);break;
			case 'SALES FORECASTING ACCURACY REPORT': SalesForecastingAccuracyReport($chunk);break;
			case 'MARKETING RESEARCH BILLINGS': MarketingResearchBillings($chunk);break;
			case 'FINANCIAL AND OPERATING STATEMENT MESSAGES': FinancialAndOperatingStatementMessages($chunk);break;
			case 'SPECIAL BRANDMAPS NOTICES': SpecialBrandmapsNotices($chunk);break;
		}
		
		
	}else{
		echo "Error: No se encontraron datos en la hoja.\r\n";
	}
	
	echo $line."\r\n";
}

print_r($products);
echo count($data);
// print_r($data);


// include ('PdfToText.phpclass') ;
// $pdf 	=  new PdfToText ( 'pdf2.pdf' );
// echo $pdf -> Text;
?>