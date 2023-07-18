<?php

/**
 * Merge new results with existing data
 * @param array $newResults
 * @param array $existingData
 * @return array
 */

function mergeResults(array $newResults, array $existingData): array
{
    // echo 'dump $newResults';
    // var_dump($newResults);
    foreach ($newResults as $item) {
        $directory = $item['directory'];
        $file = $item['file'];
        $saveAs = $item['saveAs'];
        $enabled = true; // Mark as enabled by default

        // Check if the file is already in the existing data
        $found = false;
        foreach ($existingData as &$existingItem) {
            if ($existingItem['directory'] === $directory && $existingItem['file'] === $file) {
                $found = true;
                // $enabled = $existingItem['enabled']; // Preserve the existing enabled status

                break;
            }
        }

        // If the file is not found in the existing data, add it
        if (!$found) {
            if ($directory === '') {
                $sa = $file; // $item[''];
            } else {
                $sa = $directory . '.html';
            }
            echo "\nnew html page found dump sa: ";
            var_dump($sa);
            //echo "dump directory: ";var_dump($directory);
            if ($saveAs === 'index.html') {
                $menuItem = 'Home';
            } else {
                $menuItem = basename($sa);
            }
            $existingData[] = [
                'directory' => $directory,
                'file' => $file,
                'saveAs' => $saveAs,
                'menuItem' => $menuItem,
                'menuNode' => dirname($sa),
                'enabled' => $enabled,
                'new' => true
            ];
        }
    }

    return $existingData;
}