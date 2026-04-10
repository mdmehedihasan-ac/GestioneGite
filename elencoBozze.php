<?php
include('nav.php');

$messaggio = "";

// ─── AZIONI APPROVA / BOCCIA GITA 1 GIORNO ───────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'azione_1g') {
    $idGita     = intval($_POST['id_gita']);
    $nuovoStato = ($_POST['azione'] === 'approva') ? 2 : 3;
    if ($conn->query("UPDATE gita1g SET idStato = $nuovoStato WHERE idGita = $idGita")) {
        $messaggio = "<div class='alert-success'>Operazione completata.</div>";
    } else {
        $messaggio = "<div class='alert-error'>Errore aggiornamento.</div>";
    }
}

// ─── AZIONI APPROVA / BOCCIA GITA 5 GIORNI ───────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'azione_5g') {
    $idGita     = intval($_POST['id_gita']);
    $nuovoStato = ($_POST['azione'] === 'approva') ? 2 : 3;
    if ($conn->query("UPDATE gite5 SET idStato = $nuovoStato WHERE idGita = $idGita")) {
        $messaggio = "<div class='alert-success'>Operazione completata.</div>";
    } else {
        $messaggio = "<div class='alert-error'>Errore aggiornamento.</div>";
    }
}

// ─── QUERY BOZZE GITA 1 GIORNO (stato 1) ─────────────────────────────────────
$bozze1g = $conn->query("
    SELECT g.idGita, g.destinazione, g.mezzo, g.periodo, g.costoAPersona,
           u.Nome, u.Cognome
    FROM gita1g g
    JOIN utente u ON g.idUtente = u.IDUtente
    WHERE g.idStato = 1
    ORDER BY g.idGita DESC
");

// ─── QUERY BOZZE GITA 5 GIORNI (stato 1) ─────────────────────────────────────
$bozze5g = $conn->query("
    SELECT g.idGita, g.destinazione, g.mezzo, g.periodo, g.costoAPersona,
           u.Nome, u.Cognome
    FROM gite5 g
    JOIN utente u ON g.idUtente = u.IDUtente
    WHERE g.idStato = 1
    ORDER BY g.idGita DESC
");

$tot1g = $bozze1g ? $bozze1g->num_rows : 0;
$tot5g = $bozze5g ? $bozze5g->num_rows : 0;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco Bozze</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="vetrina.css">
    <link rel="stylesheet" href="style_custom.css">
    <script src="vetrina.js" defer></script>
    <script>
    function apriConferma(idGita, azione, tabella, destinazione) {
        var modal = document.getElementById('modalConferma');
        var titolo = azione === 'approva' ? 'Approva Proposta' : 'Boccia Proposta';
        var testo  = azione === 'approva'
            ? 'Sei sicuro di voler <strong>approvare</strong> la gita verso <strong>' + destinazione + '</strong>?'
            : 'Sei sicuro di voler <strong>bocciare</strong> la gita verso <strong>' + destinazione + '</strong>?';

        document.getElementById('confTitolo').innerHTML   = titolo;
        document.getElementById('confTesto').innerHTML    = testo;
        document.getElementById('confIdGita').value       = idGita;
        document.getElementById('confAzione').value       = azione;
        document.getElementById('confTabella').value      = tabella;

        var btnConf = document.getElementById('btnConferma');
        btnConf.className = azione === 'approva' ? 'button' : 'button cancel';

        modal.classList.remove('hidden');
    }
    function chiudiConferma() {
        document.getElementById('modalConferma').classList.add('hidden');
    }
    </script>
</head>
<body>
<div class="container">
<main class="content bozze-padding">

<div class="hero-section">
    <h2 style="margin-bottom:0.5rem;color:var(--blue-700);">Bozze in Attesa</h2>
    <p>Proposte in attesa di approvazione.</p>
</div>

<?php echo $messaggio; ?>

<!-- ═══════════════════════════════════════════════════════════════
     SEZIONE BOZZE GITE 1 GIORNO
═══════════════════════════════════════════════════════════════ -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-top:2rem;margin-bottom:1rem;">
    <h3 style="color:var(--blue-700);margin:0;">Gite di 1 Giorno <span style="font-size:.85rem;font-weight:400;color:#6b7280;">(<?= $tot1g ?> in attesa)</span></h3>
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
    <th>Azioni</th>
</tr></thead>
<tbody>
<?php
$n = 1;
if ($bozze1g && $bozze1g->num_rows > 0) {
    while ($r = $bozze1g->fetch_assoc()) {
        $dest   = htmlspecialchars($r['destinazione']);
        $mezzo  = htmlspecialchars($r['mezzo'] ?? '—');
        $per    = htmlspecialchars($r['periodo'] ?? '—');
        $costo  = number_format($r['costoAPersona'], 2, ',', '.');
        $autore = htmlspecialchars($r['Nome'] . ' ' . $r['Cognome']);
        $id     = intval($r['idGita']);
        echo "<tr>
            <td>$n</td>
            <td>$dest</td>
            <td>$mezzo</td>
            <td>$per</td>
            <td>&euro; $costo</td>
            <td>$autore</td>
            <td style='display:flex;gap:0.4rem;'>
                <button type='button' class='button xs'        onclick=\"apriConferma($id,'approva','azione_1g','$dest')\">Approva</button>
                <button type='button' class='button cancel xs' onclick=\"apriConferma($id,'boccia','azione_1g','$dest')\">Boccia</button>
            </td>
        </tr>";
        $n++;
    }
} else {
    echo "<tr><td colspan='7' style='text-align:center;'>Nessuna bozza di gita 1 giorno in attesa.</td></tr>";
}
?>
</tbody>
</table>
</div></div>

<!-- ═══════════════════════════════════════════════════════════════
     SEZIONE BOZZE GITE 5 GIORNI
═══════════════════════════════════════════════════════════════ -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
    <h3 style="color:var(--blue-700);margin:0;">Gite di 5 Giorni <span style="font-size:.85rem;font-weight:400;color:#6b7280;">(<?= $tot5g ?> in attesa)</span></h3>
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
    <th>Azioni</th>
</tr></thead>
<tbody>
<?php
$n = 1;
if ($bozze5g && $bozze5g->num_rows > 0) {
    while ($r = $bozze5g->fetch_assoc()) {
        $dest   = htmlspecialchars($r['destinazione']);
        $mezzo  = htmlspecialchars($r['mezzo'] ?? '—');
        $per    = htmlspecialchars($r['periodo'] ?? '—');
        $costo  = number_format($r['costoAPersona'], 2, ',', '.');
        $autore = htmlspecialchars($r['Nome'] . ' ' . $r['Cognome']);
        $id     = intval($r['idGita']);
        echo "<tr>
            <td>$n</td>
            <td>$dest</td>
            <td>$mezzo</td>
            <td>$per</td>
            <td>&euro; $costo</td>
            <td>$autore</td>
            <td style='display:flex;gap:0.4rem;'>
                <button type='button' class='button xs'        onclick=\"apriConferma($id,'approva','azione_5g','$dest')\">Approva</button>
                <button type='button' class='button cancel xs' onclick=\"apriConferma($id,'boccia','azione_5g','$dest')\">Boccia</button>
            </td>
        </tr>";
        $n++;
    }
} else {
    echo "<tr><td colspan='7' style='text-align:center;'>Nessuna bozza di gita 5 giorni in attesa.</td></tr>";
}
?>
</tbody>
</table>
</div></div>

</main>

<!-- ═══════════════════════════════════════════════════════════════
     MODAL — Conferma Approva / Boccia
═══════════════════════════════════════════════════════════════ -->
<div class="modal-overlay hidden" id="modalConferma">
<div class="modal">
<div class="modal-header">
    <h3 id="confTitolo">Conferma</h3>
    <button class="close-btn" onclick="chiudiConferma()">&times;</button>
</div>
<div class="modal-body">
    <p id="confTesto" style="font-size:1rem;"></p>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="chiudiConferma()">Annulla</button>
    <button class="button" id="btnConferma" onclick="document.getElementById('formConferma').submit()">Conferma</button>
</div>
</div>
</div>

<!-- Form nascosto per invio POST -->
<form method="POST" id="formConferma" style="display:none;">
    <input type="hidden" name="action"   id="confTabella">
    <input type="hidden" name="id_gita"  id="confIdGita">
    <input type="hidden" name="azione"   id="confAzione">
</form>

</div><!-- /container -->
</body>
</html>
