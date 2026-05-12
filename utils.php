<?php
function formattaData($data) {
    $d = strtotime($data);
    return $d ? date('d/m/Y', $d) : '';
}
?>
