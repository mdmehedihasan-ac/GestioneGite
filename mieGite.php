<?php
include('nav.php');

$idUtenteLoggato = $_SESSION['id_utente'];
$messaggio = "";

// ─── MODIFICA GITA 1G IN ORGANIZZAZIONE ──────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'modifica_org_1g') {
    $idGita       = intval($_POST['id_gita']);
    $dest         = $conn->real_escape_string($_POST['mo_destinazione'] ?? '');
    $desc         = $conn->real_escape_string($_POST['mo_descrizione']  ?? '');
    $mezzo        = $conn->real_escape_string($_POST['mo_mezzo']        ?? '');
    $periodo      = $conn->real_escape_string($_POST['mo_periodo']      ?? '');
    $classi       = $conn->real_escape_string($_POST['mo_classi']       ?? '');
    $giorno       = $_POST['mo_giorno'] ?: null;
    $giorno_s     = $giorno ? "'" . $conn->real_escape_string($giorno) . "'" : "NULL";
    $costoMezzo   = $_POST['mo_costoMezzo']   !== '' ? floatval($_POST['mo_costoMezzo'])   : "NULL";
    $costoAtt     = $_POST['mo_costoAttivita'] !== '' ? floatval($_POST['mo_costoAttivita']) : "NULL";
    $costoAP      = $_POST['mo_costoAPersona'] !== '' ? floatval($_POST['mo_costoAPersona']) : "NULL";
    $numAlunni    = $_POST['mo_numAlunni'] !== '' ? intval($_POST['mo_numAlunni']) : "NULL";
    $conn->query("UPDATE gita1g SET destinazione='$dest', descrizione='$desc', mezzo='$mezzo', periodo='$periodo', classi='$classi', giorno=$giorno_s, costoMezzo=$costoMezzo, costoAttivita=$costoAtt, costoAPersona=$costoAP, numAlunni=$numAlunni WHERE idGita=$idGita AND idUtente=$idUtenteLoggato");
    $messaggio = "modifica_org_ok";
}

// ─── MODIFICA GITA 5G IN ORGANIZZAZIONE ──────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'modifica_org_5g') {
    $idGita       = intval($_POST['id_gita']);
    $dest         = $conn->real_escape_string($_POST['mo_destinazione']  ?? '');
    $desc         = $conn->real_escape_string($_POST['mo_descrizione']   ?? '');
    $mezzo        = $conn->real_escape_string($_POST['mo_mezzo']         ?? '');
    $periodo      = $conn->real_escape_string($_POST['mo_periodo']       ?? '');
    $classi       = $conn->real_escape_string($_POST['mo_classi']        ?? '');
    $gi           = $_POST['mo_giornoInizio'] ?: null;
    $gf           = $_POST['mo_giornoFine']   ?: null;
    $gi_s         = $gi ? "'" . $conn->real_escape_string($gi) . "'" : "NULL";
    $gf_s         = $gf ? "'" . $conn->real_escape_string($gf) . "'" : "NULL";
    $costoAP      = $_POST['mo_costoAPersona'] !== '' ? floatval($_POST['mo_costoAPersona']) : "NULL";
    $numAlunni    = $_POST['mo_numAlunni'] !== '' ? intval($_POST['mo_numAlunni']) : "NULL";
    $conn->query("UPDATE gite5 SET destinazione='$dest', descrizione='$desc', mezzo='$mezzo', periodo='$periodo', classi='$classi', giornoInizio=$gi_s, giornoFine=$gf_s, costoAPersona=$costoAP, numAlunni=$numAlunni WHERE idGita=$idGita AND idUtente=$idUtenteLoggato");
    $messaggio = "modifica_org_ok";
}

// ─── ORGANIZZA GITA 1 GIORNO (copia con stato 4) ────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'organizza_1g') {
    $idGita      = intval($_POST['id_gita']);
    $idUtente    = $_SESSION['id_utente'];
    $descrizione = $_POST['org_descrizione'] ?? '';
    $mezzo       = $_POST['org_mezzo']       ?? '';
    $classi      = $_POST['org_classe']      ?? '';
    $periodo     = $_POST['org_periodo']    ?: null;
    $giorno      = $_POST['org_giorno']     ?: null;
    $costoMezzo  = $_POST['org_costoMezzo']  !== '' ? floatval($_POST['org_costoMezzo'])  : null;
    $costoGiorno = $_POST['org_costoGiorno'] !== '' ? floatval($_POST['org_costoGiorno']) : null;
    $numAlunni   = $_POST['org_numAlunni']   !== '' ? intval($_POST['org_numAlunni'])     : null;

    $orig = $conn->query("SELECT * FROM gita1g WHERE idGita = $idGita")->fetch_assoc();
    if ($orig) {
        $dest_s       = $conn->real_escape_string($orig['destinazione']);
        $desc_s       = $conn->real_escape_string($descrizione);
        $mezzoFin_s   = $conn->real_escape_string($mezzo ?: ($orig['mezzo'] ?? ''));
        $classi_s     = $conn->real_escape_string($classi);
        $perFin_s     = $conn->real_escape_string($periodo ?? $orig['periodo'] ?? '');
        $giorno_s     = $giorno      ? "'" . $conn->real_escape_string($giorno) . "'" : "NULL";
        $costoMezzo_s = $costoMezzo  !== null ? floatval($costoMezzo)  : "NULL";
        $costoGiorno_s= $costoGiorno !== null ? floatval($costoGiorno) : "NULL";
        $costoA_s     = floatval($orig['costoAPersona']);
        $numAlunni_s  = $numAlunni   !== null ? intval($numAlunni)     : "NULL";

        $sql = "INSERT INTO gita1g (idUtente, destinazione, descrizione, mezzo, periodo, classi, giorno, costoMezzo, costoAttivita, costoAPersona, numAlunni, idStato)
                VALUES ($idUtente, '$dest_s', '$desc_s', '$mezzoFin_s', '$perFin_s', '$classi_s', $giorno_s, $costoMezzo_s, $costoGiorno_s, $costoA_s, $numAlunni_s, 4)";
        if ($conn->query($sql)) {
            $messaggio = "organizza_ok";
            $newId = $conn->insert_id;
            $conn->query("INSERT IGNORE INTO accompagnatori (idgita, idutente, tipo_gita) VALUES ($newId, $idUtente, '1g')");
        } else {
            $messaggio = "error";
        }
    }
}

