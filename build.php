<?php

require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'debug' => true,
    // Enable debug mode
    'strict_variables' => true, // Enable strict variable checking
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

$htmlPrefix = 'src/html/';
function scanDirectory($directory)
{
    $items = [];

    $contents = scandir($directory);
    $contents = array_diff($contents, ['.', '..']);

    foreach ($contents as $item) {
        $path = $directory . '/' . $item;
        if (is_dir($path)) {
            $items[$item] = scanDirectory($path);
        } else {
            $items[] = $item;
        }
    }

    return $items;
}

$items = scanDirectory($htmlPrefix);

$htmlFiles = glob($htmlPrefix . '{*,*/*}.html', GLOB_BRACE);

foreach ($htmlFiles as $file) {
    $html = file_get_contents($file);

    // Render the HTML file using Twig
    $template = $twig->createTemplate($html);
    $renderedHtml = $template->render([
        'site_image_path2' => 'assets/images/'
    ]);

    $filename = basename($file);
    $subdirectory = dirname($file);
    $subdirectory = substr($subdirectory, strlen($htmlPrefix));
    $currentYear = date('Y');
    if (!empty($subdirectory)) {
        $pageId = $subdirectory;
    } else {
        $pageId = $subdirectory;
    }
    $rendered = $twig->render('main.twig', [
        'content' => $renderedHtml,
        'title' => "Kelda: $subdirectory",
        'site_name' => "Kelda",
        'current_year' => $currentYear,
        'filename' => $filename,
        'subdirectory' => $subdirectory ,
        'pageId' => $pageId,
        'site_image_path2' => 'assets/images/'
    ]);

    if (!empty($subdirectory)) {
        $filename = $subdirectory . '.html';
    }

    file_put_contents('build/kelda/' . $filename, $rendered);
    echo "Build file ($subdirectory)$filename\n";
}

?>

