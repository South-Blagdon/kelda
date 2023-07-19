<?php

require_once __DIR__ . 'php/subDirFuncs.php';

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
    echo "Found menu config yaml file: $savedNavMenuFile";
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

foreach ($menu as $item) {
    $enabled = $item['enabled'];
    if ($enabled === true) {
        $dir = $item['directory']; //src dir
        $file = $item['file']; // src file name
        $saveAs = $item['saveAs']; // build dir file name
        $menuNode = $item['menuNode']; // the node on the tree
        $menuItem = $item['menuItem']; // the name of the page
        if (!empty($dir)) {
            $html = file_get_contents($htmlDir . $dir . '/' . $file);
        } else {
            $html = file_get_contents($htmlDir . $file);
        }

        // Render the HTML file using Twig
        $template = $twig->createTemplate($html);
        $renderedHtml = $template->render([
            'site_image_path2' => 'assets/images/'
        ]);

        $currentYear = date('Y');
        $pageId = 'id_' . $saveAs;
        $rendered = $twig->render('main.twig', [
            'content' => $renderedHtml,
            'title' => "Kelda: $menuItem",
            'site_name' => "Kelda",
            'current_year' => $currentYear,
            'filename' => $saveAs,
            'subdirectory' => $dir,
            'pageId' => $pageId,
            'site_image_path2' => 'assets/images/'
        ]);
        $saveFile = $saveAs;
        // if (!empty($dir)) {
        //     $saveFile = $dir . '/' . $saveFile;
        // }
        $saveFile = 'build/kelda/' . $saveFile;
        checkSubdirectory('build/kelda/', $saveFile);
        file_put_contents($saveFile, $rendered);
        echo "Build file: $saveFile\n";
    }
}

?>