<?php
// Include the scanDirectory function here
require_once __DIR__ . "/php/dirScan.php";


// Include the mergeResults function here
require_once __DIR__ . "/php/mergeDirScan.php";

// Include the Symfony YAML component
require_once __DIR__ . "/vendor/autoload.php";

use Symfony\Component\Yaml\Yaml;

$outputFile = 'templates/sideBar.twig';
$savedNavMenuFile = 'src/config/sideBar.yaml';
$htmlDir = './src/html/';
$htmlTestOutput = 'build/kelda/test.html';


// Call the scanDirectory function
$results = scanDirectory($htmlDir);

// get the saved menu yaml file if any. This is used to change menu items order etc
if (file_exists($savedNavMenuFile)) {
	echo "Found menu config yaml file: $savedNavMenuFile";
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
	$menuNode = $item['menuNode'];// the node on the tree
	$menuItem = $item['menuItem'];// the name of the page
	if(($menuNode === '') OR ($menuNode === '.')){$menuNode = '';}else{
		$menuNode .= '/';
	}
	
	if ($file !== null) {
		$html .= "<li><a href='{$saveAs}'>{$menuItem}</a>, File: $file, Save as: $saveAs</li>";
	} else {
		$html .= "<li>Directory: $directory, <b><i>No file at this point</i></b>, Save as: $saveAs</li>";

	}
	$html .= " \n";
}
$html .= '<nav>';
ob_start();
echo '
<details>
  <summary>Merged Arrays of config.yaml and html source tree</summary>
  <p>';//print_r($results);
var_dump($results);


// Capture the output into a string variable
$html .= '<pre>' . ob_get_contents() . '</pre></p></details>';

// Clean the output buffer
ob_end_clean();

$html .= '</ul>
</body>
</html>';

echo "\nOutput the HTML to the web page: $htmlTestOutput \n\r";
//var_dump($html);

file_put_contents($htmlTestOutput, $html);
// file_put_contents($file, $html);

function parentNode($node, $lastNode ){
	//todo
	$nodeCount = substr_count($node, '/');
	$nodeCountLast =substr_count($lastNode,'/');
	if($nodeCount < $nodeCountLast){
		return $nodeCountLast - $nodeCount;
	}
	else return 0;
}
echo "build twig file: $outputFile\n";
$html =   '<img src="{{ pathToSiteRoot }}assets/images/union-jack.gif">';
//<h4>Kelda Lakelands</h4>
// <p>page id "{{ pageId }}"</p>
// <p>title "{{ title }}"</p>
// <p>filename {{filename}}</p>';
$html .=   '<nav class="sidebar">
<ul>';
//<ul style="list-style-type: none;" >';
$lastMenuNode = '';
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
	$enabled = $item['enabled'];
	if(($menuNode === '') OR ($menuNode === '.')){$menuNode = '';}else{
		$menuNode .= '/';
	}
	if($lastMenuNode !== $menuNode){
		if(parentNode($menuNode,$lastMenuNode) > 0){
			//close folding menu item
			$html .= '</div> </details>';
		}else{
			// start folding sub node
			$html .= "<details>
  				<summary>$menuNode</summary>
				  <div class='indented-content'>";

		}
		$lastMenuNode = $menuNode;
	}
	
	
	if (($file !== null) and ($enabled === true)) {
		//$html .= "<li><a href='{$saveAs}'>{$menuItem}</a>, File: $file, Save as: $saveAs</li>/n";
		//$pageid = 'id_' . strtolower($saveAs);
		$pageid = 'id_' . $saveAs;
		$html .= "<li><a {% if pageId=='$pageid' %}class='active' {% endif %}href='{{ pathToSiteRoot }}$saveAs'>$menuItem</a></li>\n";
	} else {
		//$html .= "<li>Directory: $directory, <b><i>No file at this point</i></b>, Save as: $saveAs</li>";

	}
	$html .= " \n";
}
$html .= '</ul></nav>';
file_put_contents($outputFile, $html);
?>