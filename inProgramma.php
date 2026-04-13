<?php
include('nav.php');

$ruolo           = $_SESSION['ruolo']     ?? 0;
$idUtenteLoggato = intval($_SESSION['id_utente'] ?? 0);

// ─── Handler partecipa ────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'partecipa') {
    $idGita   = intval($_POST['id_gita']   ?? 0);
    $tipoGita = ($_POST['tipo_gita'] ?? '') === 'gite5' ? '5g' : '1g';
    if ($idGita > 0 && $idUtenteLoggato > 0) {
        mysqli_query($conn, "INSERT IGNORE INTO accompagnatori (idgita, idutente, tipo_gita) VALUES ($idGita, $idUtenteLoggato, '$tipoGita')");
    }
    header("Location: inProgramma.php?partecipato=1");
    exit;
}

// ─── Handler elimina (solo commissione) ──────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'elimina' && $ruolo == 2) {
    $idGita = intval($_POST['id_gita'] ?? 0);
    $tab    = $_POST['tabella'] === 'gite5' ? 'gite5' : 'gita1g';
    if ($idGita > 0) {
        mysqli_query($conn, "DELETE FROM $tab WHERE idGita = $idGita");
        mysqli_query($conn, "DELETE FROM accompagnatori WHERE idgita = $idGita AND tipo_gita = '" . ($tab === 'gite5' ? '5g' : '1g') . "'");
    }
    header("Location: inProgramma.php");
    exit;
}

// ─── Handler modifica 1g (solo commissione) ───────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'modifica_1g' && $ruolo == 2) {
    $id   = intval($_POST['id_gita'] ?? 0);
    $dest = mysqli_real_escape_string($conn, trim($_POST['destinazione'] ?? ''));
    $desc = mysqli_real_escape_string($conn, trim($_POST['descrizione']  ?? ''));
    $mezz = mysqli_real_escape_string($conn, trim($_POST['mezzo']        ?? ''));
    $per  = mysqli_real_escape_string($conn, trim($_POST['periodo']      ?? ''));
    $cls  = mysqli_real_escape_string($conn, trim($_POST['classi']       ?? ''));
    $gio  = trim($_POST['giorno'] ?? '');
    $gio_s = $gio ? "'$gio'" : 'NULL';
    $costoM = $_POST['costoMezzo']    !== '' ? floatval($_POST['costoMezzo'])    : 'NULL';
    $costoA = $_POST['costoAttivita'] !== '' ? floatval($_POST['costoAttivita']) : 'NULL';
    $costoP = $_POST['costoAPersona'] !== '' ? floatval($_POST['costoAPersona']) : 'NULL';
    $numAl  = $_POST['numAlunni']     !== '' ? intval($_POST['numAlunni'])       : 'NULL';
    if ($id > 0) {
        mysqli_query($conn, "UPDATE gita1g SET destinazione='$dest', descrizione='$desc', mezzo='$mezz', periodo='$per', classi='$cls', giorno=$gio_s, costoMezzo=$costoM, costoAttivita=$costoA, costoAPersona=$costoP, numAlunni=$numAl WHERE idGita=$id");
    }
    header("Location: inProgramma.php");
    exit;
}

// ─── Handler modifica 5g (solo commissione) ───────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'modifica_5g' && $ruolo == 2) {
    $id   = intval($_POST['id_gita'] ?? 0);
    $dest = mysqli_real_escape_string($conn, trim($_POST['destinazione'] ?? ''));
    $desc = mysqli_real_escape_string($conn, trim($_POST['descrizione']  ?? ''));
    $mezz = mysqli_real_escape_string($conn, trim($_POST['mezzo']        ?? ''));
    $per  = mysqli_real_escape_string($conn, trim($_POST['periodo']      ?? ''));
    $cls  = mysqli_real_escape_string($conn, trim($_POST['classi']       ?? ''));
    $gi   = trim($_POST['giornoInizio'] ?? '');
    $gf   = trim($_POST['giornoFine']   ?? '');
    $gi_s = $gi ? "'$gi'" : 'NULL';
    $gf_s = $gf ? "'$gf'" : 'NULL';
    $costoP = $_POST['costoAPersona'] !== '' ? floatval($_POST['costoAPersona']) : 'NULL';
    $numAl  = $_POST['numAlunni']     !== '' ? intval($_POST['numAlunni'])       : 'NULL';
    if ($id > 0) {
        mysqli_query($conn, "UPDATE gite5 SET destinazione='$dest', descrizione='$desc', mezzo='$mezz', periodo='$per', classi='$cls', giornoInizio=$gi_s, giornoFine=$gf_s, costoAPersona=$costoP, numAlunni=$numAl WHERE idGita=$id");
    }
    header("Location: inProgramma.php");
    exit;
}

