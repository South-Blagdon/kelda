<?php
// Enable error reporting
error_reporting(E_ALL);

// Display errors on the screen
ini_set('display_errors', '1');

require_once __DIR__ . '/vendor/autoload.php';

use voku\tidy\HtmlTidy;

function scanDirectory($html)
{
	// Adding the desired doctype to the HTML.
	// $doctype = '<!DOCTYPE html>';
	// $html = $doctype . $html;

	$tidy = new HtmlTidy();
	$tidy->parseString($html);
	$tidy->cleanRepair();

	// Get the cleaned HTML as a string.
	$cleanedHtml = $tidy->getHtml();

}