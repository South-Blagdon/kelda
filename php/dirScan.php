<?php
/**
 * Recursively scans a directory and its subdirectories for HTML files.
 *
 * @param string $baseDirectory The base directory to start scanning from.
 * @param string $subdirectory Optional subdirectory within the base directory to start scanning from.
 *
 * @return array An array containing information about the found HTML files. Each item in the array is an associative array with the following keys:
 *   - 'directory' (string): The relative directory path of the HTML file.
 *   - 'file' (string|null): The file name of the HTML file. If the item represents a directory, this value is null.
 *   - 'saveAs' (string): The name to save the HTML file as.
 *   - 'menuItem' (string): The base name of the HTML file to be used as a menu item.
 *   - 'menuNode' (string): The directory path of the HTML file to be used as a menu node.
 * The input html file is the page main body before twig etc add the menu, header, footer, nav etc and then build the web site
 * saving the built html for the page at 'saveAs'
 */
function scanDirectory($baseDirectory, $subdirectory = '') {
    $items = [];
    $directory = $baseDirectory . $subdirectory;

    $contents = scandir($directory);
    $contents = array_diff($contents, ['.', '..']);

    $hasHtmlFiles = false;

    foreach ($contents as $item) {
        $path = $directory . '/' . $item;
        if (is_dir($path)) {
            $newSubdirectory = $subdirectory;
            if (!empty($subdirectory)) {
                $newSubdirectory .= '/';
            }
            $newSubdirectory .= $item;
            $subItems = scanDirectory($baseDirectory, $newSubdirectory);
            $items = array_merge($items, $subItems);
        } else {
            if (pathinfo($path, PATHINFO_EXTENSION) === 'html') {
                $hasHtmlFiles = true;
                if ($subdirectory === '') {
                    $sa = $item;
                } else {
                    $sa = $subdirectory . '.html';
                }
                $items[] = [
                    'directory' => $subdirectory,
                    'file' => $item,
                    'saveAs' => $sa,
                    'menuItem' => basename($sa),
                    'menuNode' => dirname($sa)
                ];
            }
        }
    }

    if (!$hasHtmlFiles && count($items) > 0) {
        $items[] = [
            'directory' => $subdirectory,
            'file' => null,
            'saveAs' => $subdirectory . '/index.html'
        ];
    }

    return $items;
}
