<?php
// VULNERABILITY: Exposed debug information
// This should never be accessible in production

echo "<h1>PHP Configuration - DEBUG PAGE</h1>";
echo "<p style='color: red;'><strong>VULNERABILITY:</strong> This debug page exposes sensitive system information!</p>";

phpinfo();

echo "<hr>";
echo "<h2>Environment Variables</h2>";
foreach ($_ENV as $key => $value) {
    echo "<strong>$key:</strong> $value<br>";
}

echo "<hr>";
echo "<h2>Server Information</h2>";
foreach ($_SERVER as $key => $value) {
    if (is_string($value)) {
        echo "<strong>$key:</strong> $value<br>";
    }
}
?>