// ─── Query gite 1 giorno stato 4 ─────────────────────────────────────────────
$res1g = mysqli_query($conn,
    "SELECT g.*, CONCAT(u.Nome, ' ', u.Cognome) AS autore
     FROM gita1g g JOIN utente u ON g.idUtente = u.IDUtente
     WHERE g.idStato = 4 ORDER BY g.giorno ASC"
);

// ─── Query gite piu giorni stato 4 ───────────────────────────────────────────
$res5g = mysqli_query($conn,
    "SELECT g.*, CONCAT(u.Nome, ' ', u.Cognome) AS autore
     FROM gite5 g JOIN utente u ON g.idUtente = u.IDUtente
     WHERE g.idStato = 4 ORDER BY g.giornoInizio ASC"
);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gite in Programma</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="vetrina.css">
    <link rel="stylesheet" href="style_custom.css">
    <script src="vetrina.js" defer></script>
</head>
<body>
<div class="container">
<main class="content bozze-padding">

<div class="hero-section" style="margin-bottom:1.5rem;">
    <h2 style="margin-bottom:0.25rem;color:var(--blue-700);">Gite in Programma</h2>
    <p style="color:#64748b;margin:0;">Elenco di tutte le gite attualmente in organizzazione.</p>
</div>

<?php if (($_GET['partecipato'] ?? '') === '1'): ?>
<div class="alert alert-success" style="margin-bottom:1rem;">
    ✅ Hai aderito alla gita come accompagnatore! La trovi ora in <a href="mieGite.php"><strong>Le Mie Gite</strong></a>.
</div>
<?php endif; ?>

<!-- ══ GITE 1 GIORNO ════════════════════════════════════════════════════════ -->
<h3 style="color:var(--blue-700);margin-bottom:0.75rem;">Gite di 1 Giorno</h3>
<div class="table-section"><div class="table-container">
<table>
<thead><tr>
    <th>#</th><th>Destinazione</th><th>Mezzo</th><th>Classi</th>
    <th>Giorno</th><th>Costo/Persona</th><th>N. Alunni</th><th>Docente</th><th>Azioni</th>
</tr></thead>
<tbody>
<?php
$n = 1;
if ($res1g && mysqli_num_rows($res1g) > 0):
    while ($row = mysqli_fetch_assoc($res1g)):
        $id      = intval($row['idGita']);
        $dest    = htmlspecialchars($row['destinazione'] ?? '');
        $mezzo   = htmlspecialchars($row['mezzo']   ?? '');
        $classi  = htmlspecialchars($row['classi']  ?? '');
        $giorno  = $row['giorno'] ? date('d/m/Y', strtotime($row['giorno'])) : '—';
        $giornoV = $row['giorno'] ?? '';
        $costo   = $row['costoAPersona'] !== null ? '&euro; ' . number_format($row['costoAPersona'], 2, ',', '.') : '—';
        $numAl   = $row['numAlunni'] ?? '';
        $autore  = htmlspecialchars($row['autore'] ?? '');
        $costoMV = $row['costoMezzo']    ?? '';
        $costoAV = $row['costoAttivita'] ?? '';
        $costoPA = $row['costoAPersona'] ?? '';
        $dJ      = htmlspecialchars($row['destinazione'] ?? '', ENT_QUOTES);
        // iscritto se è accompagnatore OPPURE se è l'autore della gita
        $chk = mysqli_query($conn, "SELECT id FROM accompagnatori WHERE idgita=$id AND idutente=$idUtenteLoggato AND tipo_gita='1g'");
        $giaPart = ($chk && mysqli_num_rows($chk) > 0) || ($row['idUtente'] == $idUtenteLoggato);
