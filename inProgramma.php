<?php
include('nav.php');

$risultatoGite = mysqli_query($conn, "SELECT g.*, s.Stato FROM gitaorganizzata g JOIN statogita s ON g.IDStato = s.IDStato WHERE g.IDStato IN (4,5) ORDER BY g.DataInizio ASC");
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
<div class="hero-section">
    <h2 style="margin-bottom:0.5rem;color:var(--blue-700);">Gite in Programma</h2>
    <p>Elenco di tutte le gite in organizzazione o concluse.</p>
</div>

<div class="table-section"><div class="table-container">
<table>
<thead><tr>
    <th>#</th><th>Destinazione / Classi</th><th>Data Inizio</th><th>Data Fine</th>
    <th>Alunni</th><th>Docenti</th><th>Costo Totale</th><th>Stato</th><th>Dettagli</th>
</tr></thead>
<tbody>
<?php
$n = 1;
if (mysqli_num_rows($risultatoGite) > 0) {
    while ($row = mysqli_fetch_assoc($risultatoGite)) {
        $dest   = htmlspecialchars($row['ClassiPartecipanti'] ?? 'N/D');
        $costo  = number_format($row['CostoTot'], 2, ',', '.');
        $ini    = $row['DataInizio'] ? date('d/m/Y', strtotime($row['DataInizio'])) : '-';
        $fin    = $row['DataFine']   ? date('d/m/Y', strtotime($row['DataFine']))   : '-';
        $bc     = ($row['Stato'] == 'Conclusa') ? 'badge-secondary' : 'badge-primary';
        $disab  = intval($row['NumAlunniDisabili']);
        $disStr = $disab > 0 ? " (+$disab dis.)" : "";
        echo "<tr
            data-dest='$dest'
            data-stato='{$row['Stato']}'
            data-inizio='$ini' data-fine='$fin'
            data-alunni='" . intval($row['NumAlunni']) . "'
            data-disabili='$disab'
            data-docenti='" . intval($row['NumDocentiAccompagnatori']) . "'
            data-or-partenza='" . htmlspecialchars($row['OrarioPartenza'] ?? '') . "'
            data-or-arrivo='" . htmlspecialchars($row['OrarioArrivo'] ?? '') . "'
            data-costo-mezzi='" . ($row['CostoMezzi'] ?? 0) . "'
            data-costo-att='" . ($row['CostoAttivita'] ?? 0) . "'
            data-costo='" . ($row['CostoTot'] ?? 0) . "'>
            <td>$n</td>
            <td>$dest</td>
            <td>$ini</td>
            <td>$fin</td>
            <td>" . intval($row['NumAlunni']) . "$disStr</td>
            <td>" . intval($row['NumDocentiAccompagnatori']) . "</td>
            <td>&euro; $costo</td>
            <td><span class='badge $bc'>{$row['Stato']}</span></td>
            <td><button class='button outline xs btn-dettagli-prog'>Dettagli</button></td>
        </tr>";
        $n++;
    }
} else {
    echo "<tr><td colspan='9' style='text-align:center;'>Nessuna gita in programma.</td></tr>";
}
?>
</tbody>
</table>
</div></div>
</main>

<!-- Modal Dettagli -->
<div class="modal-overlay hidden" id="modalDettagliProg">
<div class="modal wide-modal">
<div class="modal-header">
    <h3>Dettagli Gita</h3>
    <button class="close-btn" onclick="chiudiProg()">&times;</button>
</div>
<div class="modal-body">
<div class="form-grid">
    <div class="form-group"><label>Destinazione / Classi</label><input type="text" id="progDest" readonly></div>
    <div class="form-group"><label>Stato</label><input type="text" id="progStato" readonly></div>
    <div class="form-group"><label>Data Inizio</label><input type="text" id="progInizio" readonly></div>
    <div class="form-group"><label>Data Fine</label><input type="text" id="progFine" readonly></div>
    <div class="form-group"><label>Num. Alunni</label><input type="text" id="progAlunni" readonly></div>
    <div class="form-group"><label>Alunni Disabili</label><input type="text" id="progDisabili" readonly></div>
    <div class="form-group"><label>Num. Docenti</label><input type="text" id="progDocenti" readonly></div>
    <div class="form-group"><label>Orario Partenza</label><input type="text" id="progOrPart" readonly></div>
    <div class="form-group"><label>Orario Arrivo</label><input type="text" id="progOrArr" readonly></div>
    <div class="form-group"><label>Costo Mezzi (&euro;)</label><input type="text" id="progCostoMezzi" readonly></div>
    <div class="form-group"><label>Costo Attivita (&euro;)</label><input type="text" id="progCostoAtt" readonly></div>
    <div class="form-group"><label>Costo Totale (&euro;)</label><input type="text" id="progCosto" readonly></div>
</div>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="chiudiProg()">Chiudi</button>
</div>
</div>
</div>

<footer><div class="footer-container"><div class="footer-left"><p><strong>Gestione Gite Scolastiche</strong></p></div></div></footer>
</div>
<script>
var modalProg = document.getElementById('modalDettagliProg');
document.querySelectorAll('.btn-dettagli-prog').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var r = btn.closest('tr');
        document.getElementById('progDest').value       = r.dataset.dest;
        document.getElementById('progStato').value      = r.dataset.stato;
        document.getElementById('progInizio').value     = r.dataset.inizio;
        document.getElementById('progFine').value       = r.dataset.fine;
        document.getElementById('progAlunni').value     = r.dataset.alunni;
        document.getElementById('progDisabili').value   = r.dataset.disabili;
        document.getElementById('progDocenti').value    = r.dataset.docenti;
        document.getElementById('progOrPart').value     = r.dataset.orPartenza;
        document.getElementById('progOrArr').value      = r.dataset.orArrivo;
        document.getElementById('progCostoMezzi').value = r.dataset.costoMezzi;
        document.getElementById('progCostoAtt').value   = r.dataset.costoAtt;
        document.getElementById('progCosto').value      = r.dataset.costo;
        modalProg.classList.remove('hidden');
    });
});
function chiudiProg() { modalProg.classList.add('hidden'); }
window.addEventListener('click', function(e) { if (e.target === modalProg) chiudiProg(); });
</script>
</body>
</html>
