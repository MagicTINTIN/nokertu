<?php
    echo("Test OK:\n");
    foreach ($_POST as $key => $value) {
        echo("'" . htmlspecialchars($key) . "'='" . htmlspecialchars($value) . "'\n");
    }
?>