?>
    <tr>
        <td><?php echo $n++; ?></td>
        <td><?php echo $dest; ?></td>
        <td><?php echo $mezzo ?: '—'; ?></td>
        <td><?php echo $classi ?: '—'; ?></td>
        <td><?php echo $giorno; ?></td>
        <td><?php echo $costo; ?></td>
        <td><?php echo $numAl ?: '—'; ?></td>
        <td><?php echo $autore; ?></td>
        <td style="display:flex;gap:0.4rem;flex-wrap:wrap;align-items:center;">
            <?php if ($giaPart): ?>
                <span class="badge badge-success" style="font-size:0.78rem;">✅ Iscritto</span>
            <?php else: ?>
                <form method="POST" action="inProgramma.php" style="margin:0;">
                    <input type="hidden" name="action"    value="partecipa">
                    <input type="hidden" name="id_gita"   value="<?php echo $id; ?>">
                    <input type="hidden" name="tipo_gita" value="gita1g">
                    <button type="submit" class="button xs">👥 Partecipa</button>
                </form>
            <?php endif; ?>
            <?php if ($ruolo == 2): ?>
            <button type="button" class="button xs"
                data-id="<?php echo $id; ?>"
                data-dest="<?php echo $dJ; ?>"
                data-desc="<?php echo htmlspecialchars($row['descrizione'] ?? '', ENT_QUOTES); ?>"
                data-mezzo="<?php echo htmlspecialchars($row['mezzo'] ?? '', ENT_QUOTES); ?>"
                data-periodo="<?php echo htmlspecialchars($row['periodo'] ?? '', ENT_QUOTES); ?>"
                data-classi="<?php echo htmlspecialchars($row['classi'] ?? '', ENT_QUOTES); ?>"
                data-giorno="<?php echo $giornoV; ?>"
                data-costo-mezzo="<?php echo $costoMV; ?>"
                data-costo-att="<?php echo $costoAV; ?>"
                data-costo-ap="<?php echo $costoPA; ?>"
                data-num-alunni="<?php echo $numAl; ?>"
                onclick="apriMod1g(this)">✏️ Modifica</button>
            <button type="button" class="button cancel xs"
                data-id="<?php echo $id; ?>"
                data-dest="<?php echo $dJ; ?>"
                data-tab="gita1g"
                onclick="apriElimina(this)">🗑️ Elimina</button>
            <?php endif; ?>
        </td>
    </tr>
<?php endwhile; else: ?>
    <tr><td colspan="9" style="text-align:center;color:#94a3b8;">Nessuna gita di 1 giorno in organizzazione.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div></div>

<!-- ══ GITE PIU GIORNI ══════════════════════════════════════════════════════ -->
<h3 style="color:var(--blue-700);margin:2rem 0 0.75rem;">Gite di Più Giorni</h3>
<div class="table-section"><div class="table-container">
<table>
<thead><tr>
    <th>#</th><th>Destinazione</th><th>Mezzo</th><th>Classi</th>
    <th>Dal</th><th>Al</th><th>Costo/Persona</th><th>N. Alunni</th><th>Docente</th><th>Azioni</th>
</tr></thead>
<tbody>
<?php
$n = 1;
if ($res5g && mysqli_num_rows($res5g) > 0):
    while ($row = mysqli_fetch_assoc($res5g)):
        $id     = intval($row['idGita']);
        $dest   = htmlspecialchars($row['destinazione'] ?? '');
        $mezzo  = htmlspecialchars($row['mezzo']   ?? '');
        $classi = htmlspecialchars($row['classi']  ?? '');
        $gi     = $row['giornoInizio'] ? date('d/m/Y', strtotime($row['giornoInizio'])) : '—';
        $gf     = $row['giornoFine']   ? date('d/m/Y', strtotime($row['giornoFine']))   : '—';
        $giV    = $row['giornoInizio'] ?? '';
        $gfV    = $row['giornoFine']   ?? '';
        $costo  = $row['costoAPersona'] !== null ? '&euro; ' . number_format($row['costoAPersona'], 2, ',', '.') : '—';
        $numAl  = $row['numAlunni'] ?? '';
        $autore = htmlspecialchars($row['autore'] ?? '');
        $costoPA = $row['costoAPersona'] ?? '';
        $dJ     = htmlspecialchars($row['destinazione'] ?? '', ENT_QUOTES);
        // iscritto se è accompagnatore OPPURE se è l'autore della gita
        $chk = mysqli_query($conn, "SELECT id FROM accompagnatori WHERE idgita=$id AND idutente=$idUtenteLoggato AND tipo_gita='5g'");
        $giaPart = ($chk && mysqli_num_rows($chk) > 0) || ($row['idUtente'] == $idUtenteLoggato);