// ─── ORGANIZZA GITA PIU GIORNI (copia con stato 4) ───────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'organizza_5g') {
    $idGita       = intval($_POST['id_gita']);
    $idUtente     = $_SESSION['id_utente'];
    $descrizione  = $_POST['org_descrizione']  ?? '';
    $mezzo        = $_POST['org_mezzo']         ?? '';
    $classi       = $_POST['org_classe']        ?? '';
    $periodo      = $_POST['org_periodo']      ?: null;
    $giornoInizio = $_POST['org_giornoInizio'] ?: null;
    $giornoFine   = $_POST['org_giornoFine']   ?: null;
    $costoAPersona= $_POST['org_costoAPersona'] !== '' ? floatval($_POST['org_costoAPersona']) : null;
    $numAlunni    = $_POST['org_numAlunni']     !== '' ? intval($_POST['org_numAlunni'])       : null;

    $orig = $conn->query("SELECT * FROM gite5 WHERE idGita = $idGita")->fetch_assoc();
    if ($orig) {
        $dest_s      = $conn->real_escape_string($orig['destinazione']);
        $desc_s      = $conn->real_escape_string($descrizione);
        $mezzoFin_s  = $conn->real_escape_string($mezzo ?: ($orig['mezzo'] ?? ''));
        $classi_s    = $conn->real_escape_string($classi);
        $perFin_s    = $conn->real_escape_string($periodo ?? $orig['periodo'] ?? '');
        $gi_s        = $giornoInizio ? "'" . $conn->real_escape_string($giornoInizio) . "'" : "NULL";
        $gf_s        = $giornoFine   ? "'" . $conn->real_escape_string($giornoFine)   . "'" : "NULL";
        $costoFin    = $costoAPersona !== null ? floatval($costoAPersona) : floatval($orig['costoAPersona'] ?? 0);
        $numAlunni_s = $numAlunni !== null ? intval($numAlunni) : "NULL";

        $sql = "INSERT INTO gite5 (idUtente, destinazione, descrizione, mezzo, periodo, classi, giornoInizio, giornoFine, costoAPersona, numAlunni, idStato)
                VALUES ($idUtente, '$dest_s', '$desc_s', '$mezzoFin_s', '$perFin_s', '$classi_s', $gi_s, $gf_s, $costoFin, $numAlunni_s, 4)";
        if ($conn->query($sql)) {
            $messaggio = "organizza_ok";
            $newId = $conn->insert_id;
            $conn->query("INSERT IGNORE INTO accompagnatori (idgita, idutente, tipo_gita) VALUES ($newId, $idUtente, '5g')");
        } else {
            $messaggio = "error";
        }
    }
}

// ─── RIPROPONI (modifica campi + rimetti in bozza) ────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'riproponi_1g') {
        $idGita       = intval($_POST['id_gita']);
        $destinazione = $conn->real_escape_string($_POST['destinazione']);
        $mezzo        = $conn->real_escape_string($_POST['mezzo'] ?? '');
        $periodo      = $conn->real_escape_string($_POST['periodo'] ?? '');
        $costo        = floatval($_POST['costo'] ?? 0);
        $conn->query("UPDATE gita1g SET destinazione='$destinazione', mezzo='$mezzo', periodo='$periodo', costoAPersona=$costo, idStato=1 WHERE idGita=$idGita AND idUtente=$idUtenteLoggato");
        $messaggio = "ok";
    }

    if ($_POST['action'] === 'riproponi_5g') {
        $idGita       = intval($_POST['id_gita']);
        $destinazione = $conn->real_escape_string($_POST['destinazione']);
        $mezzo        = $conn->real_escape_string($_POST['mezzo'] ?? '');
        $periodo      = $conn->real_escape_string($_POST['periodo'] ?? '');
        $costo        = floatval($_POST['costo'] ?? 0);
        $conn->query("UPDATE gite5 SET destinazione='$destinazione', mezzo='$mezzo', periodo='$periodo', costoAPersona=$costo, idStato=1 WHERE idGita=$idGita AND idUtente=$idUtenteLoggato");
        $messaggio = "ok";
    }

    if ($_POST['action'] === 'elimina_bocciata_1g') {
        $idGita = intval($_POST['id_gita']);
        $conn->query("DELETE FROM gita1g WHERE idGita=$idGita AND idUtente=$idUtenteLoggato AND idStato=3");
        $messaggio = "eliminata";
    }

    if ($_POST['action'] === 'elimina_bocciata_5g') {
        $idGita = intval($_POST['id_gita']);
        $conn->query("DELETE FROM gite5 WHERE idGita=$idGita AND idUtente=$idUtenteLoggato AND idStato=3");
        $messaggio = "eliminata";
    }
}

// ─── QUERY: proposte create da me (stato 1,2,3) ──────────────────────────────
$proposte = [];
$r1 = $conn->query("SELECT g.*, s.Stato, '1g' AS tipo FROM gita1g g JOIN statogita s ON g.idStato = s.IDStato WHERE g.idUtente = $idUtenteLoggato AND g.idStato IN (1,2,3) ORDER BY g.idGita DESC");
if ($r1) { while ($row = $r1->fetch_assoc()) $proposte[] = $row; }
$r2 = $conn->query("SELECT g.*, s.Stato, '5g' AS tipo FROM gite5 g JOIN statogita s ON g.idStato = s.IDStato WHERE g.idUtente = $idUtenteLoggato AND g.idStato IN (1,2,3) ORDER BY g.idGita DESC");
if ($r2) { while ($row = $r2->fetch_assoc()) $proposte[] = $row; }

