<?php


/**
 * 
// Usage example
$filename = 'path/to/file.ext';
$backupFile = createBackupFile($filename);

if ($backupFile) {
    echo "File renamed to: " . $backupFile;
} else {
    echo "File does not exist.";
}
 */


/**
 * Creates a backup file by renaming the original file with a numbered suffix.
 *
 * @param string $filename The path to the original file.
 * @return string|null The backup filename if the original file exists and was renamed, or null if the file doesn't exist.
 */
function createBackupFile($filename)
{
	if (file_exists($filename)) {
		$backupFilename = getBackupFilename($filename);
		rename($filename, $backupFilename);
		return $backupFilename;
	} else {
		return null;
	}
}

/**
 * Generates a backup filename with a numbered suffix to avoid conflicts.
 *
 * @param string $filename The original filename.
 * @return string The backup filename.
 */
function getBackupFilename($filename)
{
	$backupNumber = 1;
	$backupFilename = $filename . '.' . $backupNumber;

	while (file_exists($backupFilename)) {
		$backupNumber++;
		$backupFilename = $filename . '.' . $backupNumber;
	}

	return $backupFilename;
}