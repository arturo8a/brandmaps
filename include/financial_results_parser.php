<?php
//CURRENT PRODUCT OPERATING STATEMENT
function CurrentProductOperatingStatement($stringToProcess)
{
	global $products,$newLine,$firm,$regions;
	
	$linesWithSimilarLogic = array(
		9
		,10
		,11
		,13
		,14
		,16
		,17
		,18
		,19
		,20
		,21
		,23
		,26
		,27
		,28
		,29
		,30
		,31
		,33
		,38
		,41
		,42
		,43
		,46
	);
	
	$fieldNameLines = array(
		'9' => 'active',
		'10' => 'sales',
		'11' => 'unfilled_orders',
		'13' => 'price',
		'14' => 'dealer_rebates',
		'16' => 'revenue',
		'17' => 'product_costs',
		'18' => 'rebates_offered',
		'19' => 'sales_commissions',
		'20' => 'transportation',
		'21' => 'duties_tariffs',
		'23' => 'cross_margin',
		'26' => 'fixcost_administrat',
		'27' => 'fixcost_advertising',
		'28' => 'fixcost_promotion',
		'29' => 'fixcost_sales_salaries',
		'30' => 'fixcost_sales_oh',
		'31' => 'total_fixed_costs',
		'33' => 'operating_income',
		'38' => 'promotional_type',
		'41' => 'salesforce_size',
		'42' => 'salesforce_time_alloc',
		'43' => 'salesforce_effort',
		'46' => 'sales_volume_forecast'
	);
	
	$pageContainsAllRegion = false;
	
	/* Datos básicos */
	$firm = 0;
	$product = 0;
	
	$line = strtok($stringToProcess, $newLine);
	$lineNumber = 0;

	while ($line !== false) {

		$lineNumber++;
		//titulo
		if($lineNumber == 2)
		{
			$data = explode(",",trim($line));
			$firmProduct = explode("-",str_replace("PRODUCT ","",$data[1]));
			$firmNumber = trim($firmProduct[0]);
			$productNumber = trim($firmProduct[1]);
			
			/* Variable global empresa */
			$firm = $firmNumber;
		}
		// Cabecera de regiones
		if($lineNumber == 6)
		{
			if(strpos($line,"REGIONS") !== false)
			{
				//Es la primera página (incluye totales)
				$pageContainsAllRegion = true;
				$total = trim(substr($line,19,12));
				$region1 = trim(substr($line,32,12));
				$region2 = trim(substr($line,44,12));
				$region3 = trim(substr($line,56,12));
				$region4 = trim(substr($line,68,12));
				if(!isset($regions[1]) || !isset($regions[2]) || !isset($regions[3]) || !isset($regions[4]))
				{
					$regions[1] = $region1;
					$regions[2] = $region2;
					$regions[3] = $region3;
					$regions[4] = $region4;
				}
				
			}else{
				// Es una página con solo regiones
				
				$region5 = trim(substr($line,32,12));
				$region6 = trim(substr($line,44,12));
				$region7 = trim(substr($line,56,12));
				$region8 = trim(substr($line,68,12));
				
				if(!isset($regions[5]) || !isset($regions[6]) || !isset($regions[7]) || !isset($regions[8]))
				{
					if(trim($region5) != '')
						$regions[5] = $region5;
					if(trim($region6) != '')
						$regions[6] = $region6;
					if(trim($region7) != '')
						$regions[7] = $region7;
					if(trim($region8) != '')
						$regions[8] = $region8;
				}
				
			}
		}
		
		if(in_array($lineNumber,$linesWithSimilarLogic))
		{
			$fieldName = $fieldNameLines[$lineNumber];
			if($pageContainsAllRegion)
			{
				$total = trim(substr($line,19,12));
				$region1 = trim(substr($line,32,12));
				$region2 = trim(substr($line,44,12));
				$region3 = trim(substr($line,56,12));
				$region4 = trim(substr($line,68,12));
				
				if(trim($total) != '')
				{
					$products["$firmNumber-$productNumber"]['results_per_region'][0][$fieldName] = $total;
				}
				
				if(trim($region1) != '')
				{
					$products["$firmNumber-$productNumber"]['results_per_region'][1][$fieldName] = $region1;
				}
				if(trim($region2) != '')
				{
					$products["$firmNumber-$productNumber"]['results_per_region'][2][$fieldName] = $region2;
				}
				if(trim($region3) != '')
				{
					$products["$firmNumber-$productNumber"]['results_per_region'][3][$fieldName] = $region3;
				}
				if(trim($region4) != '')
				{
					$products["$firmNumber-$productNumber"]['results_per_region'][4][$fieldName] = $region4;
				}
				
			}else{
				
				$region5 = trim(substr($line,32,12));
				$region6 = trim(substr($line,44,12));
				$region7 = trim(substr($line,56,12));
				$region8 = trim(substr($line,68,12));

				if(trim($region5) != '')
				{
					$products["$firmNumber-$productNumber"]['results_per_region'][5][$fieldName] = $region5;
				}
				if(trim($region6) != '')
				{
					$products["$firmNumber-$productNumber"]['results_per_region'][6][$fieldName] = $region6;
				}
				if(trim($region7) != '')
				{
					$products["$firmNumber-$productNumber"]['results_per_region'][7][$fieldName] = $region7;
				}
				if(trim($region8) != '')
				{
					$products["$firmNumber-$productNumber"]['results_per_region'][8][$fieldName] = $region8;
				}
			}
		}
		
		
		// Composición del producto
		if($lineNumber == 48)
		{
			$productComposition = trim(str_replace("PRODUCT COMPOSITION","",$line));
			$products["$firmNumber-$productNumber"]['composition'] = $productComposition;
		}
		
		// Pedido de producción
		if($lineNumber == 50)
		{
			$productProduction = trim(str_replace("PRODUCTION ORDER (Units)","",$line));
			$products["$firmNumber-$productNumber"]['production_order'] = $productProduction;
		}
		
		// Investigación y Desarrollo
		if($lineNumber == 52)
		{
			$productResearch = trim(str_replace("RESEARCH & DEVELOPMENT","",$line));
			$products["$firmNumber-$productNumber"]['research_development'] = $productResearch;
		}

		$line = strtok( $newLine );
	}
}