?>
    <tr>
        <td><?php echo $n++; ?></td>
        <td><?php echo $dest; ?></td>
        <td><?php echo $mezzo ?: '—'; ?></td>
        <td><?php echo $classi ?: '—'; ?></td>
        <td><?php echo $gi; ?></td>
        <td><?php echo $gf; ?></td>
        <td><?php echo $costo; ?></td>
        <td><?php echo $numAl ?: '—'; ?></td>
        <td><?php echo $autore; ?></td>
        <td style="display:flex;gap:0.4rem;flex-wrap:wrap;align-items:center;">
            <?php if ($giaPart): ?>
                <span class="badge badge-success" style="font-size:0.78rem;">✅ Iscritto</span>
            <?php else: ?>
                <form method="POST" action="inProgramma.php" style="margin:0;">
                    <input type="hidden" name="action"    value="partecipa">
                    <input type="hidden" name="id_gita"   value="<?php echo $id; ?>">
                    <input type="hidden" name="tipo_gita" value="gite5">
                    <button type="submit" class="button xs">👥 Partecipa</button>
                </form>
            <?php endif; ?>
            <?php if ($ruolo == 2): ?>
            <button type="button" class="button xs"
                data-id="<?php echo $id; ?>"
                data-dest="<?php echo $dJ; ?>"
                data-desc="<?php echo htmlspecialchars($row['descrizione'] ?? '', ENT_QUOTES); ?>"
                data-mezzo="<?php echo htmlspecialchars($row['mezzo'] ?? '', ENT_QUOTES); ?>"
                data-periodo="<?php echo htmlspecialchars($row['periodo'] ?? '', ENT_QUOTES); ?>"
                data-classi="<?php echo htmlspecialchars($row['classi'] ?? '', ENT_QUOTES); ?>"
                data-gi="<?php echo $giV; ?>"
                data-gf="<?php echo $gfV; ?>"
                data-costo-ap="<?php echo $costoPA; ?>"
                data-num-alunni="<?php echo $numAl; ?>"
                onclick="apriMod5g(this)">✏️ Modifica</button>
            <button type="button" class="button cancel xs"
                data-id="<?php echo $id; ?>"
                data-dest="<?php echo $dJ; ?>"
                data-tab="gite5"
                onclick="apriElimina(this)">🗑️ Elimina</button>
            <?php endif; ?>
        </td>
    </tr>
<?php endwhile; else: ?>
    <tr><td colspan="10" style="text-align:center;color:#94a3b8;">Nessuna gita di più giorni in organizzazione.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div></div>

</main>

<!-- ══════════════ MODAL — Conferma Elimina ══════════════════════════════════ -->
<div class="modal-overlay hidden" id="modalElimina">
<div class="modal" style="max-width:420px;">
<div class="modal-header">
    <h3>Conferma eliminazione</h3>
    <button class="close-btn" onclick="chiudi('modalElimina')">&times;</button>
</div>
<div class="modal-body" style="text-align:center;">
    <p style="font-size:1rem;margin-bottom:0.5rem;">Stai per eliminare la gita:</p>
    <p style="font-weight:600;color:var(--blue-700);font-size:1.1rem;" id="elimDest"></p>
    <p style="color:#64748b;font-size:0.9rem;">Questa azione non può essere annullata.</p>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="chiudi('modalElimina')">Annulla</button>
    <form id="formElimina" method="POST" action="inProgramma.php" style="margin:0;">
        <input type="hidden" name="action"  value="elimina">
        <input type="hidden" name="id_gita" id="elimId">
        <input type="hidden" name="tabella" id="elimTab">
        <button type="submit" class="button cancel">Sì, elimina</button>
    </form>
