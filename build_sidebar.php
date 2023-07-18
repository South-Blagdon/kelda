<?php
// Include the scanDirectory function here
require_once __DIR__ . "/php/dirScan.php";


// Include the mergeResults function here
require_once __DIR__ . "/php/mergeDirScan.php";

// Include the Symfony YAML component
require_once __DIR__ . "/vendor/autoload.php";

use Symfony\Component\Yaml\Yaml;

$outputFile = 'templates/sidBar.twig';
$savedNavMenuFile = 'src/config/sideBar.yaml';
$htmlDir = './src/html/';
$htmlTestOutput = 'build/kelda/test.html';


// Call the scanDirectory function
$results = scanDirectory($htmlDir);

// get the saved menu yaml file if any. This is used to change menu items order etc
if (file_exists($savedNavMenuFile)) {
    // Load file contents
    $fileContents = file_get_contents($savedNavMenuFile);
    
    // Parse YAML into an associative array
    $currentMenu = Yaml::parse($fileContents);
    if (!is_array($currentMenu)) {
	$currentMenu = array();
    }
    
    
    // Access the data
    // Example: Output the value of a specific key
    //echo $data['keyName'];
} else {
    echo "File not found.";
    $currentMenu = [];
}

$menu = mergeResults($results,$currentMenu);


// Save as YAML
$yamlData = Yaml::dump($menu);
file_put_contents($savedNavMenuFile, $yamlData);


// Generate HTML output
$html = '<html>
<head>
  <title>Directory Scan Results</title>
</head>
<body>
  <h1>Directory Scan Results</h1>
  <nav>
  <ul>';

foreach ($menu as $item) {
	$directory = $item['directory'];
	if (empty($directory)) {
		$directory = '<b><i>html base dir</i></b>';
	}
//<a {% if pageId=="home" %}class="active" {% endif %}href="{{ homep }}index.html">Home page</a>
	$file = $item['file'];
	$saveAs = $item['saveAs'];
	$menuNode = $item['menuNode'];
	$menuItem = $item['menuItem'];
	if(($menuNode === '') OR ($menuNode === '.')){$menuNode = '';}else{
		$menuNode .= '/';
	}
	
	if ($file !== null) {
		$html .= "<li><a href='{$saveAs}'>{$menuItem}</a>, File: $file, Save as: $saveAs</li>";
	} else {
		$html .= "<li>Directory: $directory, <b><i>No file at this point</i></b>, Save as: $saveAs</li>";

	}
}
$html .= '<nav>';
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


file_put_contents($htmlTestOutput, $html);
// file_put_contents($file, $html);
?>

