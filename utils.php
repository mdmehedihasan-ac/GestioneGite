<?php
// funzione per formattare la data (trovata dall'ia per un bug di formattazione)
function formattaData($data) {
    if ($data === null || $data === '') return '';
    $d = strtotime($data);
    return $d ? date('d/m/Y', $d) : '';
}

// funzione per ottenere il nome dello stato
function nomeStato($id) {
    $nomi = [1 => 'Bozza', 2 => 'Approvata', 3 => 'Bocciata', 4 => 'Organizzazione', 5 => 'Conclusa'];
    return isset($nomi[$id]) ? $nomi[$id] : 'Sconosciuto';
}

// funzione per ottenere il nome del ruolo
function nomeRuolo($id) {
    return $id == 2 ? 'Commissione' : 'Docente';
}
?>
