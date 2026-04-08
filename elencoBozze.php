<?php
include('nav.php');

$messaggio = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_gita'])) {
    $idGita     = intval($_POST['id_gita']);
    $nuovoStato = ($_POST['azione'] == 'approva') ? 2 : 3;
    if (mysqli_query($conn, "UPDATE gitaorganizzata SET IDStato = $nuovoStato WHERE IDGita = $idGita")) {
        $messaggio = "<div style='background-color:#d4edda;color:#155724;padding:10px;margin-bottom:20px;border-radius:5px;'>Operazione completata.</div>";
    } else {
        $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore aggiornamento.</div>";
    }
}

$risultatoBozze = mysqli_query($conn, "SELECT g.*, u.Nome, u.Cognome FROM gitaorganizzata g JOIN utente u ON g.IDUtente = u.IDUtente WHERE g.IDStato = 1 ORDER BY g.IDGita DESC");
$totBozze = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as totale FROM gitaorganizzata WHERE IDStato = 1"))['totale'];
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
</head>
<body>
<div class="container">
<main class="content bozze-padding">
<div class="hero-section">
    <h2 style="margin-bottom:0.5rem;color:var(--blue-700);">Bozze in Attesa</h2>
    <p>Proposte in attesa di approvazione. Totale: <strong><?php echo $totBozze; ?></strong></p>
</div>

<?php echo $messaggio; ?>

<div class="table-section"><div class="table-container">
<table>
<thead><tr>
    <th>#</th><th>Destinazione / Descrizione</th><th>Docente</th><th>Costo Stimato</th><th>Azioni</th>
</tr></thead>
<tbody>
<?php
$n = 1;
if (mysqli_num_rows($risultatoBozze) > 0) {
    while ($row = mysqli_fetch_assoc($risultatoBozze)) {
        $dest   = htmlspecialchars($row['ClassiPartecipanti'] ?? 'N/D');
        $costo  = number_format($row['CostoTot'], 2, ',', '.');
        $autore = htmlspecialchars($row['Nome'] . ' ' . $row['Cognome']);
        echo "<tr>
            <td>$n</td>
            <td>$dest</td>
            <td>$autore</td>
            <td>&euro; $costo</td>
            <td style='display:flex;gap:0.4rem;'>
                <button class='button xs btn-approva' data-id='{$row['IDGita']}' data-dest='$dest'>Approva</button>
                <button class='button cancel xs btn-boccia' data-id='{$row['IDGita']}' data-dest='$dest'>Boccia</button>
            </td>
        </tr>";
        $n++;
    }
} else {
    echo "<tr><td colspan='5' style='text-align:center;'>Nessuna bozza in attesa.</td></tr>";
}
?>
</tbody>
</table>
</div></div>
</main>

<!-- Modal Approva -->
<div class="modal-overlay hidden" id="modalApprova">
<div class="modal">
<div class="modal-header">
    <h3>Approva Proposta</h3>
    <button class="close-btn" id="chiudiApprova">&times;</button>
</div>
<div class="modal-body">
    <p>Approvare la proposta <strong id="approvaDestName"></strong>?</p>
    <form id="formApprova" method="POST" action="elencoBozze.php">
        <input type="hidden" name="id_gita" id="approvaIdGita">
        <input type="hidden" name="azione" value="approva">
    </form>
</div>
<div class="modal-footer">
    <button class="button cancel" id="annullaApprova">Annulla</button>
    <button class="button" type="submit" form="formApprova">Approva</button>
</div>
</div>
</div>

<!-- Modal Boccia -->
<div class="modal-overlay hidden" id="modalBoccia">
<div class="modal">
<div class="modal-header">
    <h3>Boccia Proposta</h3>
    <button class="close-btn" id="chiudiBoccia">&times;</button>
</div>
<div class="modal-body">
    <p>Bocciare la proposta <strong id="bocciaDestName"></strong>?</p>
    <form id="formBoccia" method="POST" action="elencoBozze.php">
        <input type="hidden" name="id_gita" id="bocciaIdGita">
        <input type="hidden" name="azione" value="boccia">
    </form>
</div>
<div class="modal-footer">
    <button class="button cancel" id="annullaBoccia">Annulla</button>
    <button class="button" style="background:#dc2626;" type="submit" form="formBoccia">Boccia</button>
</div>
</div>
</div>

<footer><div class="footer-container"><div class="footer-left"><p><strong>Gestione Gite Scolastiche</strong></p></div></div></footer>
</div>
<script>
var modalApprova = document.getElementById('modalApprova');
var modalBoccia  = document.getElementById('modalBoccia');
document.querySelectorAll('.btn-approva').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('approvaIdGita').value = btn.dataset.id;
        document.getElementById('approvaDestName').textContent = btn.dataset.dest;
        modalApprova.classList.remove('hidden');
    });
});
document.querySelectorAll('.btn-boccia').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('bocciaIdGita').value = btn.dataset.id;
        document.getElementById('bocciaDestName').textContent = btn.dataset.dest;
        modalBoccia.classList.remove('hidden');
    });
});
function chiudiA() { modalApprova.classList.add('hidden'); }
document.getElementById('chiudiApprova').addEventListener('click', chiudiA);
document.getElementById('annullaApprova').addEventListener('click', chiudiA);
function chiudiB() { modalBoccia.classList.add('hidden'); }
document.getElementById('chiudiBoccia').addEventListener('click', chiudiB);
document.getElementById('annullaBoccia').addEventListener('click', chiudiB);
window.addEventListener('click', function(e) {
    if (e.target === modalApprova) chiudiA();
    if (e.target === modalBoccia) chiudiB();
});
</script>
</body>
</html>
