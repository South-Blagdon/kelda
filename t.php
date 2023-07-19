<?php
// Sample HTML string with messy formatting
$html = '<html><head><title>Hello</title></head><body><h1>Heading</h1><p>This is a paragraph.</p></body></html>';

// Create a new Tidy instance and set the desired options
$config = array(
    'indent' => true,
    'indent-spaces' => 4
);

// Parse and clean the HTML with the custom configuration
$tidyHtml = tidy_parse_string($html, $config);
tidy_clean_repair($tidyHtml);

// Output the tidy HTML
echo $tidyHtml;
?>
