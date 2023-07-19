<?php

/**
 * File: subDirFuncs.php
 *
 * This file contains functions related to handling subdirectories within a parent directory.
 * It includes functions to check if a directory exists, validate if a subdirectory is a valid
 * subdirectory of the parent directory, and create a subdirectory if it meets the criteria.
 *
 * @author jmnc2
 * @version 1.0
 * 
 * @example
 * $parentDirectory = '/path/to/parent/directory';
 * $subDirectory = '/path/to/parent/directory/subdirectory';
 *
 * // Call the function to create the subdirectory
 * checkSubdirectory($parentDirectory, $subDirectory);
*/


function getRelativePath($string) {
    $slashCount = substr_count($string, '/');
    $relativePath = '';
    
    for ($i = 0; $i < $slashCount; $i++) {
        $relativePath .= '../';
    }
    
    return $relativePath;
}

/**
 * Function to check if a directory exists
 *
 * @param string $directory The directory path to check
 * @return bool Returns true if the directory exists, false otherwise.
 */
function isDirectoryExists($directory) {
    return is_dir($directory);
}

/**
 * Function to check if $subDirectory is a valid subdirectory of $parentDirectory
 *
 * @param string $parentDirectory The parent directory path
 * @param string $subDirectory The subdirectory path to check
 * @return bool Returns true if $subDirectory is a valid subdirectory of $parentDirectory, false otherwise.
 */
function isSubdirectoryValid($parentDirectory, $subDirectory) {
    $parentDirectory = rtrim($parentDirectory, '/');
    $subDirectory = rtrim($subDirectory, '/');

    return strpos($subDirectory, $parentDirectory) === 0;
}

/**
 * Function to check if subdirectory if it's a valid subdirectory of the parent and create it if necessary
 *
 * @param string $parentDirectory The parent directory path
 * @param string $subDirectory The subdirectory path to create
 * @return bool Returns true if $subDirectory is a valid subdirectory of $parentDirectory, false otherwise.
 *
 * @example
 * $parentDirectory = '/path/to/parent/directory';
 * $subDirectory = '/path/to/parent/directory/subdirectory';
 *
 * // Call the function to create the subdirectory
 * createSubdirectory($parentDirectory, $subDirectory);
 */
function checkSubdirectory($parentDirectory, $subDirectory) {
    if (isDirectoryExists($parentDirectory) && isSubdirectoryValid($parentDirectory, $subDirectory)) {
        if (!isDirectoryExists($subDirectory)) {
            mkdir($subDirectory, 0777, true);
            echo "$subDirectory has been created as a subdirectory of $parentDirectory.\n";
        } else {
            echo "$subDirectory already exists as a subdirectory of $parentDirectory.\n";
        }
        return true;
    } else {
        echo "$subDirectory is NOT a subdirectory of $parentDirectory.\n";
        return false;
    }
}
?>
