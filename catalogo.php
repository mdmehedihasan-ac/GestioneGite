<?php
include('nav.php');

$messaggio = "";

// ─── NUOVA PROPOSTA GITA 1 GIORNO ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'nuova_1g') {
    $idUtente     = $_SESSION['id_utente'];
    $destinazione = $_POST['destinazione'];
    $mezzo        = $_POST['mezzo'];
    $periodo      = $_POST['periodo'];
    $costo        = floatval($_POST['costo']);

    $stmt = $conn->prepare("INSERT INTO gita1g (idUtente, destinazione, mezzo, periodo, costoAPersona, idStato) VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("isssd", $idUtente, $destinazione, $mezzo, $periodo, $costo);
    if ($stmt->execute()) {
        $messaggio = "<div class='alert-success'>Proposta gita 1 giorno salvata come bozza.</div>";
    } else {
        $messaggio = "<div class='alert-error'>Errore durante il salvataggio.</div>";
    }
    $stmt->close();
}

// ─── NUOVA PROPOSTA GITA PIU GIORNI ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'nuova_5g') {
    $idUtente     = $_SESSION['id_utente'];
    $destinazione = $_POST['destinazione'];
    $mezzo        = $_POST['mezzo'];
    $periodo      = $_POST['periodo'];
    $costo        = floatval($_POST['costo']);

    $stmt = $conn->prepare("INSERT INTO gite5 (idUtente, destinazione, mezzo, periodo, costoAPersona, idStato) VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("isssd", $idUtente, $destinazione, $mezzo, $periodo, $costo);
    if ($stmt->execute()) {
        $messaggio = "<div class='alert-success'>Proposta gita di più giorni salvata come bozza.</div>";
    } else {
        $messaggio = "<div class='alert-error'>Errore durante il salvataggio.</div>";
    }
    $stmt->close();
}

// ─── ORGANIZZA GITA 1 GIORNO (copia con stato 4) ─────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'organizza_1g') {
    $idGita    = intval($_POST['id_gita']);
    $idUtente  = $_SESSION['id_utente'];
    $periodo   = $_POST['org_periodo']   ?: null;
    $giorno    = $_POST['org_giorno']    ?: null;
    $costoMezzo= $_POST['org_costoMezzo'] !== '' ? floatval($_POST['org_costoMezzo']) : null;
    $costoGiorno= $_POST['org_costoGiorno'] !== '' ? floatval($_POST['org_costoGiorno']) : null;
    $numAlunni = $_POST['org_numAlunni'] !== '' ? intval($_POST['org_numAlunni']) : null;

    // Leggi la riga originale
    $orig = $conn->query("SELECT * FROM gita1g WHERE idGita = $idGita")->fetch_assoc();
    if ($orig) {
        $dest   = $orig['destinazione'];
        $mezzoO = $orig['mezzo'];
        $perFin = $periodo ?? $orig['periodo'];
        $costoA = $orig['costoAPersona'];
        // uso query diretta con valori sanificati
        $perFin_s    = $conn->real_escape_string($perFin ?? '');
        $giorno_s    = $giorno    ? "'" . $conn->real_escape_string($giorno) . "'" : "NULL";
        $costoMezzo_s= $costoMezzo  !== null ? floatval($costoMezzo)  : "NULL";
        $costoGiorno_s= $costoGiorno !== null ? floatval($costoGiorno) : "NULL";
        $numAlunni_s = $numAlunni !== null ? intval($numAlunni) : "NULL";
        $dest_s      = $conn->real_escape_string($dest);
        $mezzoO_s    = $conn->real_escape_string($mezzoO ?? '');
        $costoA_s    = floatval($costoA);

        $sql = "INSERT INTO gita1g (idUtente, destinazione, mezzo, periodo, giorno, costoMezzo, costoAttivita, costoAPersona, numAlunni, idStato)
                VALUES ($idUtente, '$dest_s', '$mezzoO_s', '$perFin_s', $giorno_s, $costoMezzo_s, $costoGiorno_s, $costoA_s, $numAlunni_s, 4)";
        if ($conn->query($sql)) {
            $messaggio = "organizza_ok";
        } else {
            $messaggio = "<div class='alert-error'>Errore: " . htmlspecialchars($conn->error) . "</div>";
        }
    }
}

