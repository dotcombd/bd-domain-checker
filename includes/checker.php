<?php
function checkBDDomain($domain) {
    $output = shell_exec("whois $domain");
    if (strpos($output, 'not found') !== false || strpos($output, 'No match for') !== false) {
        return true; // Available
    }
    return false; // Not available
}