</div>
</div>
</div>

<!-- ══════════════ MODAL — Modifica Gita 1 Giorno ════════════════════════════ -->
<div class="modal-overlay hidden" id="modalMod1g">
<div class="modal wide-modal">
<div class="modal-header">
    <h3 id="modTit1g">Modifica Gita 1 Giorno</h3>
    <button class="close-btn" onclick="chiudi('modalMod1g')">&times;</button>
</div>
<div class="modal-body">
<form id="formMod1g" method="POST" action="inProgramma.php">
    <input type="hidden" name="action"  value="modifica_1g">
    <input type="hidden" name="id_gita" id="m1g_id">
    <div class="form-grid">
        <div class="form-group full-row">
            <label>Destinazione *</label>
            <input type="text" name="destinazione" id="m1g_dest" class="form-control" required>
        </div>
        <div class="form-group full-row">
            <label>Descrizione</label>
            <input type="text" name="descrizione" id="m1g_desc" class="form-control">
        </div>
        <div class="form-group">
            <label>Mezzo di trasporto</label>
            <select name="mezzo" id="m1g_mezzo" class="form-control">
                <option value="">— Seleziona —</option>
                <option value="Bus">Bus</option>
                <option value="Treno">Treno</option>
                <option value="Ci incontriamo direttamente li">Ci incontriamo direttamente lì</option>
            </select>
        </div>
        <div class="form-group">
            <label>Periodo</label>
            <input type="text" name="periodo" id="m1g_periodo" class="form-control">
        </div>
        <div class="form-group">
            <label>Classe/i</label>
            <input type="text" name="classi" id="m1g_classi" class="form-control">
        </div>
        <div class="form-group">
            <label>Giorno</label>
            <input type="date" name="giorno" id="m1g_giorno" class="form-control">
        </div>
        <div class="form-group">
            <label>Costo Mezzo (&euro;)</label>
            <input type="number" name="costoMezzo" id="m1g_costoMezzo" class="form-control" step="0.01" min="0">
        </div>
        <div class="form-group">
            <label>Costo Attività (&euro;)</label>
            <input type="number" name="costoAttivita" id="m1g_costoAtt" class="form-control" step="0.01" min="0">
        </div>
        <div class="form-group">
            <label>Costo a Persona (&euro;)</label>
            <input type="number" name="costoAPersona" id="m1g_costoAP" class="form-control" step="0.01" min="0">
        </div>
        <div class="form-group">
            <label>Num. Alunni</label>
            <input type="number" name="numAlunni" id="m1g_numAlunni" class="form-control" min="0">
        </div>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="chiudi('modalMod1g')">Annulla</button>
    <button class="button" onclick="document.getElementById('formMod1g').submit()">Salva modifiche</button>
</div>
</div>
</div>

<!-- ══════════════ MODAL — Modifica Gita Più Giorni ══════════════════════════ -->
<div class="modal-overlay hidden" id="modalMod5g">
<div class="modal wide-modal">
<div class="modal-header">
    <h3 id="modTit5g">Modifica Gita Più Giorni</h3>
    <button class="close-btn" onclick="chiudi('modalMod5g')">&times;</button>