function CurrentDivisionalOperatingStatement($stringToProcess)
{
	global $firmData,$divisionalSummary,$newLine,$firm,$regions;
	$line = strtok($stringToProcess, $newLine);
	$lineNumber = 0;

	$linesWithSimilarLogic = array(
		9
		,10
		,12
		,13
		,15
		,16
		,17
		,18
		,19
		,20
		,22
		,25
		,26
		,27
		,28
		,29
		,30
		,31
		,32
		,33
		,34
		,35
		,36
		,37
		,38
		,39
		,40
		,41
		,42
		,44
		,46
		,47
		,49
	);
	
	$fieldNameLines = array(
		'9' => 'sales_units',
		'10' => 'unfilled_orders',
		'12' => 'price',
		'13' => 'dealer_rebates',
		'15' => 'revenue',
		'16' => 'product_costs',
		'17' => 'rebates_offered',
		'18' => 'sales_commissions',
		'19' => 'transportation',
		'20' => 'duties_tariffs',
		'22' => 'cross_margin',
		'25' => 'fixcost_administrat',
		'26' => 'fixcost_advertising',
		'27' => 'fixcost_consulting_fees',
		'28' => 'fixcost_corporate_oh',
		'29' => 'fixcost_depreciation',
		'30' => 'fixcost_disposal_sales',
		'31' => 'fixcost_energ_premiums',
		'32' => 'fixcost_introductions',
		'33' => 'fixcost_inventory_charg',
		'34' => 'fixcost_market_research',
		'35' => 'fixcost_prod_order_cost',
		'36' => 'fixcost_promotion',
		'37' => 'fixcost_reformulations',
		'38' => 'fixcost_research_development',
		'39' => 'fixcost_sales_expenses',
		'40' => 'fixcost_sales_salaries',
		'41' => 'fixcost_sales_oh',
		'42' => 'total_fixed_costs',
		'44' => 'operating_income',
		'46' => 'non_operat_income',
		'47' => 'less_taxes',
		'49' => 'net_income'
	);   
	
	while ($line !== false) {
		$lineNumber++;
		// echo "Linea $lineNumber<br>\n";
		
		
		if(in_array($lineNumber,$linesWithSimilarLogic))
		{
			$fieldName = $fieldNameLines[$lineNumber];
			$lineValue = trim(substr($line,19,12));
			$divisionalSummary[$fieldName] = $lineValue;
			
			if($lineNumber == 9)
			{
				$firmData['sales_units'] = $lineValue;
			}
			
			if($lineNumber == 49)
			{
				$firmData['net_income'] = $lineValue;
			}
		}
		
		
		$line = strtok( $newLine );
		
		// echo $line;
	}

}

function CumulativeDivisionalOperatingStatement($stringToProcess)
{
	
}

function DivisionalBalanceSheet($stringToProcess)
{
	global $products,$firmData,$newLine,$firm,$regions;
	$line = strtok($stringToProcess, $newLine);
	$lineNumber = 0;
	
	while ($line !== false) {
		$lineNumber++;
		//echo "Linea $lineNumber<br>\n";
		
		if($lineNumber == 7)
		{
			$firmData['cash'] = trim(str_replace("CASH","",$line));
		}
		
		if($lineNumber == 10)
		{
			$products['4-1']['stock'] = trim(substr($line,23,10));
		}
		
		if($lineNumber == 11)
		{
			$products['4-2']['stock'] = trim(substr($line,23,10));
		}
		
		if($lineNumber == 12)
		{
			$products['4-3']['stock'] = trim(substr($line,23,10));
		}
		
		if($lineNumber == 13)
		{
			$products['4-4']['stock'] = trim(substr($line,23,10));
		}
		
		if($lineNumber == 15)
		{
			$firmData['plant_capacity'] = trim(substr($line,52,10));
			$firmData['plant_value'] = trim(substr($line,63,15));
		}
		
		if($lineNumber == 17)
		{
			$firmData['total_assets'] = trim(str_replace("TOTAL ASSETS","",$line));
		}
		
		if($lineNumber == 21)
		{
			$firmData['loans'] = trim(str_replace("LOANS","",$line));
		}
		
		
		$line = strtok( $newLine );
		
		//echo $line;
	}
}

function CashFlowAnalysysReport($stringToProcess)
{
	
}

function DetailedVariableCostCalculations($stringToProcess)
{
	
}

function SalesForecastingAccuracyReport($stringToProcess)
{
	
}

function MarketingResearchBillings($stringToProcess)
{
	
}

function FinancialAndOperatingStatementMessages($stringToProcess)
{
	
}

function SpecialBrandmapsNotices($stringToProcess)
{
	
}

?>