// ─── ORGANIZZA GITA PIU GIORNI (copia con stato 4) ───────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'organizza_5g') {
    $idGita       = intval($_POST['id_gita']);
    $idUtente     = $_SESSION['id_utente'];
    $periodo      = $_POST['org_periodo']      ?: null;
    $giornoInizio = $_POST['org_giornoInizio'] ?: null;
    $giornoFine   = $_POST['org_giornoFine']   ?: null;
    $costoAPersona= $_POST['org_costoAPersona'] !== '' ? floatval($_POST['org_costoAPersona']) : null;
    $numAlunni    = $_POST['org_numAlunni']    !== '' ? intval($_POST['org_numAlunni'])    : null;

    $orig = $conn->query("SELECT * FROM gite5 WHERE idGita = $idGita")->fetch_assoc();
    if ($orig) {
        $dest_s      = $conn->real_escape_string($orig['destinazione']);
        $mezzoO_s    = $conn->real_escape_string($orig['mezzo'] ?? '');
        $perFin_s    = $conn->real_escape_string($periodo ?? $orig['periodo'] ?? '');
        $gi_s        = $giornoInizio ? "'" . $conn->real_escape_string($giornoInizio) . "'" : "NULL";
        $gf_s        = $giornoFine   ? "'" . $conn->real_escape_string($giornoFine)   . "'" : "NULL";
        $costoFin    = $costoAPersona !== null ? floatval($costoAPersona) : floatval($orig['costoAPersona'] ?? 0);
        $numAlunni_s = $numAlunni !== null ? intval($numAlunni) : "NULL";

        $sql = "INSERT INTO gite5 (idUtente, destinazione, mezzo, periodo, giornoInizio, giornoFine, costoAPersona, numAlunni, idStato)
                VALUES ($idUtente, '$dest_s', '$mezzoO_s', '$perFin_s', $gi_s, $gf_s, $costoFin, $numAlunni_s, 4)";
        if ($conn->query($sql)) {
            $messaggio = "organizza_ok";
        } else {
            $messaggio = "<div class='alert-error'>Errore: " . htmlspecialchars($conn->error) . "</div>";
        }
    }
}

// ─── MODIFICA GITA 1 GIORNO ──────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'modifica_1g') {
    if ($_SESSION['ruolo'] == 2) {
        $idGita       = intval($_POST['id_gita']);
        $destinazione = $conn->real_escape_string($_POST['mod_destinazione']);
        $mezzo        = $conn->real_escape_string($_POST['mod_mezzo']);
        $periodo      = $conn->real_escape_string($_POST['mod_periodo']);
        $costo        = floatval($_POST['mod_costo']);
        if ($conn->query("UPDATE gita1g SET destinazione='$destinazione', mezzo='$mezzo', periodo='$periodo', costoAPersona=$costo WHERE idGita=$idGita")) {
            $messaggio = "<div class='alert-success'>Gita 1 giorno modificata.</div>";
        } else {
            $messaggio = "<div class='alert-error'>Errore modifica: " . htmlspecialchars($conn->error) . "</div>";
        }
    }
}

// ─── ELIMINA GITA 1 GIORNO ───────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'elimina_1g') {
    if ($_SESSION['ruolo'] == 2) {
        $idGita = intval($_POST['id_gita']);
        if ($conn->query("DELETE FROM gita1g WHERE idGita=$idGita")) {
            $messaggio = "<div class='alert-success'>Gita 1 giorno eliminata.</div>";
        } else {
            $messaggio = "<div class='alert-error'>Errore eliminazione: " . htmlspecialchars($conn->error) . "</div>";
        }
    }
}