</div>
<div class="modal-body">
<form id="formMod5g" method="POST" action="inProgramma.php">
    <input type="hidden" name="action"  value="modifica_5g">
    <input type="hidden" name="id_gita" id="m5g_id">
    <div class="form-grid">
        <div class="form-group full-row">
            <label>Destinazione *</label>
            <input type="text" name="destinazione" id="m5g_dest" class="form-control" required>
        </div>
        <div class="form-group full-row">
            <label>Descrizione</label>
            <input type="text" name="descrizione" id="m5g_desc" class="form-control">
        </div>
        <div class="form-group">
            <label>Mezzo di trasporto</label>
            <select name="mezzo" id="m5g_mezzo" class="form-control">
                <option value="">— Seleziona —</option>
                <option value="Bus GT">Bus GT</option>
                <option value="Treno">Treno</option>
                <option value="Aereo">Aereo</option>
            </select>
        </div>
        <div class="form-group">
            <label>Periodo</label>
            <input type="text" name="periodo" id="m5g_periodo" class="form-control">
        </div>
        <div class="form-group">
            <label>Classe/i</label>
            <input type="text" name="classi" id="m5g_classi" class="form-control">
        </div>
        <div class="form-group">
            <label>Giorno Inizio</label>
            <input type="date" name="giornoInizio" id="m5g_gi" class="form-control">
        </div>
        <div class="form-group">
            <label>Giorno Fine</label>
            <input type="date" name="giornoFine" id="m5g_gf" class="form-control">
        </div>
        <div class="form-group">
            <label>Costo a Persona (&euro;)</label>
            <input type="number" name="costoAPersona" id="m5g_costoAP" class="form-control" step="0.01" min="0">
        </div>
        <div class="form-group">
            <label>Num. Alunni</label>
            <input type="number" name="numAlunni" id="m5g_numAlunni" class="form-control" min="0">
        </div>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="chiudi('modalMod5g')">Annulla</button>
    <button class="button" onclick="document.getElementById('formMod5g').submit()">Salva modifiche</button>
</div>
</div>
</div>

<footer><div class="footer-container"><div class="footer-left"><p><strong>Gestione Gite Scolastiche</strong></p></div></div></footer>
</div>

<script>
function chiudi(id) { document.getElementById(id).classList.add('hidden'); }

window.addEventListener('click', function(e) {
    ['modalElimina','modalMod1g','modalMod5g'].forEach(function(id) {
        var m = document.getElementById(id);
        if (e.target === m) m.classList.add('hidden');
    });
});

function apriElimina(btn) {
    var d = btn.dataset;
    document.getElementById('elimDest').textContent = d.dest;
    document.getElementById('elimId').value  = d.id;
    document.getElementById('elimTab').value = d.tab;
    document.getElementById('modalElimina').classList.remove('hidden');
}

function apriMod1g(btn) {
    var d = btn.dataset;
    document.getElementById('modTit1g').textContent    = 'Modifica: ' + d.dest;
    document.getElementById('m1g_id').value            = d.id;
    document.getElementById('m1g_dest').value          = d.dest;
    document.getElementById('m1g_desc').value          = d.desc       || '';
    document.getElementById('m1g_mezzo').value         = d.mezzo      || '';
    document.getElementById('m1g_periodo').value       = d.periodo    || '';
    document.getElementById('m1g_classi').value        = d.classi     || '';
    document.getElementById('m1g_giorno').value        = d.giorno     || '';
    document.getElementById('m1g_costoMezzo').value    = d.costoMezzo || '';
    document.getElementById('m1g_costoAtt').value      = d.costoAtt   || '';
    document.getElementById('m1g_costoAP').value       = d.costoAp    || '';
    document.getElementById('m1g_numAlunni').value     = d.numAlunni  || '';
    document.getElementById('modalMod1g').classList.remove('hidden');
}

function apriMod5g(btn) {
    var d = btn.dataset;
    document.getElementById('modTit5g').textContent    = 'Modifica: ' + d.dest;
    document.getElementById('m5g_id').value            = d.id;
    document.getElementById('m5g_dest').value          = d.dest;
    document.getElementById('m5g_desc').value          = d.desc      || '';
    document.getElementById('m5g_mezzo').value         = d.mezzo     || '';
    document.getElementById('m5g_periodo').value       = d.periodo   || '';
    document.getElementById('m5g_classi').value        = d.classi    || '';
    document.getElementById('m5g_gi').value            = d.gi        || '';
    document.getElementById('m5g_gf').value            = d.gf        || '';
    document.getElementById('m5g_costoAP').value       = d.costoAp   || '';
    document.getElementById('m5g_numAlunni').value     = d.numAlunni || '';
    document.getElementById('modalMod5g').classList.remove('hidden');
}
</script>
</body>
</html>
