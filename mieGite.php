<?php
include('nav.php');

$messaggio = "";
$idUtenteLoggato = $_SESSION['id_utente'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'modifica_gita_organizzata') {
        $idGita         = intval($_POST['idGita'] ?? 0);
        $dataInizio     = $_POST['dataInizio'] ?? '';
        $dataFine       = $_POST['dataFine'] ?? '';
        $alunni         = intval($_POST['alunni'] ?? 0);
        $alunniDisabili = intval($_POST['alunniDisabili'] ?? 0);
        $docenti        = intval($_POST['docenti'] ?? 0);
        $classi         = $_POST['classi'] ?? '';
        $orarioPartenza = $_POST['orarioPartenza'] ?: null;
        $orarioArrivo   = $_POST['orarioArrivo'] ?: null;
        $costoMezzi     = floatval($_POST['costoMezzi'] ?? 0);
        $costoAttivita  = floatval($_POST['costoAttivita'] ?? 0);
        $costoTotale    = floatval($_POST['costoTotale'] ?? 0);
        $istr = mysqli_prepare($conn, "UPDATE gitaorganizzata SET DataInizio=?, DataFine=?, NumAlunni=?, NumDocentiAccompagnatori=?, NumAlunniDisabili=?, ClassiPartecipanti=?, OrarioPartenza=?, OrarioArrivo=?, CostoMezzi=?, CostoAttivita=?, CostoTot=? WHERE IDGita=? AND IDUtente=?");
        mysqli_stmt_bind_param($istr, "ssiiisssdddii", $dataInizio, $dataFine, $alunni, $docenti, $alunniDisabili, $classi, $orarioPartenza, $orarioArrivo, $costoMezzi, $costoAttivita, $costoTotale, $idGita, $idUtenteLoggato);
        if (mysqli_stmt_execute($istr)) {
            $messaggio = "<div style='background-color:#d4edda;color:#155724;padding:10px;margin-bottom:20px;border-radius:5px;'>Gita aggiornata.</div>";
        } else {
            $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore modifica.</div>";
        }
        mysqli_stmt_close($istr);
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
    <p>Tutte le gite che hai creato o organizzato.</p>
</div>

<?php echo $messaggio; ?>

<div style="margin-top:2rem;">
<h3 style="margin-bottom:1rem;color:var(--blue-700);">Gite create da me</h3>
<div class="miegite-grid">
<?php
$res1 = mysqli_query($conn, "SELECT g.*, s.Stato FROM gitaorganizzata g JOIN statogita s ON g.IDStato = s.IDStato WHERE g.IDUtente = $idUtenteLoggato AND g.IDStato IN (1,2,3) ORDER BY g.IDGita DESC");
if (mysqli_num_rows($res1) > 0) {
    while ($row = mysqli_fetch_assoc($res1)) {
        $cb = 'badge-secondary';
        if ($row['Stato'] == 'Approvata') $cb = 'badge-success';
        if ($row['Stato'] == 'Bocciata')  $cb = 'badge-danger';
        if ($row['Stato'] == 'Bozza')     $cb = 'badge-warning';
        $dest  = htmlspecialchars($row['ClassiPartecipanti'] ?? 'N/D');
        $costo = number_format($row['CostoTot'], 2, ',', '.');
        $ini   = $row['DataInizio'] ? date('d/m/Y', strtotime($row['DataInizio'])) : '-';
        $fin   = $row['DataFine']   ? date('d/m/Y', strtotime($row['DataFine']))   : '-';
        echo "<div class='gita-card'>
            <div class='gita-card-header'><span class='badge $cb'>{$row['Stato']}</span></div>
            <div class='gita-card-body'>
                <h4>$dest</h4>
                <p><strong>Dal:</strong> $ini <strong>Al:</strong> $fin</p>
                <p><strong>Costo:</strong> &euro; $costo</p>
            </div>
            <div class='gita-card-footer'>
                <button class='button outline btn-dettagli-gita' data-dest='$dest' data-stato='{$row['Stato']}' data-costo='$costo'>Dettagli</button>
            </div>
        </div>";
    }
} else {
    echo "<p style='color:#64748b;'>Non hai ancora creato nessuna proposta.</p>";
}
?>
</div>
</div>

<div style="margin-top:3rem;">
<h3 style="margin-bottom:1rem;color:var(--blue-700);">Gite che ho organizzato</h3>
<div class="miegite-grid">
<?php
$res2 = mysqli_query($conn, "SELECT g.*, s.Stato FROM gitaorganizzata g JOIN statogita s ON g.IDStato = s.IDStato WHERE g.IDUtente = $idUtenteLoggato AND g.IDStato IN (4,5) ORDER BY g.DataInizio DESC");
if (mysqli_num_rows($res2) > 0) {
    while ($row = mysqli_fetch_assoc($res2)) {
        $cb   = ($row['Stato'] == 'Conclusa') ? 'badge-secondary' : 'badge-primary';
        $dest = htmlspecialchars($row['ClassiPartecipanti'] ?? 'N/D');
        $costo= number_format($row['CostoTot'], 2, ',', '.');
        $ini  = $row['DataInizio'] ? date('d/m/Y', strtotime($row['DataInizio'])) : '-';
        $fin  = $row['DataFine']   ? date('d/m/Y', strtotime($row['DataFine']))   : '-';
        $iniR = $row['DataInizio'] ?? '';
        $finR = $row['DataFine'] ?? '';
        echo "<div class='gita-card'
            data-id='{$row['IDGita']}'
            data-dest='$dest'
            data-stato='{$row['Stato']}'
            data-inizio='$ini' data-fine='$fin'
            data-inizio-raw='$iniR' data-fine-raw='$finR'
            data-alunni='" . intval($row['NumAlunni']) . "'
            data-alunni-disabili='" . intval($row['NumAlunniDisabili']) . "'
            data-docenti='" . intval($row['NumDocentiAccompagnatori']) . "'
            data-classi='$dest'
            data-or-partenza='" . htmlspecialchars($row['OrarioPartenza'] ?? '') . "'
            data-or-arrivo='" . htmlspecialchars($row['OrarioArrivo'] ?? '') . "'
            data-costo-mezzi='" . ($row['CostoMezzi'] ?? 0) . "'
            data-costo-att='" . ($row['CostoAttivita'] ?? 0) . "'
            data-costo='" . ($row['CostoTot'] ?? 0) . "'>
            <div class='gita-card-header'><span class='badge $cb'>{$row['Stato']}</span></div>
            <div class='gita-card-body'>
                <h4>$dest</h4>
                <p><strong>Dal:</strong> $ini <strong>Al:</strong> $fin</p>
                <p><strong>Alunni:</strong> " . intval($row['NumAlunni']) . " | <strong>Docenti:</strong> " . intval($row['NumDocentiAccompagnatori']) . "</p>
                <p><strong>Costo:</strong> &euro; $costo</p>
            </div>
            <div class='gita-card-footer' style='display:flex;gap:0.5rem;flex-wrap:wrap;'>
                <button class='button outline btn-dettagli-org'>Dettagli</button>
                <button class='button btn-modifica-partecipo'>Modifica</button>
                <a href='partecipanti.php?id={$row['IDGita']}' class='button' style='text-decoration:none;'>Partecipanti</a>
            </div>
        </div>";
    }
} else {
    echo "<p style='color:#64748b;'>Non hai ancora organizzato nessuna gita.</p>";
}
?>
</div>
</div>
</main>

<!-- Modal Dettagli proposte -->
<div class="modal-overlay hidden" id="modalDettagli">
<div class="modal">
<div class="modal-header">
    <h3>Dettagli Proposta</h3>
    <button class="close-btn" onclick="document.getElementById('modalDettagli').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
    <div class="form-group"><label>Destinazione</label><input type="text" id="detDest" readonly></div>
    <div class="form-group"><label>Stato</label><input type="text" id="detStato" readonly></div>
    <div class="form-group"><label>Costo (&euro;)</label><input type="text" id="detCosto" readonly></div>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modalDettagli').classList.add('hidden')">Chiudi</button>
</div>
</div>
</div>

<!-- Modal Dettagli gite organizzate -->
<div class="modal-overlay hidden" id="modalDettagliOrg">
<div class="modal wide-modal">
<div class="modal-header">
    <h3>Dettagli Gita Organizzata</h3>
    <button class="close-btn" onclick="document.getElementById('modalDettagliOrg').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
<div class="form-grid">
    <div class="form-group"><label>Destinazione</label><input type="text" id="orgDest" readonly></div>
    <div class="form-group"><label>Stato</label><input type="text" id="orgStato" readonly></div>
    <div class="form-group"><label>Data Inizio</label><input type="text" id="orgInizio" readonly></div>
    <div class="form-group"><label>Data Fine</label><input type="text" id="orgFine" readonly></div>
    <div class="form-group"><label>Alunni</label><input type="text" id="orgAlunni" readonly></div>
    <div class="form-group"><label>Disabili</label><input type="text" id="orgDisabili" readonly></div>
    <div class="form-group"><label>Docenti</label><input type="text" id="orgDocenti" readonly></div>
    <div class="form-group"><label>Orario Partenza</label><input type="text" id="orgOrPart" readonly></div>
    <div class="form-group"><label>Orario Arrivo</label><input type="text" id="orgOrArr" readonly></div>
    <div class="form-group"><label>Costo Mezzi</label><input type="text" id="orgCostoMezzi" readonly></div>
    <div class="form-group"><label>Costo Attivita</label><input type="text" id="orgCostoAtt" readonly></div>
    <div class="form-group"><label>Costo Totale</label><input type="text" id="orgCosto" readonly></div>
</div>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modalDettagliOrg').classList.add('hidden')">Chiudi</button>
</div>
</div>
</div>

<!-- Modal Modifica -->
<div class="modal-overlay hidden" id="modalModificaPartecipo">
<div class="modal wide-modal">
<div class="modal-header">
    <h3>Modifica Gita</h3>
    <button class="close-btn" onclick="chiudiMod()">&times;</button>
</div>
<div class="modal-body">
<form id="formModifica" class="form-grid" method="POST" action="mieGite.php">
    <input type="hidden" name="action" value="modifica_gita_organizzata">
    <input type="hidden" name="idGita" id="modIdGita">
    <div class="form-group"><label>Data Inizio</label><input type="date" id="modDataInizio" name="dataInizio" required></div>
    <div class="form-group"><label>Data Fine</label><input type="date" id="modDataFine" name="dataFine" required></div>
    <div class="form-group"><label>Num. Alunni</label><input type="number" id="modAlunni" name="alunni" min="0" required></div>
    <div class="form-group"><label>Alunni Disabili</label><input type="number" id="modAlunniDisabili" name="alunniDisabili" min="0" value="0"></div>
    <div class="form-group"><label>Num. Docenti</label><input type="number" id="modDocenti" name="docenti" min="0" required></div>
    <div class="form-group"><label>Destinazione / Classi</label><input type="text" id="modClassi" name="classi"></div>
    <div class="form-group"><label>Orario Partenza</label><input type="time" id="modOrarioPartenza" name="orarioPartenza"></div>
    <div class="form-group"><label>Orario Arrivo</label><input type="time" id="modOrarioArrivo" name="orarioArrivo"></div>
    <div class="form-group"><label>Costo Mezzi (&euro;)</label><input type="number" step="0.01" id="modCostoMezzi" name="costoMezzi" min="0" value="0"></div>
    <div class="form-group"><label>Costo Attivita (&euro;)</label><input type="number" step="0.01" id="modCostoAttivita" name="costoAttivita" min="0" value="0"></div>
    <div class="form-group"><label>Costo Totale (&euro;)</label><input type="number" step="0.01" id="modCostoTotale" name="costoTotale" min="0" value="0"></div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="chiudiMod()">Annulla</button>
    <button class="button" type="submit" form="formModifica">Salva</button>
</div>
</div>
</div>

<footer><div class="footer-container"><div class="footer-left"><p><strong>Gestione Gite Scolastiche</strong></p></div></div></footer>
</div>
<script>
document.querySelectorAll('.btn-dettagli-gita').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('detDest').value  = btn.dataset.dest;
        document.getElementById('detStato').value = btn.dataset.stato;
        document.getElementById('detCosto').value = btn.dataset.costo;
        document.getElementById('modalDettagli').classList.remove('hidden');
    });
});
document.querySelectorAll('.btn-dettagli-org').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var c = btn.closest('.gita-card');
        document.getElementById('orgDest').value       = c.dataset.dest;
        document.getElementById('orgStato').value      = c.dataset.stato;
        document.getElementById('orgInizio').value     = c.dataset.inizio;
        document.getElementById('orgFine').value       = c.dataset.fine;
        document.getElementById('orgAlunni').value     = c.dataset.alunni;
        document.getElementById('orgDisabili').value   = c.dataset.alunniDisabili;
        document.getElementById('orgDocenti').value    = c.dataset.docenti;
        document.getElementById('orgOrPart').value     = c.dataset.orPartenza;
        document.getElementById('orgOrArr').value      = c.dataset.orArrivo;
        document.getElementById('orgCostoMezzi').value = c.dataset.costoMezzi;
        document.getElementById('orgCostoAtt').value   = c.dataset.costoAtt;
        document.getElementById('orgCosto').value      = c.dataset.costo;
        document.getElementById('modalDettagliOrg').classList.remove('hidden');
    });
});
document.querySelectorAll('.btn-modifica-partecipo').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var c = btn.closest('.gita-card');
        document.getElementById('modIdGita').value         = c.dataset.id;
        document.getElementById('modDataInizio').value     = c.dataset.inizioRaw;
        document.getElementById('modDataFine').value       = c.dataset.fineRaw;
        document.getElementById('modAlunni').value         = c.dataset.alunni;
        document.getElementById('modAlunniDisabili').value = c.dataset.alunniDisabili;
        document.getElementById('modDocenti').value        = c.dataset.docenti;
        document.getElementById('modClassi').value         = c.dataset.classi;
        document.getElementById('modOrarioPartenza').value = c.dataset.orPartenza;
        document.getElementById('modOrarioArrivo').value   = c.dataset.orArrivo;
        document.getElementById('modCostoMezzi').value     = c.dataset.costoMezzi;
        document.getElementById('modCostoAttivita').value  = c.dataset.costoAtt;
        document.getElementById('modCostoTotale').value    = c.dataset.costo;
        document.getElementById('modalModificaPartecipo').classList.remove('hidden');
    });
});
function chiudiMod() { document.getElementById('modalModificaPartecipo').classList.add('hidden'); }
window.addEventListener('click', function(e) {
    if (e.target === document.getElementById('modalModificaPartecipo')) chiudiMod();
    if (e.target === document.getElementById('modalDettagli')) document.getElementById('modalDettagli').classList.add('hidden');
    if (e.target === document.getElementById('modalDettagliOrg')) document.getElementById('modalDettagliOrg').classList.add('hidden');
});
</script>
</body>
</html>
