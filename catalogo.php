<?php
include('nav.php');

$messaggio = "";
$mostraSuccesso = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

    if ($_POST['action'] == 'nuova_proposta') {
        $destinazione = $_POST['destinazione'] ?? '';
        $costo        = floatval($_POST['costo'] ?? 0);
        $idUtente     = $_SESSION['id_utente'] ?? 0;
        $stato        = 1;
        $istr = mysqli_prepare($conn, "INSERT INTO gitaorganizzata (IDUtente, IDStato, ClassiPartecipanti, CostoTot) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($istr, "iisd", $idUtente, $stato, $destinazione, $costo);
        if (mysqli_stmt_execute($istr)) {
            $mostraSuccesso = true;
        } else {
            $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore durante l'aggiunta.</div>";
        }
        mysqli_stmt_close($istr);

    } elseif ($_POST['action'] == 'modifica_proposta') {
        $idGita       = intval($_POST['idGita'] ?? 0);
        $destinazione = $_POST['destinazione'] ?? '';
        $costo        = floatval($_POST['costo'] ?? 0);
        $istr = mysqli_prepare($conn, "UPDATE gitaorganizzata SET ClassiPartecipanti=?, CostoTot=? WHERE IDGita=?");
        mysqli_stmt_bind_param($istr, "sdi", $destinazione, $costo, $idGita);
        if (mysqli_stmt_execute($istr)) {
            $messaggio = "<div style='background-color:#d4edda;color:#155724;padding:10px;margin-bottom:20px;border-radius:5px;'>Proposta modificata.</div>";
        } else {
            $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore modifica.</div>";
        }
        mysqli_stmt_close($istr);

    } elseif ($_POST['action'] == 'elimina_proposta') {
        $idGita = intval($_POST['idGita'] ?? 0);
        $istr = mysqli_prepare($conn, "DELETE FROM gitaorganizzata WHERE IDGita=?");
        mysqli_stmt_bind_param($istr, "i", $idGita);
        if (mysqli_stmt_execute($istr)) {
            $messaggio = "<div style='background-color:#d4edda;color:#155724;padding:10px;margin-bottom:20px;border-radius:5px;'>Proposta eliminata.</div>";
        } else {
            $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore eliminazione.</div>";
        }
        mysqli_stmt_close($istr);

    } elseif ($_POST['action'] == 'organizza_gita') {
        $idGitaProposta = intval($_POST['idGita'] ?? 0);
        $idUtente       = $_SESSION['id_utente'] ?? 0;
        $dataInizio     = $_POST['dataInizio'] ?? '';
        $dataFine       = $_POST['dataFine'] ?? '';
        $alunni         = intval($_POST['alunni'] ?? 0);
        $docenti        = intval($_POST['docenti'] ?? 0);
        $alunniDisabili = intval($_POST['alunniDisabili'] ?? 0);
        $orarioPartenza = $_POST['orarioPartenza'] ?: null;
        $orarioArrivo   = $_POST['orarioArrivo'] ?: null;
        $costoMezzi     = floatval($_POST['costoMezzi'] ?? 0);
        $costoAttivita  = floatval($_POST['costoAttivita'] ?? 0);
        $classi         = $_POST['classi'] ?? '';
        $statoOrg       = 4;
        $resCosto = mysqli_query($conn, "SELECT CostoTot FROM gitaorganizzata WHERE IDGita = $idGitaProposta");
        $rigaCosto = mysqli_fetch_assoc($resCosto);
        $costoTotale = floatval($rigaCosto['CostoTot'] ?? 0);
        $istr = mysqli_prepare($conn, "INSERT INTO gitaorganizzata (IDUtente, DataInizio, DataFine, NumAlunni, NumDocentiAccompagnatori, NumAlunniDisabili, CostoTot, IDStato, OrarioPartenza, OrarioArrivo, CostoMezzi, CostoAttivita, ClassiPartecipanti) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($istr, "issiiidissdds", $idUtente, $dataInizio, $dataFine, $alunni, $docenti, $alunniDisabili, $costoTotale, $statoOrg, $orarioPartenza, $orarioArrivo, $costoMezzi, $costoAttivita, $classi);
        if (mysqli_stmt_execute($istr)) {
            $messaggio = "<div style='background-color:#d4edda;color:#155724;padding:10px;margin-bottom:20px;border-radius:5px;'>Gita organizzata!</div>";
        } else {
            $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore organizzazione.</div>";
        }
        mysqli_stmt_close($istr);
    }
}

$risultatoProposte = mysqli_query($conn, "SELECT g.*, u.Nome AS NomeUtente, u.Cognome AS CognomeUtente FROM gitaorganizzata g JOIN utente u ON g.IDUtente = u.IDUtente WHERE g.IDStato = 2 ORDER BY g.IDGita DESC");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo Proposte</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="vetrina.css">
    <link rel="stylesheet" href="style_custom.css">
    <script src="vetrina.js" defer></script>
</head>
<body>
<div class="container">
<main class="content bozze-padding">
<div class="hero-section" style="display:flex;justify-content:space-between;align-items:flex-end;">
    <div>
        <h2 style="margin-bottom:0.5rem;color:var(--blue-700);">Catalogo Proposte</h2>
        <p>Proposte approvate disponibili per l'organizzazione.</p>
    </div>
    <button class="button" id="btnNuovaProposta">+ Nuova Proposta</button>
</div>

<?php echo $messaggio; ?>

<div class="table-section"><div class="table-container">
<table>
<thead><tr>
    <th>#</th><th>Destinazione / Descrizione</th><th>Proposta da</th><th>Costo Stimato</th><th>Azioni</th>
</tr></thead>
<tbody>
<?php
$n = 1;
if (mysqli_num_rows($risultatoProposte) > 0) {
    while ($row = mysqli_fetch_assoc($risultatoProposte)) {
        $dest   = htmlspecialchars($row['ClassiPartecipanti'] ?? 'N/D');
        $costo  = number_format($row['CostoTot'], 2, ',', '.');
        $autore = htmlspecialchars($row['NomeUtente'] . ' ' . $row['CognomeUtente']);
        echo "<tr>
            <td>$n</td>
            <td>$dest</td>
            <td>$autore</td>
            <td>&euro; $costo</td>
            <td style='display:flex;gap:0.4rem;flex-wrap:wrap;'>
                <button class='button outline xs btn-modifica' data-id='{$row['IDGita']}' data-dest='$dest' data-costo='{$row['CostoTot']}'>Modifica</button>
                <button class='button xs btn-organizza' data-id='{$row['IDGita']}' data-dest='$dest'>Organizza</button>
                <button class='button cancel xs btn-elimina' data-id='{$row['IDGita']}' data-dest='$dest'>Elimina</button>
            </td>
        </tr>";
        $n++;
    }
} else {
    echo "<tr><td colspan='5' style='text-align:center;'>Nessuna proposta approvata.</td></tr>";
}
?>
</tbody>
</table>
</div></div>
</main>

<!-- Modal Nuova/Modifica Proposta -->
<div class="modal-overlay hidden" id="modalOverlay">
<div class="modal">
<div class="modal-header">
    <h3 id="modalTitolo">Nuova Proposta</h3>
    <button class="close-btn" id="chiudiModal">&times;</button>
</div>
<div class="modal-body">
<form id="formProposta" class="form-grid" method="POST" action="catalogo.php">
    <input type="hidden" name="action" id="formAction" value="nuova_proposta">
    <input type="hidden" name="idGita" id="formIdGita" value="">
    <div class="form-group">
        <label for="destinazione">Destinazione / Descrizione</label>
        <input type="text" id="destinazione" name="destinazione" placeholder="es. Roma, Museo..." required>
    </div>
    <div class="form-group">
        <label for="costo">Costo Stimato (&euro;)</label>
        <input type="number" step="0.01" id="costo" name="costo" min="0" required>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" id="annullaModal">Annulla</button>
    <button class="button" type="submit" form="formProposta" id="btnSubmitModal">Aggiungi</button>
</div>
</div>
</div>

<!-- Modal Elimina -->
<div class="modal-overlay hidden" id="modalDeleteOverlay">
<div class="modal">
<div class="modal-header">
    <h3>Elimina Proposta</h3>
    <button class="close-btn" id="chiudiDelete">&times;</button>
</div>
<div class="modal-body">
    <p>Eliminare la proposta <strong id="deleteDestName"></strong>?</p>
    <form id="formElimina" method="POST" action="catalogo.php">
        <input type="hidden" name="action" value="elimina_proposta">
        <input type="hidden" name="idGita" id="deleteIdGita">
    </form>
</div>
<div class="modal-footer">
    <button class="button cancel" id="annullaDelete">Annulla</button>
    <button class="button" style="background:#dc2626;" type="submit" form="formElimina">Elimina</button>
</div>
</div>
</div>

<!-- Modal Organizza -->
<div class="modal-overlay hidden" id="modalOrganizza">
<div class="modal wide-modal">
<div class="modal-header">
    <h3>Organizza Gita: <span id="orgDestName"></span></h3>
    <button class="close-btn" id="chiudiOrganizza">&times;</button>
</div>
<div class="modal-body">
<form id="formOrganizza" class="form-grid" method="POST" action="catalogo.php">
    <input type="hidden" name="action" value="organizza_gita">
    <input type="hidden" name="idGita" id="orgIdGita">
    <div class="form-group"><label>Data Inizio</label><input type="date" name="dataInizio" required></div>
    <div class="form-group"><label>Data Fine</label><input type="date" name="dataFine" required></div>
    <div class="form-group"><label>Orario Partenza</label><input type="time" name="orarioPartenza"></div>
    <div class="form-group"><label>Orario Arrivo</label><input type="time" name="orarioArrivo"></div>
    <div class="form-group"><label>Num. Alunni</label><input type="number" name="alunni" min="0" required></div>
    <div class="form-group"><label>Alunni Disabili</label><input type="number" name="alunniDisabili" min="0" value="0"></div>
    <div class="form-group"><label>Num. Docenti</label><input type="number" name="docenti" min="0" required></div>
    <div class="form-group"><label>Classi Partecipanti</label><input type="text" name="classi" placeholder="es. 3A, 3B"></div>
    <div class="form-group"><label>Costo Mezzi (&euro;)</label><input type="number" step="0.01" name="costoMezzi" min="0" value="0"></div>
    <div class="form-group"><label>Costo Attivita (&euro;)</label><input type="number" step="0.01" name="costoAttivita" min="0" value="0"></div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" id="annullaOrganizza">Annulla</button>
    <button class="button" type="submit" form="formOrganizza">Conferma</button>
</div>
</div>
</div>

<!-- Modal Successo -->
<div class="modal-overlay <?php echo $mostraSuccesso ? '' : 'hidden'; ?>" id="modalSuccesso">
<div class="modal">
<div class="modal-header"><h3>Proposta Inviata</h3></div>
<div class="modal-body"><p>La tua proposta e' stata inviata ed e' in attesa di approvazione.</p></div>
<div class="modal-footer">
    <button class="button" onclick="document.getElementById('modalSuccesso').classList.add('hidden')">OK</button>
</div>
</div>
</div>

<footer><div class="footer-container"><div class="footer-left"><p><strong>Gestione Gite Scolastiche</strong></p></div></div></footer>
</div>
<script>
var modalOverlay   = document.getElementById('modalOverlay');
var modalDelete    = document.getElementById('modalDeleteOverlay');
var modalOrganizza = document.getElementById('modalOrganizza');

document.getElementById('btnNuovaProposta').addEventListener('click', function() {
    document.getElementById('formProposta').reset();
    document.getElementById('formAction').value = 'nuova_proposta';
    document.getElementById('formIdGita').value = '';
    document.getElementById('modalTitolo').textContent = 'Nuova Proposta';
    document.getElementById('btnSubmitModal').textContent = 'Aggiungi';
    modalOverlay.classList.remove('hidden');
});
document.querySelectorAll('.btn-modifica').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('formAction').value    = 'modifica_proposta';
        document.getElementById('formIdGita').value   = btn.dataset.id;
        document.getElementById('destinazione').value = btn.dataset.dest;
        document.getElementById('costo').value        = btn.dataset.costo;
        document.getElementById('modalTitolo').textContent = 'Modifica Proposta';
        document.getElementById('btnSubmitModal').textContent = 'Salva';
        modalOverlay.classList.remove('hidden');
    });
});
document.querySelectorAll('.btn-elimina').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('deleteIdGita').value = btn.dataset.id;
        document.getElementById('deleteDestName').textContent = btn.dataset.dest;
        modalDelete.classList.remove('hidden');
    });
});
document.querySelectorAll('.btn-organizza').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('orgIdGita').value = btn.dataset.id;
        document.getElementById('orgDestName').textContent = btn.dataset.dest;
        document.getElementById('formOrganizza').reset();
        document.getElementById('orgIdGita').value = btn.dataset.id;
        modalOrganizza.classList.remove('hidden');
    });
});
function chiudiM() { modalOverlay.classList.add('hidden'); }
document.getElementById('chiudiModal').addEventListener('click', chiudiM);
document.getElementById('annullaModal').addEventListener('click', chiudiM);
function chiudiD() { modalDelete.classList.add('hidden'); }
document.getElementById('chiudiDelete').addEventListener('click', chiudiD);
document.getElementById('annullaDelete').addEventListener('click', chiudiD);
function chiudiO() { modalOrganizza.classList.add('hidden'); }
document.getElementById('chiudiOrganizza').addEventListener('click', chiudiO);
document.getElementById('annullaOrganizza').addEventListener('click', chiudiO);
window.addEventListener('click', function(e) {
    if (e.target === modalOverlay) chiudiM();
    if (e.target === modalDelete) chiudiD();
    if (e.target === modalOrganizza) chiudiO();
});
</script>
</body>
</html>
