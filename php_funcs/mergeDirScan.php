<?php

/**
 * Merge new results with existing data
 * @param array $newResults
 * @param array $existingData
 * @return array
 */

function mergeResults(array $newResults, array $existingData): array
{
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
                $sa = $item;
            } else {
                $sa = $directory . '.html';
            }
            $existingData[] = [
                'directory' => $directory,
                'file' => $file,
                'saveAs' => $saveAs,
                'enabled' => $enabled,
                'menuItem' => basename($sa),
                'menuNode' => dirname($sa),
                'new' => true
            ];
        }
    }

    return $existingData;
}