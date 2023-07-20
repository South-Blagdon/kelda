<?php
// Enable error reporting
error_reporting(E_ALL);

// Display errors on the screen
ini_set('display_errors', '1');

require_once __DIR__ . '/php/subDirFuncs.php';
require_once __DIR__ . '/php/checkHtml.php';

require_once __DIR__ . '/vendor/autoload.php';


// Include the Symfony YAML component
use Symfony\Component\Yaml\Yaml;

$savedNavMenuFile = 'src/config/sideBar.yaml';
$htmlDir = './src/html/';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'debug' => true,
    // Enable debug mode
    'strict_variables' => true, // Enable strict variable checking
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());


if (file_exists($savedNavMenuFile)) {
    echo "Found menu config yaml file: $savedNavMenuFile\n";
    // Load file contents
    $fileContents = file_get_contents($savedNavMenuFile);

    // Parse YAML into an associative array
    $currentMenu = Yaml::parse($fileContents);
    if (!is_array($currentMenu)) {
        $currentMenu = array();
    }
    $menu = $currentMenu;

    // Access the data
    // Example: Output the value of a specific key
    //echo $data['keyName'];
} else {
    echo "File not found $.";
    trigger_error("An error occurred. Script halted.", E_USER_ERROR);
}
$lastMenuNode = '';
foreach ($menu as $item) {
    $enabled = $item['enabled'];
    if ($enabled === true) {
        $dir = $item['directory']; //src dir
        $file = $item['file']; // src file name
        $saveAs = $item['saveAs']; // build dir file name
        $menuNode = $item['menuNode']; // the node on the tree
        $menuItem = $item['menuItem']; // the name of the page
        if (!empty($dir)) { //We need this if as if $dir is empty we can't add the '/'.
            $srcFile = $htmlDir . $dir . '/' . $file;
        } else {
            $srcFile = $htmlDir . $file;
        }
        if (is_dir($srcFile)) {
            //TODO: maybe build a .html index file for the sub pages and place it here.
            continue;
        }
        $html = file_get_contents($srcFile);

        // Render the HTML file using Twig
        $template = $twig->createTemplate($html);
        $renderedHtml = $template->render([
            'site_image_path2' => 'assets/images/'
        ]);

        $currentYear = date('Y');
        $pageId = 'id_' . $saveAs;
        $pathToSiteRoot = getRelativePath($saveAs);
        $rendered = $twig->render('main.twig', [
            'content' => $renderedHtml,
            'title' => "Kelda: $menuItem",
            'site_name' => "Kelda",
            'current_year' => $currentYear,
            'filename' => $saveAs,
            'subdirectory' => $dir,
            'pathToSiteRoot' => $pathToSiteRoot,
            'pageId' => $pageId,
            'site_image_path2' => 'assets/images/'
        ]);
        $saveFile = $saveAs;
        // if (!empty($dir)) {
        //     $saveFile = $dir . '/' . $saveFile;
        // }
        $saveFile = 'build/kelda/' . $saveFile;
        if ($menuNode !== $lastMenuNode) {
            checkSubdirectory('build/kelda/', dirname($saveFile));
            $lastMenuNode = $menuNode;
        }
        echo "\nBuild file: $saveFile ";
        //$rendered = tidy_repair_string($rendered);


        // Create a new Tidy instance and set the desired options
        $config = array(
            'indent' => true,
            'indent-spaces' => 4,
            'wrap' => 200,
            'newline' => 'block-level',
            // Place links on a new line
            'show-body-only' => false // Preserve <!DOCTYPE> declaration
        );

        // Parse and clean the HTML with the custom configuration
        //$html = '<html><head><title>Hello</title></head><body><h1>Heading</h1><p>This is a paragraph.</p></body></html>';
        $html = $rendered;
        $tidyHtml = tidy_parse_string($html, $config);
        tidy_clean_repair($tidyHtml);

        $tidyHtml = $tidyHtml->html()->value;

        // Add the DOCTYPE back to the cleaned HTML
        $tidyHtml = "<!DOCTYPE html>\n" . $tidyHtml;
        //$tidyHtml = $html;
        $errorMessages = checkHtmlSyntaxErrors($rendered);
        //$rendered = tidy_clean_repair($rendered);
        if ($errorMessages !== null) {
            echo "Syntax errors in the HTML code for file $saveFile :\n" . $errorMessages;
        } else {
            echo "No HTML error found.";
        }
        file_put_contents($saveFile, $tidyHtml);
    }
}

?>