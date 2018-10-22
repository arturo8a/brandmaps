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
//Resumen QUARTER
$divisionalSummary = array();
//Información Relevante Empresa
$firmData = array();
//arreglo de datos global
$dataBrandmaps = array();



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
}


$pdf    = $parser->parseFile('pdf2.pdf');
 
$text = str_replace("	","",$pdf->getText());
$text = str_replace("&#34;","\"",$text); // &#34
// echo $text;
// die();


// Dividimos el documento en chunks
$text = preg_replace('/[\\n]?[\*]{77} \\n FIRM [\d]\: (.*?)INDUSTRY O \\n MARKETING RESEARCH FOR FIRM [\d], QUARTER (.*?)PAGE(.*?)[\*]{77} \\n \\n \\n/mis', "\r\r", $text);
$data = preg_split('/^ MARKETING RESEARCH STUDY #/mis',$text,-1, PREG_SPLIT_NO_EMPTY);

foreach($data as $chunk)
{
	$line = strtok($chunk, $newLine);
	$line = strtok($newLine);
	echo $chunk;
	continue;
	
	$datosHoja = explode(',',$line);
	if(count($datosHoja) > 0)
	{
		print_r($datosHoja);
		$title = trim($datosHoja[0]);
		$current_quarter = trim($datosHoja[2]);
		if(isZero($quarter))
		{
			$current_quarter = str_replace("QUARTER ","",substr($current_quarter,0,10));
			$quarter = $current_quarter;
		}
		echo $title;
		// switch($title)
		// {
			// case 'CURRENT PRODUCT OPERATING STATEMENT': CurrentProductOperatingStatement($chunk);break;
			// case 'CURRENT DIVISIONAL OPERATING STATEMENT': CurrentDivisionalOperatingStatement($chunk);break;
			// case 'CUMULATIVE DIVISIONAL OPERATING STATEMENT': CumulativeDivisionalOperatingStatement($chunk);break;
			// case 'DIVISIONAL BALANCE SHEET': DivisionalBalanceSheet($chunk);break;
			// case 'CASH FLOW ANALYSIS REPORT': CashFlowAnalysysReport($chunk);break;
			// case 'DETAILED VARIABLE COST CALCULATIONS': DetailedVariableCostCalculations($chunk);break;
			// case 'SALES FORECASTING ACCURACY REPORT': SalesForecastingAccuracyReport($chunk);break;
			// case 'MARKETING RESEARCH BILLINGS': MarketingResearchBillings($chunk);break;
			// case 'FINANCIAL AND OPERATING STATEMENT MESSAGES': FinancialAndOperatingStatementMessages($chunk);break;
			// case 'SPECIAL BRANDMAPS NOTICES': SpecialBrandmapsNotices($chunk);break;
		// }
		
		
	}else{
		echo "Error: No se encontraron datos en la hoja.\r\n";
	}
}

$dataBrandmaps['products'] = $products;
$dataBrandmaps['divisionalSummary'] = $divisionalSummary;
$dataBrandmaps['firmData'] = $firmData;
echo "<pre>";
// print_r($dataBrandmaps);
echo "</pre>";


?>