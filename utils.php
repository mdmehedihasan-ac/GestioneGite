<?php
function formattaData($data) {
    $d = strtotime($data);
    return $d ? date('d/m/Y', $d) : '';
}

function nomeStato($id) {
    $nomi = [1 => 'Bozza', 2 => 'Approvata', 3 => 'Bocciata', 4 => 'Organizzazione', 5 => 'Conclusa'];
    return $nomi[$id] ?? 'Sconosciuto';
}

function nomeRuolo($id) {
    return $id == 2 ? 'Commissione' : 'Docente';
}
?>