// ─── MODIFICA GITA PIU GIORNI ─────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'modifica_5g') {
    if ($_SESSION['ruolo'] == 2) {
        $idGita       = intval($_POST['id_gita']);
        $destinazione = $conn->real_escape_string($_POST['mod_destinazione']);
        $mezzo        = $conn->real_escape_string($_POST['mod_mezzo']);
        $periodo      = $conn->real_escape_string($_POST['mod_periodo']);
        $costo        = floatval($_POST['mod_costo']);
        if ($conn->query("UPDATE gite5 SET destinazione='$destinazione', mezzo='$mezzo', periodo='$periodo', costoAPersona=$costo WHERE idGita=$idGita")) {
            $messaggio = "<div class='alert-success'>Gita di più giorni modificata.</div>";
        } else {
            $messaggio = "<div class='alert-error'>Errore modifica: " . htmlspecialchars($conn->error) . "</div>";
        }
    }
}

// ─── ELIMINA GITA PIU GIORNI ──────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'elimina_5g') {
    if ($_SESSION['ruolo'] == 2) {
        $idGita = intval($_POST['id_gita']);
        if ($conn->query("DELETE FROM gite5 WHERE idGita=$idGita")) {
            $messaggio = "<div class='alert-success'>Gita di più giorni eliminata.</div>";
        } else {
            $messaggio = "<div class='alert-error'>Errore eliminazione: " . htmlspecialchars($conn->error) . "</div>";
        }
    }
}

