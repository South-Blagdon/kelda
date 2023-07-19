<?php
/**
 * Function to check for syntax errors in an HTML string using DOMDocument.
 *
 * @param string $html The HTML string to check for syntax errors.
 * @return string|null Returns the error message if syntax errors are found, or null if no errors are found.
 */
function checkHtmlSyntaxErrors($html) {
    $dom = new DOMDocument();

    // Disable error reporting for XML parsing errors
    libxml_use_internal_errors(true);

    // Load the HTML string into the DOMDocument
    $loaded = $dom->loadHTML($html);

    // Check for XML parsing errors
    if (!$loaded) {
        $errorMessages = [];
        foreach (libxml_get_errors() as $error) {
            $errorMessages[] = "Line {$error->line}: {$error->message}";
        }
        libxml_clear_errors();

        return implode("\n", $errorMessages);
    }

    return null;
}