<?php

function isZero($number)
{
	return ($number == 0);
}

function ifNullArray($var)
{
	if(!isset($var)) $var = array();
}

function removeComma($number)
{
	return str_replace(",","",$number);
}

function evaluateYesNo($string)
{
	if($string == 'YES')
		return 1;
	else
		return 0;
}

?>