// ─── QUERY: gite in organizzazione/concluse (stato 4,5) ──────────────────────
// Include sia le gite create dall'utente che quelle a cui partecipa come accompagnatore
$organizzate = [];
$r3 = $conn->query("
    SELECT g.*, s.Stato, '1g' AS tipo, 1 AS sono_autore
    FROM gita1g g JOIN statogita s ON g.idStato = s.IDStato
    WHERE g.idUtente = $idUtenteLoggato AND g.idStato IN (4,5)
    UNION
    SELECT g.*, s.Stato, '1g' AS tipo, 0 AS sono_autore
    FROM gita1g g JOIN statogita s ON g.idStato = s.IDStato
    JOIN accompagnatori a ON a.idgita = g.idGita AND a.tipo_gita = '1g'
    WHERE a.idutente = $idUtenteLoggato AND g.idUtente <> $idUtenteLoggato AND g.idStato IN (4,5)
    ORDER BY idGita DESC
");
if ($r3) { while ($row = $r3->fetch_assoc()) $organizzate[] = $row; }
$r4 = $conn->query("
    SELECT g.*, s.Stato, '5g' AS tipo, 1 AS sono_autore
    FROM gite5 g JOIN statogita s ON g.idStato = s.IDStato
    WHERE g.idUtente = $idUtenteLoggato AND g.idStato IN (4,5)
    UNION
    SELECT g.*, s.Stato, '5g' AS tipo, 0 AS sono_autore
    FROM gite5 g JOIN statogita s ON g.idStato = s.IDStato
    JOIN accompagnatori a ON a.idgita = g.idGita AND a.tipo_gita = '5g'
    WHERE a.idutente = $idUtenteLoggato AND g.idUtente <> $idUtenteLoggato AND g.idStato IN (4,5)
    ORDER BY idGita DESC
");
if ($r4) { while ($row = $r4->fetch_assoc()) $organizzate[] = $row; }

function badgeClass($stato) {
    switch ($stato) {
        case 'Approvata':      return 'badge-success';
        case 'Bocciata':       return 'badge-danger';
        case 'Bozza':          return 'badge-warning';
        case 'Organizzazione': return 'badge-primary';
        case 'Conclusa':       return 'badge-secondary';
        default:               return 'badge-secondary';
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Mie Gite</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="vetrina.css">
    <link rel="stylesheet" href="style_custom.css">
    <script src="vetrina.js" defer></script>
</head>
<body>
<div class="container">
<main class="content bozze-padding">

<div class="hero-section">
    <h2 style="margin-bottom:0.5rem;color:var(--blue-700);">Le Mie Gite</h2>
    <p>Tutte le gite che hai proposto o stai organizzando.</p>
</div>

<?php if ($messaggio === 'ok'): ?>
<script>document.addEventListener('DOMContentLoaded',function(){ document.getElementById('modalRiproponiOk').classList.remove('hidden'); });</script>
<?php endif; ?>
<?php if ($messaggio === 'organizza_ok'): ?>
<script>document.addEventListener('DOMContentLoaded',function(){ document.getElementById('modalOrganizzaOk').classList.remove('hidden'); });</script>
<?php endif; ?>
<?php if ($messaggio === 'modifica_org_ok'): ?>
<script>document.addEventListener('DOMContentLoaded',function(){ document.getElementById('modalModOrgOk').classList.remove('hidden'); });</script>
<?php endif; ?>
<?php if ($messaggio === 'eliminata'): ?>
<script>document.addEventListener('DOMContentLoaded',function(){ document.getElementById('modalEliminataOk').classList.remove('hidden'); });</script>
<?php endif; ?>

<!-- ══════════════ SEZIONE 1: PROPOSTE CREATE DA ME ══════════════ -->
<div class="table-section" style="margin-top:2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;">
        <h3 style="margin:0;color:var(--blue-700);">📋 Proposte create da me</h3>
        <span style="font-size:0.85rem;color:var(--blue-400);"><?php echo count($proposte); ?> proposta<?php echo count($proposte) != 1 ? 'e' : ''; ?></span>
    </div>

    <?php if (count($proposte) === 0): ?>
        <p style="color:#64748b;font-style:italic;">Non hai ancora creato nessuna proposta.</p>
    <?php else: ?>
    <div class="miegite-grid">
        <?php foreach ($proposte as $row):
            $tipo      = $row['tipo'] === '1g' ? 'Gita 1 Giorno' : 'Gita Più Giorni';
            $dest      = htmlspecialchars($row['destinazione']);
            $destJs    = htmlspecialchars($row['destinazione'], ENT_QUOTES);
            $mezzo     = htmlspecialchars($row['mezzo'] ?? '');
            $mezzoJs   = htmlspecialchars($row['mezzo'] ?? '', ENT_QUOTES);
            $periodo   = htmlspecialchars($row['periodo'] ?? '');
            $periodoJs = htmlspecialchars($row['periodo'] ?? '', ENT_QUOTES);
            $descJs    = htmlspecialchars($row['descrizione'] ?? '', ENT_QUOTES);
            $classiJs  = htmlspecialchars($row['classi'] ?? '', ENT_QUOTES);
            $costoRaw  = $row['costoAPersona'] ?? 0;
            $costo     = $costoRaw !== null ? '€ ' . number_format($costoRaw, 2, ',', '.') : '—';
            $stato     = $row['Stato'];
            $badge     = badgeClass($stato);
            $id        = intval($row['idGita']);
            $tipoTabella = $row['tipo'];

            if ($row['tipo'] === '1g') {
                $data = $row['giorno'] ? date('d/m/Y', strtotime($row['giorno'])) : '—';
                $dataLabel = 'Giorno';
            } else {
                $ini = $row['giornoInizio'] ? date('d/m/Y', strtotime($row['giornoInizio'])) : '—';
                $fin = $row['giornoFine']   ? date('d/m/Y', strtotime($row['giornoFine']))   : '—';
                $data = "$ini → $fin";
                $dataLabel = 'Date';
            }
        ?>
        <div class="miegite-card">
            <div class="miegite-card-header">
                <h4 class="miegite-card-title"><?php echo $dest; ?></h4>
                <span class="badge <?php echo $badge; ?>"><?php echo $stato; ?></span>
            </div>
            <div class="miegite-card-body">
                <div class="miegite-card-info">
                    <span><strong>Tipo:</strong> <?php echo $tipo; ?></span>
                    <span><strong>Mezzo:</strong> <?php echo $mezzo ?: '—'; ?></span>
                    <span><strong>Periodo:</strong> <?php echo $periodo ?: '—'; ?></span>
                    <span><strong><?php echo $dataLabel; ?>:</strong> <?php echo $data; ?></span>
                    <span><strong>Costo a persona:</strong> <?php echo $costo; ?></span>
                </div>
            </div>
            <?php if ($stato === 'Approvata'): ?>
            <div class="miegite-card-footer">
                <button type="button" class="button xs"
                    data-id="<?php echo $id; ?>"
                    data-dest="<?php echo $destJs; ?>"
                    data-desc="<?php echo $descJs; ?>"
                    data-mezzo="<?php echo $mezzoJs; ?>"
                    data-periodo="<?php echo $periodoJs; ?>"
                    data-classi="<?php echo $classiJs; ?>"
                    data-costo="<?php echo floatval($costoRaw); ?>"
                    data-tipo="<?php echo $tipoTabella; ?>"
                    onclick="apriOrg(this)">
                    🗓️ Organizza
                </button>
            </div>
            <?php elseif ($stato === 'Bocciata'): ?>
            <div class="miegite-card-footer">
                <button type="button" class="button xs"
                    onclick="apriModifica(<?php echo $id; ?>,'<?php echo $destJs; ?>','<?php echo $mezzoJs; ?>','<?php echo $periodoJs; ?>',<?php echo floatval($costoRaw); ?>,'<?php echo $tipoTabella; ?>')">
                    ✏️ Modifica
                </button>
                <button type="button" class="button cancel xs"
                    onclick="apriElimina(<?php echo $id; ?>,'<?php echo $destJs; ?>','<?php echo $tipoTabella; ?>')">
                    🗑️ Elimina
                </button>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- ══════════════ SEZIONE 2: GITE IN ORGANIZZAZIONE / CONCLUSE ══════════════ -->
<div class="table-section" style="margin-top:3rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;">
        <h3 style="margin:0;color:var(--blue-700);">🗓️ Gite che sto organizzando</h3>
        <span style="font-size:0.85rem;color:var(--blue-400);"><?php echo count($organizzate); ?> gita<?php echo count($organizzate) != 1 ? 'e' : ''; ?></span>
    </div>

    <?php if (count($organizzate) === 0): ?>
        <p style="color:#64748b;font-style:italic;">Non stai organizzando nessuna gita al momento.</p>
    <?php else: ?>
    <div class="miegite-grid">
        <?php foreach ($organizzate as $row):
            $tipo    = $row['tipo'] === '1g' ? 'Gita 1 Giorno' : 'Gita Più Giorni';
            $dest    = htmlspecialchars($row['destinazione']);
            $destJs  = htmlspecialchars($row['destinazione'], ENT_QUOTES);
            $mezzo   = htmlspecialchars($row['mezzo'] ?? '—');
            $mezzoJs = htmlspecialchars($row['mezzo'] ?? '', ENT_QUOTES);
            $periodo = htmlspecialchars($row['periodo'] ?? '—');
            $periodoJs = htmlspecialchars($row['periodo'] ?? '', ENT_QUOTES);
            $descJs  = htmlspecialchars($row['descrizione'] ?? '', ENT_QUOTES);
            $classiJs= htmlspecialchars($row['classi'] ?? '', ENT_QUOTES);
            $costo   = $row['costoAPersona'] !== null ? '€ ' . number_format($row['costoAPersona'], 2, ',', '.') : '—';
            $stato   = $row['Stato'];
            $badge   = badgeClass($stato);
            $numAl   = $row['numAlunni'] !== null ? $row['numAlunni'] : '—';
            $id      = intval($row['idGita']);
            $tipoTabella = $row['tipo'];
            if ($row['tipo'] === '1g') {
                $dataRaw   = $row['giorno'] ?? '';
                $data      = $dataRaw ? date('d/m/Y', strtotime($dataRaw)) : '—';
                $dataLabel = 'Giorno';
                $costoMezzoRaw = $row['costoMezzo'] ?? '';
                $costoAttRaw   = $row['costoAttivita'] ?? '';
                $costoAPRaw    = $row['costoAPersona'] ?? '';
                $costoMezzo = $costoMezzoRaw !== null && $costoMezzoRaw !== '' ? '€ ' . number_format($costoMezzoRaw, 2, ',', '.') : '—';
                $costoAtt   = $costoAttRaw   !== null && $costoAttRaw   !== '' ? '€ ' . number_format($costoAttRaw,   2, ',', '.') : '—';
                $extraInfo  = "<span><strong>Costo mezzo:</strong> $costoMezzo</span><span><strong>Costo attività:</strong> $costoAtt</span>";
            } else {
                $dataRaw = '';
                $ini = $row['giornoInizio'] ? date('d/m/Y', strtotime($row['giornoInizio'])) : '—';
                $fin = $row['giornoFine']   ? date('d/m/Y', strtotime($row['giornoFine']))   : '—';
                $data      = "$ini → $fin";
                $dataLabel = 'Date';
                $extraInfo = "";
                $costoMezzoRaw = '';
                $costoAttRaw   = '';
                $costoAPRaw    = $row['costoAPersona'] ?? '';
            }
        ?>
        <div class="miegite-card">
            <div class="miegite-card-header">
                <h4 class="miegite-card-title"><?php echo $dest; ?></h4>
                <span class="badge <?php echo $badge; ?>"><?php echo $stato; ?></span>
            </div>
            <div class="miegite-card-body">
                <div class="miegite-card-info">
                    <span><strong>Tipo:</strong> <?php echo $tipo; ?></span>
                    <span><strong>Mezzo:</strong> <?php echo $mezzo; ?></span>
                    <span><strong>Periodo:</strong> <?php echo $periodo; ?></span>
                    <span><strong><?php echo $dataLabel; ?>:</strong> <?php echo $data; ?></span>
                    <span><strong>Costo a persona:</strong> <?php echo $costo; ?></span>
                    <span><strong>Num. alunni:</strong> <?php echo $numAl; ?></span>
                    <?php echo $extraInfo; ?>
                </div>
            </div>
            <div class="miegite-card-footer">
                <button type="button" class="button xs"
                    data-id="<?php echo $id; ?>"
                    data-tipo="<?php echo $tipoTabella; ?>"
                    data-dest="<?php echo $destJs; ?>"
                    data-desc="<?php echo $descJs; ?>"
                    data-mezzo="<?php echo $mezzoJs; ?>"
                    data-periodo="<?php echo $periodoJs; ?>"
                    data-classi="<?php echo $classiJs; ?>"
                    data-giorno="<?php echo htmlspecialchars($row['tipo']==='1g' ? ($row['giorno']??'') : '', ENT_QUOTES); ?>"
                    data-giorno-inizio="<?php echo htmlspecialchars($row['tipo']==='5g' ? ($row['giornoInizio']??'') : '', ENT_QUOTES); ?>"
                    data-giorno-fine="<?php echo htmlspecialchars($row['tipo']==='5g' ? ($row['giornoFine']??'') : '', ENT_QUOTES); ?>"
                    data-costo-mezzo="<?php echo $costoMezzoRaw; ?>"
                    data-costo-att="<?php echo $costoAttRaw; ?>"
                    data-costo-ap="<?php echo $costoAPRaw; ?>"
                    data-num-alunni="<?php echo $row['numAlunni'] ?? ''; ?>"
                    onclick="apriModOrg(this)">
                    ✏️ Modifica
                </button>
                <?php if ($row['tipo'] === '1g'):
                    $accRes = $conn->query("SELECT CONCAT(u.Nome,' ',u.Cognome) AS nome FROM accompagnatori a JOIN utente u ON a.idutente=u.IDUtente WHERE a.idgita={$row['idGita']} AND a.tipo_gita='1g' ORDER BY u.Cognome,u.Nome");
                    $accList = [];
                    if ($accRes) { while ($aRow = $accRes->fetch_assoc()) $accList[] = $aRow['nome']; }
                    $accJson = htmlspecialchars(json_encode($accList), ENT_QUOTES);
                ?>
                <button type="button" class="button xs outline"
                    data-dest="<?php echo $destJs; ?>"
                    data-acc="<?php echo $accJson; ?>"
                    onclick="apriAccompagnatori(this)">👥 Accompagnatori</button>
                <?php endif; ?>
                <?php if ($row['tipo'] === '5g'): ?>
                <a href="partecipanti.php?id=<?php echo $row['idGita']; ?>" class="button xs" style="text-decoration:none;">👥 Partecipanti</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

</main>

<!-- ══════════════ MODAL — Modifica Gita 1g in Organizzazione ══════════════ -->
<div class="modal-overlay hidden" id="modalModOrg1g">
<div class="modal wide-modal">
<div class="modal-header">
    <h3 id="modOrg1gTitle">Modifica Gita 1 Giorno</h3>
    <button class="close-btn" onclick="document.getElementById('modalModOrg1g').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
<form id="formModOrg1g" method="POST" action="mieGite.php">
    <input type="hidden" name="action"  value="modifica_org_1g">
    <input type="hidden" name="id_gita" id="modOrg1g_id">
    <div class="form-grid">
        <div class="form-group full-row">
            <label>Destinazione *</label>
            <input type="text" name="mo_destinazione" id="modOrg1g_dest" class="form-control" required>
        </div>
        <div class="form-group full-row">
            <label>Descrizione</label>
            <input type="text" name="mo_descrizione" id="modOrg1g_desc" class="form-control" placeholder="Breve descrizione">
        </div>
        <div class="form-group">
            <label>Mezzo di trasporto</label>
            <select name="mo_mezzo" id="modOrg1g_mezzo" class="form-control">
                <option value="">— Seleziona —</option>
                <option value="Bus">Bus</option>
                <option value="Treno">Treno</option>
                <option value="Ci incontriamo direttamente lì">Ci incontriamo direttamente lì</option>
            </select>
        </div>
        <div class="form-group">
            <label>Periodo</label>
            <input type="text" name="mo_periodo" id="modOrg1g_periodo" class="form-control">
        </div>
        <div class="form-group">
            <label>Classe/i</label>
            <input type="text" name="mo_classi" id="modOrg1g_classi" class="form-control" placeholder="es. 3A">
        </div>
        <div class="form-group">
            <label>Giorno</label>
            <input type="date" name="mo_giorno" id="modOrg1g_giorno" class="form-control">
        </div>
        <div class="form-group">
            <label>Costo Mezzo (&euro;)</label>
            <input type="number" name="mo_costoMezzo" id="modOrg1g_costoMezzo" class="form-control" step="0.01" min="0">
        </div>
        <div class="form-group">
            <label>Costo Attività (&euro;)</label>
            <input type="number" name="mo_costoAttivita" id="modOrg1g_costoAtt" class="form-control" step="0.01" min="0">
        </div>
        <div class="form-group">
            <label>Costo a Persona (&euro;)</label>
            <input type="number" name="mo_costoAPersona" id="modOrg1g_costoAP" class="form-control" step="0.01" min="0">
        </div>
        <div class="form-group">
            <label>Num. Alunni</label>
            <input type="number" name="mo_numAlunni" id="modOrg1g_numAlunni" class="form-control" min="0">
        </div>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modalModOrg1g').classList.add('hidden')">Annulla</button>
    <button class="button" onclick="document.getElementById('formModOrg1g').submit()">💾 Salva</button>
</div>
</div>
</div>

<!-- ══════════════ MODAL — Modifica Gita 5g in Organizzazione ══════════════ -->
<div class="modal-overlay hidden" id="modalModOrg5g">
<div class="modal wide-modal">
<div class="modal-header">
    <h3 id="modOrg5gTitle">Modifica Gita Più Giorni</h3>
    <button class="close-btn" onclick="document.getElementById('modalModOrg5g').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
<form id="formModOrg5g" method="POST" action="mieGite.php">
    <input type="hidden" name="action"  value="modifica_org_5g">
    <input type="hidden" name="id_gita" id="modOrg5g_id">
    <div class="form-grid">
        <div class="form-group full-row">
            <label>Destinazione *</label>
            <input type="text" name="mo_destinazione" id="modOrg5g_dest" class="form-control" required>
        </div>
        <div class="form-group full-row">
            <label>Descrizione</label>
            <input type="text" name="mo_descrizione" id="modOrg5g_desc" class="form-control" placeholder="Breve descrizione">
        </div>
        <div class="form-group">
            <label>Mezzo di trasporto</label>
            <select name="mo_mezzo" id="modOrg5g_mezzo" class="form-control">
                <option value="">— Seleziona —</option>
                <option value="Bus GT">Bus GT</option>
                <option value="Treno">Treno</option>
                <option value="Aereo">Aereo</option>
            </select>
        </div>
        <div class="form-group">
            <label>Periodo</label>
            <input type="text" name="mo_periodo" id="modOrg5g_periodo" class="form-control">
        </div>
        <div class="form-group">
            <label>Classe/i</label>
            <input type="text" name="mo_classi" id="modOrg5g_classi" class="form-control" placeholder="es. 4B">
        </div>
        <div class="form-group">
            <label>Giorno Inizio</label>
            <input type="date" name="mo_giornoInizio" id="modOrg5g_gi" class="form-control">
        </div>
        <div class="form-group">
            <label>Giorno Fine</label>
            <input type="date" name="mo_giornoFine" id="modOrg5g_gf" class="form-control">
        </div>
        <div class="form-group">
            <label>Costo a Persona (&euro;)</label>
            <input type="number" name="mo_costoAPersona" id="modOrg5g_costoAP" class="form-control" step="0.01" min="0">
        </div>
        <div class="form-group">
            <label>Num. Alunni</label>
            <input type="number" name="mo_numAlunni" id="modOrg5g_numAlunni" class="form-control" min="0">
        </div>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modalModOrg5g').classList.add('hidden')">Annulla</button>
    <button class="button" onclick="document.getElementById('formModOrg5g').submit()">💾 Salva</button>
</div>
</div>
</div>

<!-- ══════════════ MODAL — Modifica salvata ══════════════ -->
<div class="modal-overlay hidden" id="modalModOrgOk">
<div class="modal" style="text-align:center;max-width:400px;">
<div class="modal-header" style="justify-content:center;border-bottom:none;padding-bottom:0;">
    <button class="close-btn" style="position:absolute;right:1rem;top:1rem;" onclick="document.getElementById('modalModOrgOk').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body" style="padding-top:0.5rem;">
    <div style="font-size:3rem;margin-bottom:0.75rem;">💾</div>
    <h3 style="color:var(--blue-700);margin-bottom:0.5rem;">Modifiche Salvate!</h3>
    <p style="color:#475569;">I dati della gita sono stati aggiornati con successo.</p>
</div>
<div class="modal-footer" style="justify-content:center;">
    <button class="button" onclick="document.getElementById('modalModOrgOk').classList.add('hidden')">OK</button>
</div>
</div>
</div>

<!-- ══════════════ MODAL — Modifica e Riproponi ══════════════ -->
<div class="modal-overlay hidden" id="modalModifica">
<div class="modal">
<div class="modal-header">
    <h3 id="modTitolo">Modifica Proposta</h3>
    <button class="close-btn" onclick="chiudiModifica()">&times;</button>
</div>
<div class="modal-body">
<form id="formModifica" method="POST" action="mieGite.php">
    <input type="hidden" name="action" id="modAction">
    <input type="hidden" name="id_gita" id="modId">
    <div class="form-grid">
        <div class="form-group full-row">
            <label>Destinazione *</label>
            <input type="text" name="destinazione" id="modDest" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Mezzo di trasporto</label>
            <input type="text" name="mezzo" id="modMezzo" class="form-control">
        </div>
        <div class="form-group">
            <label>Periodo</label>
            <input type="text" name="periodo" id="modPeriodo" class="form-control" placeholder="es. Marzo 2026">
        </div>
        <div class="form-group">
            <label>Costo a persona (&euro;)</label>
            <input type="number" name="costo" id="modCosto" class="form-control" step="0.01" min="0">
        </div>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="chiudiModifica()">Annulla</button>
    <button class="button" onclick="document.getElementById('formModifica').submit()">🔄 Proponi di Nuovo</button>
</div>
</div>
</div>

<!-- ══════════════ MODAL — Conferma Elimina ══════════════ -->
<div class="modal-overlay hidden" id="modalElimina">
<div class="modal">
<div class="modal-header">
    <h3>Conferma Eliminazione</h3>
    <button class="close-btn" onclick="chiudiElimina()">&times;</button>
</div>
<div class="modal-body">
    <p>Sei sicuro di voler eliminare la gita verso <strong id="elimDestTxt"></strong>? L'operazione non è reversibile.</p>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="chiudiElimina()">Annulla</button>
    <button class="button cancel" onclick="document.getElementById('formElimina').submit()">🗑️ Elimina</button>
</div>
</div>
</div>
<form id="formElimina" method="POST" action="mieGite.php" style="display:none;">
    <input type="hidden" name="action" id="elimAction">
    <input type="hidden" name="id_gita" id="elimId">
</form>

<!-- ══════════════ MODAL — Organizza Gita 1 Giorno ══════════════ -->
<div class="modal-overlay hidden" id="modalOrg1g">
<div class="modal">
<div class="modal-header">
    <h3 id="org1g_title">Organizza Gita 1 Giorno</h3>
    <button class="close-btn" onclick="document.getElementById('modalOrg1g').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
<form id="formOrg1g" method="POST" action="mieGite.php">
    <input type="hidden" name="action"  value="organizza_1g">
    <input type="hidden" name="id_gita" id="org1g_id">
    <div class="form-grid">
        <div class="form-group full-row">
            <label>Destinazione</label>
            <input type="text" id="org1g_mezzo_dest" class="form-control" readonly>
        </div>
        <div class="form-group full-row">
            <label>Descrizione</label>
            <input type="text" name="org_descrizione" id="org1g_descrizione" class="form-control" placeholder="Breve descrizione">
        </div>
        <div class="form-group">
            <label>Mezzo di trasporto</label>
            <select name="org_mezzo" id="org1g_mezzo" class="form-control">
                <option value="">— Seleziona —</option>
                <option value="Bus">Bus</option>
                <option value="Treno">Treno</option>
                <option value="Ci incontriamo direttamente lì">Ci incontriamo direttamente lì</option>
            </select>
        </div>
        <div class="form-group">
            <label>Periodo</label>
            <input type="text" name="org_periodo" id="org1g_periodo" class="form-control">
        </div>
        <div class="form-group">
            <label>Classe/i</label>
            <input type="text" name="org_classe" id="org1g_classe" class="form-control" placeholder="es. 3A">
        </div>
        <div class="form-group">
            <label>Giorno *</label>
            <input type="date" name="org_giorno" id="org1g_giorno" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Costo Mezzo (&euro;)</label>
            <input type="number" name="org_costoMezzo" id="org1g_costoMezzo" class="form-control" step="0.01" min="0">
        </div>
        <div class="form-group">
            <label>Costo Giornata (&euro;)</label>
            <input type="number" name="org_costoGiorno" id="org1g_costoGiorno" class="form-control" step="0.01" min="0">
        </div>
        <div class="form-group">
            <label>Costo a Persona (&euro;)</label>
            <input type="number" name="org_costoPersona" id="org1g_costoPersona" class="form-control" step="0.01" min="0">
        </div>
        <div class="form-group">
            <label>Num. Alunni</label>
            <input type="number" name="org_numAlunni" id="org1g_numAlunni" class="form-control" min="0">
        </div>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modalOrg1g').classList.add('hidden')">Annulla</button>
    <button class="button" onclick="document.getElementById('formOrg1g').submit()">🗓️ Organizza</button>
</div>
</div>
</div>

<!-- ══════════════ MODAL — Organizza Gita Più Giorni ══════════════ -->
<div class="modal-overlay hidden" id="modalOrg5g">
<div class="modal">
<div class="modal-header">
    <h3 id="org5g_title">Organizza Gita Più Giorni</h3>
    <button class="close-btn" onclick="document.getElementById('modalOrg5g').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
<form id="formOrg5g" method="POST" action="mieGite.php">
    <input type="hidden" name="action"  value="organizza_5g">
    <input type="hidden" name="id_gita" id="org5g_id">
    <div class="form-grid">
        <div class="form-group full-row">
            <label>Destinazione</label>
            <input type="text" id="org5g_mezzo_dest" class="form-control" readonly>
        </div>
        <div class="form-group full-row">
            <label>Descrizione</label>
            <input type="text" name="org_descrizione" id="org5g_descrizione" class="form-control" placeholder="Breve descrizione">
        </div>
        <div class="form-group">
            <label>Mezzo di trasporto</label>
            <select name="org_mezzo" id="org5g_mezzo" class="form-control">
                <option value="">— Seleziona —</option>
                <option value="Bus GT">Bus GT</option>
                <option value="Treno">Treno</option>
                <option value="Aereo">Aereo</option>
            </select>
        </div>
        <div class="form-group">
            <label>Periodo</label>
            <input type="text" name="org_periodo" id="org5g_periodo" class="form-control">
        </div>
        <div class="form-group">
            <label>Classe/i</label>
            <input type="text" name="org_classe" id="org5g_classe" class="form-control" placeholder="es. 4B">
        </div>
        <div class="form-group">
            <label>Giorno Inizio *</label>
            <input type="date" name="org_giornoInizio" id="org5g_giornoInizio" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Giorno Fine *</label>
            <input type="date" name="org_giornoFine" id="org5g_giornoFine" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Costo a Persona (&euro;)</label>
            <input type="number" name="org_costoAPersona" id="org5g_costoAPersona" class="form-control" step="0.01" min="0">
        </div>
        <div class="form-group">
            <label>Num. Alunni</label>
            <input type="number" name="org_numAlunni" id="org5g_numAlunni" class="form-control" min="0">
        </div>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modalOrg5g').classList.add('hidden')">Annulla</button>
    <button class="button" onclick="document.getElementById('formOrg5g').submit()">🗓️ Organizza</button>
</div>
</div>
</div>

<!-- ══════════════ MODAL — Organizzata con successo ══════════════ -->
<div class="modal-overlay hidden" id="modalOrganizzaOk">
<div class="modal" style="text-align:center;max-width:400px;">
<div class="modal-header" style="justify-content:center;border-bottom:none;padding-bottom:0;">
    <button class="close-btn" style="position:absolute;right:1rem;top:1rem;" onclick="document.getElementById('modalOrganizzaOk').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body" style="padding-top:0.5rem;">
    <div style="font-size:3rem;margin-bottom:0.75rem;">✅</div>
    <h3 style="color:var(--blue-700);margin-bottom:0.5rem;">Gita Organizzata!</h3>
    <p style="color:#475569;">La gita è stata messa in organizzazione con successo.</p>
</div>
<div class="modal-footer" style="justify-content:center;">
    <button class="button" onclick="document.getElementById('modalOrganizzaOk').classList.add('hidden')">OK</button>
</div>
</div>
</div>

<!-- ══════════════ MODAL — Riproposta salvata ══════════════ -->
<div class="modal-overlay hidden" id="modalRiproponiOk">
<div class="modal" style="text-align:center;max-width:400px;">
<div class="modal-header" style="justify-content:center;border-bottom:none;padding-bottom:0;">
    <button class="close-btn" style="position:absolute;right:1rem;top:1rem;" onclick="document.getElementById('modalRiproponiOk').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body" style="padding-top:0.5rem;">
    <div style="font-size:3rem;margin-bottom:0.75rem;">✅</div>
    <h3 style="color:var(--blue-700);margin-bottom:0.5rem;">Proposta Inviata!</h3>
    <p style="color:#475569;">La gita è stata rimessa in bozza e inviata per approvazione.</p>
</div>
<div class="modal-footer" style="justify-content:center;">
    <button class="button" onclick="document.getElementById('modalRiproponiOk').classList.add('hidden')">OK</button>
</div>
</div>
</div>

<!-- ══════════════ MODAL — Eliminata ══════════════ -->
<div class="modal-overlay hidden" id="modalEliminataOk">
<div class="modal" style="text-align:center;max-width:400px;">
<div class="modal-header" style="justify-content:center;border-bottom:none;padding-bottom:0;">
    <button class="close-btn" style="position:absolute;right:1rem;top:1rem;" onclick="document.getElementById('modalEliminataOk').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body" style="padding-top:0.5rem;">
    <div style="font-size:3rem;margin-bottom:0.75rem;">🗑️</div>
    <h3 style="color:#dc2626;margin-bottom:0.5rem;">Gita Eliminata</h3>
    <p style="color:#475569;">La proposta è stata eliminata correttamente.</p>
</div>
<div class="modal-footer" style="justify-content:center;">
    <button class="button" onclick="document.getElementById('modalEliminataOk').classList.add('hidden')">OK</button>
</div>
</div>
</div>

<footer><div class="footer-container"><div class="footer-left"><p><strong>Gestione Gite Scolastiche</strong></p></div></div></footer>
</div>

<!-- ══════════════ MODAL — Accompagnatori Gita 1g ══════════════ -->
<div class="modal-overlay hidden" id="modalAccompagnatori">
<div class="modal" style="max-width:480px;">
<div class="modal-header">
    <h3 id="accModalTit">Accompagnatori</h3>
    <button class="close-btn" onclick="document.getElementById('modalAccompagnatori').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
    <div id="accModalList"></div>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modalAccompagnatori').classList.add('hidden')">Chiudi</button>
</div>
</div>
</div>

<script>
function apriAccompagnatori(btn) {
    var dest = btn.dataset.dest;
    var acc  = JSON.parse(btn.dataset.acc);
    document.getElementById('accModalTit').textContent = 'Accompagnatori — ' + dest;
    var html = acc.length > 0
        ? '<ul style="list-style:none;padding:0;margin:0;">' + acc.map(function(n){ return '<li style="padding:0.4rem 0;border-bottom:1px solid #e2e8f0;">👤 ' + n + '</li>'; }).join('') + '</ul>'
        : '<p style="color:#94a3b8;text-align:center;padding:1rem 0;">Nessun accompagnatore registrato.</p>';
    document.getElementById('accModalList').innerHTML = html;
    document.getElementById('modalAccompagnatori').classList.remove('hidden');
}
</script>

<script>
function apriModOrg(btn) {
    var d = btn.dataset;
    if (d.tipo === '1g') {
        document.getElementById('modOrg1g_id').value         = d.id;
        document.getElementById('modOrg1gTitle').textContent = 'Modifica: ' + d.dest;
        document.getElementById('modOrg1g_dest').value       = d.dest;
        document.getElementById('modOrg1g_desc').value       = d.desc      || '';
        document.getElementById('modOrg1g_mezzo').value      = d.mezzo     || '';
        document.getElementById('modOrg1g_periodo').value    = d.periodo   || '';
        document.getElementById('modOrg1g_classi').value     = d.classi    || '';
        document.getElementById('modOrg1g_giorno').value     = d.giorno    || '';
        document.getElementById('modOrg1g_costoMezzo').value = d.costoMezzo || '';
        document.getElementById('modOrg1g_costoAtt').value   = d.costoAtt  || '';
        document.getElementById('modOrg1g_costoAP').value    = d.costoAp   || '';
        document.getElementById('modOrg1g_numAlunni').value  = d.numAlunni || '';
        document.getElementById('modalModOrg1g').classList.remove('hidden');
    } else {
        document.getElementById('modOrg5g_id').value         = d.id;
        document.getElementById('modOrg5gTitle').textContent = 'Modifica: ' + d.dest;
        document.getElementById('modOrg5g_dest').value       = d.dest;
        document.getElementById('modOrg5g_desc').value       = d.desc      || '';
        document.getElementById('modOrg5g_mezzo').value      = d.mezzo     || '';
        document.getElementById('modOrg5g_periodo').value    = d.periodo   || '';
        document.getElementById('modOrg5g_classi').value     = d.classi    || '';
        document.getElementById('modOrg5g_gi').value         = d.giornoInizio || '';
        document.getElementById('modOrg5g_gf').value         = d.giornoFine   || '';
        document.getElementById('modOrg5g_costoAP').value    = d.costoAp   || '';
        document.getElementById('modOrg5g_numAlunni').value  = d.numAlunni || '';
        document.getElementById('modalModOrg5g').classList.remove('hidden');
    }
}
function apriOrg(btn) {
    var d = btn.dataset;
    if (d.tipo === '1g') {
        document.getElementById('org1g_id').value           = d.id;
        document.getElementById('org1g_title').textContent  = 'Organizza: ' + d.dest;
        document.getElementById('org1g_mezzo_dest').value   = d.dest;
        document.getElementById('org1g_descrizione').value  = d.desc    || '';
        document.getElementById('org1g_mezzo').value        = d.mezzo   || '';
        document.getElementById('org1g_periodo').value      = d.periodo || '';
        document.getElementById('org1g_classe').value       = d.classi  || '';
        document.getElementById('org1g_costoPersona').value = d.costo   || '';
        document.getElementById('org1g_giorno').value       = '';
        document.getElementById('org1g_costoMezzo').value   = '';
        document.getElementById('org1g_costoGiorno').value  = '';
        document.getElementById('org1g_numAlunni').value    = '';
        document.getElementById('modalOrg1g').classList.remove('hidden');
    } else {
        document.getElementById('org5g_id').value              = d.id;
        document.getElementById('org5g_title').textContent     = 'Organizza: ' + d.dest;
        document.getElementById('org5g_mezzo_dest').value      = d.dest;
        document.getElementById('org5g_descrizione').value     = d.desc    || '';
        document.getElementById('org5g_mezzo').value           = d.mezzo   || '';
        document.getElementById('org5g_periodo').value         = d.periodo || '';
        document.getElementById('org5g_classe').value          = d.classi  || '';
        document.getElementById('org5g_costoAPersona').value   = d.costo   || '';
        document.getElementById('org5g_giornoInizio').value    = '';
        document.getElementById('org5g_giornoFine').value      = '';
        document.getElementById('org5g_numAlunni').value       = '';
        document.getElementById('modalOrg5g').classList.remove('hidden');
    }
}
function apriModifica(id, dest, mezzo, periodo, costo, tipo) {
    document.getElementById('modId').value      = id;
    document.getElementById('modDest').value    = dest;
    document.getElementById('modMezzo').value   = mezzo;
    document.getElementById('modPeriodo').value = periodo;
    document.getElementById('modCosto').value   = costo;
    document.getElementById('modAction').value  = tipo === '1g' ? 'riproponi_1g' : 'riproponi_5g';
    document.getElementById('modTitolo').textContent = 'Modifica: ' + dest;
    document.getElementById('modalModifica').classList.remove('hidden');
}
function chiudiModifica() {
    document.getElementById('modalModifica').classList.add('hidden');
}
function apriElimina(id, dest, tipo) {
    document.getElementById('elimId').value        = id;
    document.getElementById('elimAction').value    = tipo === '1g' ? 'elimina_bocciata_1g' : 'elimina_bocciata_5g';
    document.getElementById('elimDestTxt').textContent = dest;
    document.getElementById('modalElimina').classList.remove('hidden');
}
function chiudiElimina() {
    document.getElementById('modalElimina').classList.add('hidden');
}
window.addEventListener('click', function(e) {
    if (e.target === document.getElementById('modalModifica'))  chiudiModifica();
    if (e.target === document.getElementById('modalElimina'))   chiudiElimina();
    if (e.target === document.getElementById('modalOrg1g'))     document.getElementById('modalOrg1g').classList.add('hidden');
    if (e.target === document.getElementById('modalOrg5g'))     document.getElementById('modalOrg5g').classList.add('hidden');
    if (e.target === document.getElementById('modalModOrg1g'))  document.getElementById('modalModOrg1g').classList.add('hidden');
    if (e.target === document.getElementById('modalModOrg5g'))  document.getElementById('modalModOrg5g').classList.add('hidden');
    if (e.target === document.getElementById('modalAccompagnatori')) document.getElementById('modalAccompagnatori').classList.add('hidden');
});
</script>
</body>
</html>
