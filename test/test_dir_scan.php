<?php

// Include the scanDirectory function here
require_once __DIR__ . "/../php_funcs/dirScan.php";

// Include the Symfony YAML component
require_once __DIR__ . "/../vendor/autoload.php";

use Symfony\Component\Yaml\Yaml;

// Define the directory to scan
$directoryToScan = './src/html/';

// Dir to save output to
$outDir = 'build/kelda/';

// Output file names.
$filename = 'test.html'; //html
$jsonFilename = 'results.json';
$yamlFilename = 'results.yaml';

// Call the scanDirectory function
$results = scanDirectory($directoryToScan);

// Save as JSON
$jsonData = json_encode($results, JSON_PRETTY_PRINT);
file_put_contents($outDir . $jsonFilename, $jsonData);

// Save as YAML
$yamlData = Yaml::dump($results);
file_put_contents($outDir . $yamlFilename, $yamlData);


// Generate HTML output
$html = '<html>
<head>
  <title>Directory Scan Results</title>
</head>
<body>
  <h1>Directory Scan Results</h1>
  <ul>';

foreach ($results as $item) {
	$directory = $item['directory'];
	if (empty($directory)) {
		$directory = '<b><i>html base dir</i></b>';
	}

	$file = $item['file'];
	$saveAs = $item['saveAs'];

	if ($file !== null) {
		$html .= "<li>Directory: $directory, File: $file, Save as: $saveAs</li>";
	} else {
		$html .= "<li>Directory: $directory, <b><i>No file at this point</i></b>, Save as: $saveAs</li>";

	}
}

ob_start();
//print_r($results);
var_dump($results);
// Capture the output into a string variable
$html .= '<pre>' . ob_get_contents() . '</pre>';

// Clean the output buffer
ob_end_clean();

$html .= '</ul>
</body>
</html>';

// Output the HTML to the web page
// echo $html;


file_put_contents(($outDir . $filename), $html);
// file_put_contents($file, $html);
?>