// ─── QUERY GITE 1 GIORNO (stato 2 = Approvate) ───────────────────────────────
$gite1g = $conn->query("
    SELECT g.idGita, g.destinazione, g.mezzo, g.periodo, g.costoAPersona,
           u.Nome, u.Cognome
    FROM gita1g g
    JOIN utente u ON g.idUtente = u.IDUtente
    WHERE g.idStato = 2
    ORDER BY g.idGita DESC
");

// ─── QUERY GITE PIU GIORNI (stato 2 = Approvate) ─────────────────────────────
$gite5g = $conn->query("
    SELECT g.idGita, g.destinazione, g.mezzo, g.periodo, g.costoAPersona,
           u.Nome, u.Cognome
    FROM gite5 g
    JOIN utente u ON g.idUtente = u.IDUtente
    WHERE g.idStato = 2
    ORDER BY g.idGita DESC
");

$tot1g = $gite1g ? $gite1g->num_rows : 0;
$tot5g = $gite5g ? $gite5g->num_rows : 0;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposte Gite</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="vetrina.css">
    <link rel="stylesheet" href="style_custom.css">
    <script src="vetrina.js" defer></script>
    <script>
    function apriOrg1g(btn) {
        var d = btn.dataset;
        document.getElementById('org1g_id').value             = d.id;
        document.getElementById('org1g_title').textContent    = 'Organizza: ' + d.dest;
        document.getElementById('org1g_mezzo_dest').value     = d.dest;
        document.getElementById('org1g_mezzo').value          = d.mezzo    || '';
        document.getElementById('org1g_periodo').value        = d.periodo  || '';
        document.getElementById('org1g_costoPersona').value   = d.costo    || '';
        document.getElementById('org1g_giorno').value         = '';
        document.getElementById('org1g_costoMezzo').value     = '';
        document.getElementById('org1g_costoGiorno').value    = '';
        document.getElementById('org1g_numAlunni').value      = '';
        document.getElementById('modalOrg1g').classList.remove('hidden');
    }
    function apriOrg5g(btn) {
        var d = btn.dataset;
        document.getElementById('org5g_id').value             = d.id;
        document.getElementById('org5g_title').textContent    = 'Organizza: ' + d.dest;
        document.getElementById('org5g_mezzo_dest').value     = d.dest;
        document.getElementById('org5g_mezzo').value          = d.mezzo    || '';
        document.getElementById('org5g_periodo').value        = d.periodo  || '';
        document.getElementById('org5g_costoAPersona').value  = d.costo    || '';
        document.getElementById('org5g_giornoInizio').value   = '';
        document.getElementById('org5g_giornoFine').value     = '';
        document.getElementById('org5g_numAlunni').value      = '';
        document.getElementById('modalOrg5g').classList.remove('hidden');
    }
    function apriModifica1g(btn) {
        var d = btn.dataset;
        document.getElementById('mod1g_id').value          = d.id;
        document.getElementById('mod1g_destinazione').value = d.dest;
        document.getElementById('mod1g_mezzo').value        = d.mezzo   || '';
        document.getElementById('mod1g_periodo').value      = d.periodo || '';
        document.getElementById('mod1g_costo').value        = d.costo   || '';
        document.getElementById('modalMod1g').classList.remove('hidden');
    }
    function apriModifica5g(btn) {
        var d = btn.dataset;
        document.getElementById('mod5g_id').value          = d.id;
        document.getElementById('mod5g_destinazione').value = d.dest;
        document.getElementById('mod5g_mezzo').value        = d.mezzo   || '';
        document.getElementById('mod5g_periodo').value      = d.periodo || '';
        document.getElementById('mod5g_costo').value        = d.costo   || '';
        document.getElementById('modalMod5g').classList.remove('hidden');
    }
    function apriElimina(id, dest, tabella) {
        document.getElementById('elimId').value      = id;
        document.getElementById('elimTabella').value = tabella;
        document.getElementById('elimDest').textContent = dest;
        document.getElementById('modalElimina').classList.remove('hidden');
    }
    </script>
</head>
<body>
<div class="container">
<main class="content bozze-padding">

<div class="hero-section">
    <h2 style="margin-bottom:0.5rem;color:var(--blue-700);">Proposte Gite</h2>
    <p>Gite approvate disponibili per l'organizzazione.</p>
</div>

<?php if ($messaggio && $messaggio !== 'organizza_ok') echo $messaggio; ?>
<?php if ($messaggio === 'organizza_ok'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('modalOrganizzaOk').classList.remove('hidden');
});
</script>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════════
     SEZIONE GITE 1 GIORNO
═══════════════════════════════════════════════════════════════ -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-top:2rem;margin-bottom:1rem;">
    <h3 style="color:var(--blue-700);margin:0;">Gite di 1 Giorno <span style="font-size:.85rem;font-weight:400;color:#6b7280;">(<?= $tot1g ?> approvate)</span></h3>
    <button class="button" onclick="document.getElementById('modal1g').classList.remove('hidden')">+ Nuova Proposta</button>
</div>

<div class="table-section" style="margin-bottom:3rem;"><div class="table-container">
<table>
<thead><tr>
    <th>#</th>
    <th>Destinazione</th>
    <th>Mezzo</th>
    <th>Periodo</th>
    <th>Costo a Persona</th>
    <th>Proposta da</th>
    <th>Organizza</th>
    <?php if ($_SESSION['ruolo'] == 2): ?><th>Azioni</th><?php endif; ?>
</tr></thead>
<tbody>
<?php
$n = 1;
if ($gite1g && $gite1g->num_rows > 0) {
    while ($r = $gite1g->fetch_assoc()) {
        $dest   = htmlspecialchars($r['destinazione']);
        $mezzo  = htmlspecialchars($r['mezzo'] ?? '—');
        $per    = htmlspecialchars($r['periodo'] ?? '—');
        $costo  = number_format($r['costoAPersona'], 2, ',', '.');
        $autore = htmlspecialchars($r['Nome'] . ' ' . $r['Cognome']);
        $id     = intval($r['idGita']);
        $destJs  = addslashes($r['destinazione']);
        $mezzoJs = addslashes($r['mezzo'] ?? '');
        $perJs   = addslashes($r['periodo'] ?? '');
        $costoJs = floatval($r['costoAPersona']);
        $azioniCol = '';
        if ($_SESSION['ruolo'] == 2) {
            $azioniCol = "<td style='display:flex;gap:0.4rem;'>
                <button type='button' class='button xs'
                    data-id='$id' data-dest='$destJs' data-mezzo='$mezzoJs'
                    data-periodo='$perJs' data-costo='$costoJs'
                    onclick=\"apriModifica1g(this)\">Modifica</button>
                <button type='button' class='button cancel xs'
                    onclick=\"apriElimina($id,'$destJs','elimina_1g')\">Elimina</button>
            </td>";
        }
        echo "<tr>
            <td>$n</td>
            <td>$dest</td>
            <td>$mezzo</td>
            <td>$per</td>
            <td>&euro; $costo</td>
            <td>$autore</td>
            <td><button type='button' class='button xs'
                data-id='$id'
                data-dest='$destJs'
                data-mezzo='$mezzoJs'
                data-periodo='$perJs'
                data-costo='$costoJs'
                onclick=\"apriOrg1g(this)\">Organizza</button></td>
            $azioniCol
        </tr>";
        $n++;
    }
} else {
    echo "<tr><td colspan='8' style='text-align:center;'>Nessuna gita di 1 giorno approvata al momento.</td></tr>";
}
?>
</tbody>
</table>
</div></div>

<!-- ═══════════════════════════════════════════════════════════════
     SEZIONE GITE PIU GIORNI
═══════════════════════════════════════════════════════════════ -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
    <h3 style="color:var(--blue-700);margin:0;">Gite di più Giorni <span style="font-size:.85rem;font-weight:400;color:#6b7280;">(<?= $tot5g ?> approvate)</span></h3>
    <button class="button" onclick="document.getElementById('modal5g').classList.remove('hidden')">+ Nuova Proposta</button>
</div>

<div class="table-section"><div class="table-container">
<table>
<thead><tr>
    <th>#</th>
    <th>Destinazione</th>
    <th>Mezzo</th>
    <th>Periodo</th>
    <th>Costo a Persona</th>
    <th>Proposta da</th>
    <th>Organizza</th>
    <?php if ($_SESSION['ruolo'] == 2): ?><th>Azioni</th><?php endif; ?>
</tr></thead>
<tbody>
<?php
$n = 1;
if ($gite5g && $gite5g->num_rows > 0) {
    while ($r = $gite5g->fetch_assoc()) {
        $dest   = htmlspecialchars($r['destinazione']);
        $mezzo  = htmlspecialchars($r['mezzo'] ?? '—');
        $per    = htmlspecialchars($r['periodo'] ?? '—');
        $costo  = number_format($r['costoAPersona'], 2, ',', '.');
        $autore = htmlspecialchars($r['Nome'] . ' ' . $r['Cognome']);
        $id     = intval($r['idGita']);
        $destJs  = addslashes($r['destinazione']);
        $mezzoJs = addslashes($r['mezzo'] ?? '');
        $perJs   = addslashes($r['periodo'] ?? '');
        $costoJs = floatval($r['costoAPersona']);
        $azioniCol = '';
        if ($_SESSION['ruolo'] == 2) {
            $azioniCol = "<td style='display:flex;gap:0.4rem;'>
                <button type='button' class='button xs'
                    data-id='$id' data-dest='$destJs' data-mezzo='$mezzoJs'
                    data-periodo='$perJs' data-costo='$costoJs'
                    onclick=\"apriModifica5g(this)\">Modifica</button>
                <button type='button' class='button cancel xs'
                    onclick=\"apriElimina($id,'$destJs','elimina_5g')\">Elimina</button>
            </td>";
        }
        echo "<tr>
            <td>$n</td>
            <td>$dest</td>
            <td>$mezzo</td>
            <td>$per</td>
            <td>&euro; $costo</td>
            <td>$autore</td>
            <td><button type='button' class='button xs'
                data-id='$id'
                data-dest='$destJs'
                data-mezzo='$mezzoJs'
                data-periodo='$perJs'
                data-costo='$costoJs'
                onclick=\"apriOrg5g(this)\">Organizza</button></td>
            $azioniCol
        </tr>";
        $n++;
    }
} else {
    echo "<tr><td colspan='8' style='text-align:center;'>Nessuna gita di più giorni approvata al momento.</td></tr>";
}
?>
</tbody>
</table>
</div></div>

</main>


<!-- ═══════════════════════════════════════════════════════════════
     MODAL — Nuova Proposta Gita 1 Giorno
═══════════════════════════════════════════════════════════════ -->
<div class="modal-overlay hidden" id="modal1g">
<div class="modal">
<div class="modal-header">
    <h3>Nuova Proposta — Gita 1 Giorno</h3>
    <button class="close-btn" onclick="document.getElementById('modal1g').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
<form method="POST" action="catalogo.php" id="form1g">
    <input type="hidden" name="action" value="nuova_1g">
    <div class="form-grid">
        <div class="form-group">
            <label>Destinazione *</label>
            <input type="text" name="destinazione" class="form-control" required placeholder="es. Roma">
        </div>
        <div class="form-group">
            <label>Mezzo di trasporto</label>
            <input type="text" name="mezzo" class="form-control" placeholder="es. Pullman">
        </div>
        <div class="form-group">
            <label>Periodo</label>
            <input type="text" name="periodo" class="form-control" placeholder="es. Marzo 2026">
        </div>
        <div class="form-group">
            <label>Costo a persona (&euro;) *</label>
            <input type="number" name="costo" class="form-control" step="0.01" min="0" required placeholder="es. 45.00">
        </div>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modal1g').classList.add('hidden')">Annulla</button>
    <button class="button" onclick="document.getElementById('form1g').submit()">Salva Proposta</button>
</div>
</div>
</div>


<!-- ═══════════════════════════════════════════════════════════════
     MODAL — Nuova Proposta Gita più Giorni
═══════════════════════════════════════════════════════════════ -->
<div class="modal-overlay hidden" id="modal5g">
<div class="modal">
<div class="modal-header">
    <h3>Nuova Proposta — Gita più Giorni</h3>
    <button class="close-btn" onclick="document.getElementById('modal5g').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
<form method="POST" action="catalogo.php" id="form5g">
    <input type="hidden" name="action" value="nuova_5g">
    <div class="form-grid">
        <div class="form-group">
            <label>Destinazione *</label>
            <input type="text" name="destinazione" class="form-control" required placeholder="es. Parigi">
        </div>
        <div class="form-group">
            <label>Mezzo di trasporto</label>
            <input type="text" name="mezzo" class="form-control" placeholder="es. Aereo">
        </div>
        <div class="form-group">
            <label>Periodo</label>
            <input type="text" name="periodo" class="form-control" placeholder="es. Maggio 2026">
        </div>
        <div class="form-group">
            <label>Costo a persona (&euro;) *</label>
            <input type="number" name="costo" class="form-control" step="0.01" min="0" required placeholder="es. 350.00">
        </div>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modal5g').classList.add('hidden')">Annulla</button>
    <button class="button" onclick="document.getElementById('form5g').submit()">Salva Proposta</button>
</div>
</div>
</div>


<!-- ═══════════════════════════════════════════════════════════════
     MODAL — Organizza Gita 1 Giorno
═══════════════════════════════════════════════════════════════ -->
<div class="modal-overlay hidden" id="modalOrg1g">
<div class="modal">
<div class="modal-header">
    <h3 id="org1g_title">Organizza Gita 1 Giorno</h3>
    <button class="close-btn" onclick="document.getElementById('modalOrg1g').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
<form method="POST" action="catalogo.php" id="formOrg1g">
    <input type="hidden" name="action"   value="organizza_1g">
    <input type="hidden" name="id_gita"  id="org1g_id">
    <div class="form-grid">
        <div class="form-group">
            <label>Destinazione</label>
            <input type="text" name="org_mezzo_dest" id="org1g_mezzo_dest" class="form-control" readonly style="background:#f3f4f6;">
        </div>
        <div class="form-group">
            <label>Mezzo di trasporto</label>
            <input type="text" name="org_mezzo" id="org1g_mezzo" class="form-control" placeholder="es. Pullman">
        </div>
        <div class="form-group">
            <label>Periodo</label>
            <input type="text" name="org_periodo" id="org1g_periodo" class="form-control" placeholder="es. Aprile 2026">
        </div>
        <div class="form-group">
            <label>Costo a Persona (&euro;)</label>
            <input type="number" name="org_costoAPersona" id="org1g_costoPersona" class="form-control" step="0.01" min="0" placeholder="es. 45.00">
        </div>
        <div class="form-group">
            <label>Giorno</label>
            <input type="date" name="org_giorno" id="org1g_giorno" class="form-control">
        </div>
        <div class="form-group">
            <label>Costo Mezzo (&euro;)</label>
            <input type="number" name="org_costoMezzo" id="org1g_costoMezzo" class="form-control" step="0.01" min="0" placeholder="es. 200.00">
        </div>
        <div class="form-group">
            <label>Costo Attività/Giorno (&euro;)</label>
            <input type="number" name="org_costoGiorno" id="org1g_costoGiorno" class="form-control" step="0.01" min="0" placeholder="es. 15.00">
        </div>
        <div class="form-group">
            <label>Num. Alunni</label>
            <input type="number" name="org_numAlunni" id="org1g_numAlunni" class="form-control" min="0" placeholder="es. 25">
        </div>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modalOrg1g').classList.add('hidden')">Annulla</button>
    <button class="button" onclick="document.getElementById('formOrg1g').submit()">Metti in Organizzazione</button>
</div>
</div>
</div>


<!-- ═══════════════════════════════════════════════════════════════
     MODAL — Organizza Gita più Giorni
═══════════════════════════════════════════════════════════════ -->
<div class="modal-overlay hidden" id="modalOrg5g">
<div class="modal">
<div class="modal-header">
    <h3 id="org5g_title">Organizza Gita più Giorni</h3>
    <button class="close-btn" onclick="document.getElementById('modalOrg5g').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
<form method="POST" action="catalogo.php" id="formOrg5g">
    <input type="hidden" name="action"   value="organizza_5g">
    <input type="hidden" name="id_gita"  id="org5g_id">
    <div class="form-grid">
        <div class="form-group">
            <label>Destinazione</label>
            <input type="text" id="org5g_mezzo_dest" class="form-control" readonly style="background:#f3f4f6;">
        </div>
        <div class="form-group">
            <label>Mezzo di trasporto</label>
            <input type="text" name="org_mezzo" id="org5g_mezzo" class="form-control" placeholder="es. Aereo">
        </div>
        <div class="form-group">
            <label>Periodo</label>
            <input type="text" name="org_periodo" id="org5g_periodo" class="form-control" placeholder="es. Maggio 2026">
        </div>
        <div class="form-group">
            <label>Costo a Persona (&euro;)</label>
            <input type="number" name="org_costoAPersona" id="org5g_costoAPersona" class="form-control" step="0.01" min="0" placeholder="es. 350.00">
        </div>
        <div class="form-group">
            <label>Giorno Inizio</label>
            <input type="date" name="org_giornoInizio" id="org5g_giornoInizio" class="form-control">
        </div>
        <div class="form-group">
            <label>Giorno Fine</label>
            <input type="date" name="org_giornoFine" id="org5g_giornoFine" class="form-control">
        </div>
        <div class="form-group">
            <label>Num. Alunni</label>
            <input type="number" name="org_numAlunni" id="org5g_numAlunni" class="form-control" min="0" placeholder="es. 50">
        </div>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modalOrg5g').classList.add('hidden')">Annulla</button>
    <button class="button" onclick="document.getElementById('formOrg5g').submit()">Metti in Organizzazione</button>
</div>
</div>
</div>

<!-- ═══════════════════════════════════════════════════════════════
     MODAL — Modifica Gita 1 Giorno (solo commissione)
═══════════════════════════════════════════════════════════════ -->
<div class="modal-overlay hidden" id="modalMod1g">
<div class="modal">
<div class="modal-header">
    <h3>Modifica Gita 1 Giorno</h3>
    <button class="close-btn" onclick="document.getElementById('modalMod1g').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
<form method="POST" action="catalogo.php" id="formMod1g">
    <input type="hidden" name="action"  value="modifica_1g">
    <input type="hidden" name="id_gita" id="mod1g_id">
    <div class="form-grid">
        <div class="form-group">
            <label>Destinazione *</label>
            <input type="text" name="mod_destinazione" id="mod1g_destinazione" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Mezzo di trasporto</label>
            <input type="text" name="mod_mezzo" id="mod1g_mezzo" class="form-control">
        </div>
        <div class="form-group">
            <label>Periodo</label>
            <input type="text" name="mod_periodo" id="mod1g_periodo" class="form-control">
        </div>
        <div class="form-group">
            <label>Costo a Persona (&euro;)</label>
            <input type="number" name="mod_costo" id="mod1g_costo" class="form-control" step="0.01" min="0">
        </div>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modalMod1g').classList.add('hidden')">Annulla</button>
    <button class="button" onclick="document.getElementById('formMod1g').submit()">Salva Modifiche</button>
</div>
</div>
</div>


<!-- ═══════════════════════════════════════════════════════════════
     MODAL — Modifica Gita più Giorni (solo commissione)
═══════════════════════════════════════════════════════════════ -->
<div class="modal-overlay hidden" id="modalMod5g">
<div class="modal">
<div class="modal-header">
    <h3>Modifica Gita più Giorni</h3>
    <button class="close-btn" onclick="document.getElementById('modalMod5g').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
<form method="POST" action="catalogo.php" id="formMod5g">
    <input type="hidden" name="action"  value="modifica_5g">
    <input type="hidden" name="id_gita" id="mod5g_id">
    <div class="form-grid">
        <div class="form-group">
            <label>Destinazione *</label>
            <input type="text" name="mod_destinazione" id="mod5g_destinazione" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Mezzo di trasporto</label>
            <input type="text" name="mod_mezzo" id="mod5g_mezzo" class="form-control">
        </div>
        <div class="form-group">
            <label>Periodo</label>
            <input type="text" name="mod_periodo" id="mod5g_periodo" class="form-control">
        </div>
        <div class="form-group">
            <label>Costo a Persona (&euro;)</label>
            <input type="number" name="mod_costo" id="mod5g_costo" class="form-control" step="0.01" min="0">
        </div>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modalMod5g').classList.add('hidden')">Annulla</button>
    <button class="button" onclick="document.getElementById('formMod5g').submit()">Salva Modifiche</button>
</div>
</div>
</div>


<!-- ═══════════════════════════════════════════════════════════════
     MODAL — Conferma Elimina (solo commissione)
═══════════════════════════════════════════════════════════════ -->
<div class="modal-overlay hidden" id="modalElimina">
<div class="modal">
<div class="modal-header">
    <h3>Conferma Eliminazione</h3>
    <button class="close-btn" onclick="document.getElementById('modalElimina').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
    <p>Sei sicuro di voler eliminare la gita verso <strong id="elimDest"></strong>? L'operazione non è reversibile.</p>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modalElimina').classList.add('hidden')">Annulla</button>
    <button class="button cancel" onclick="document.getElementById('formElimina').submit()">Elimina</button>
</div>
</div>
</div>
<form method="POST" id="formElimina" style="display:none;">
    <input type="hidden" name="action"  id="elimTabella">
    <input type="hidden" name="id_gita" id="elimId">
</form>

<!-- ═══════════════════════════════════════════════════════════════
     MODAL — Organizzazione completata con successo
═══════════════════════════════════════════════════════════════ -->
<div class="modal-overlay hidden" id="modalOrganizzaOk">
<div class="modal" style="text-align:center;max-width:420px;">
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

</div><!-- /container -->
</body>
</html>
