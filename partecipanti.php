<?php
include('nav.php');

$idGita = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($idGita === 0) {
    header("Location: mieGite.php");
    exit;
}

$idUtenteLoggato = intval($_SESSION['id_utente'] ?? 0);
$ruolo           = $_SESSION['ruolo']     ?? 0;

// Verifica accesso: deve essere autore oppure accompagnatore della gita5
$sqlGita = "SELECT * FROM gite5 WHERE idGita = $idGita";
$resGita = mysqli_query($conn, $sqlGita);
if (!$resGita || mysqli_num_rows($resGita) == 0) {
    header("Location: mieGite.php");
    exit;
}
$gita = mysqli_fetch_assoc($resGita);

// Controlla che sia autore o accompagnatore
$isAutore = ($gita['idUtente'] == $idUtenteLoggato);
$chkAcc = mysqli_query($conn, "SELECT id FROM accompagnatori WHERE idgita=$idGita AND idutente=$idUtenteLoggato AND tipo_gita='5g'");
$isAccompagnatore = ($chkAcc && mysqli_num_rows($chkAcc) > 0);

if (!$isAutore && !$isAccompagnatore && $ruolo != 2) {
    header("Location: mieGite.php");
    exit;
}

$messaggio = '';

// ─── Handler aggiungi partecipante ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'aggiungi') {
    $nome      = mysqli_real_escape_string($conn, trim($_POST['nome']       ?? ''));
    $cognome   = mysqli_real_escape_string($conn, trim($_POST['cognome']    ?? ''));
    $classe    = mysqli_real_escape_string($conn, trim($_POST['classe']     ?? ''));
    $note      = mysqli_real_escape_string($conn, trim($_POST['note']       ?? ''));
    $documento = mysqli_real_escape_string($conn, trim($_POST['documento']  ?? ''));
    $nDoc      = mysqli_real_escape_string($conn, trim($_POST['nDocumento'] ?? ''));
    $scadenza  = trim($_POST['scadenza'] ?? '');
    $scadenza_s = $scadenza ? "'$scadenza'" : 'NULL';

    if ($nome !== '' && $cognome !== '' && $classe !== '' && $documento !== '' && $nDoc !== '' && $scadenza !== '') {
        $sql = "INSERT INTO partecipanti (idgita, nome, cognome, classe, descrizione, documento, nDocumento, scadenza) VALUES ($idGita, '$nome', '$cognome', '$classe', '$note', '$documento', '$nDoc', $scadenza_s)";
        $messaggio = mysqli_query($conn, $sql) ? 'ok' : 'error';
    } else {
        $messaggio = 'campi';
    }
}

// ─── Handler elimina partecipante ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'elimina') {
    $idPart = intval($_POST['id_part'] ?? 0);
    if ($idPart > 0) {
        mysqli_query($conn, "DELETE FROM partecipanti WHERE id = $idPart AND idgita = $idGita");
    }
    header("Location: partecipanti.php?id=$idGita");
    exit;
}

// ─── Handler modifica dati accompagnatore ────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'mod_acc') {
    $accId     = intval($_POST['acc_id'] ?? 0);
    $documento = mysqli_real_escape_string($conn, trim($_POST['acc_documento']  ?? ''));
    $nDoc      = mysqli_real_escape_string($conn, trim($_POST['acc_nDocumento'] ?? ''));
    $scadenza  = trim($_POST['acc_scadenza'] ?? '');
    $scadenza_s = $scadenza ? "'$scadenza'" : 'NULL';
    $note      = mysqli_real_escape_string($conn, trim($_POST['acc_note'] ?? ''));
    if ($accId > 0) {
        mysqli_query($conn, "UPDATE accompagnatori SET documento='$documento', nDocumento='$nDoc', scadenza=$scadenza_s, note='$note' WHERE id=$accId AND idgita=$idGita");
    }
    header("Location: partecipanti.php?id=$idGita&acc=1");
    exit;
}

// ─── Carica accompagnatori della gita ────────────────────────────────────────
$resAcc = mysqli_query($conn,
    "SELECT a.*, CONCAT(u.Nome, ' ', u.Cognome) AS nomeUtente, u.Nome AS nome_u, u.Cognome AS cognome_u
     FROM accompagnatori a JOIN utente u ON a.idutente = u.IDUtente
     WHERE a.idgita = $idGita AND a.tipo_gita = '5g'
     ORDER BY u.Cognome ASC, u.Nome ASC"
);

// ─── Carica partecipanti ──────────────────────────────────────────────────────
$resPart = mysqli_query($conn, "SELECT * FROM partecipanti WHERE idgita = $idGita ORDER BY cognome ASC, nome ASC");
$totPart = $resPart ? mysqli_num_rows($resPart) : 0;

$destDisplay = htmlspecialchars($gita['destinazione'] ?? '');
$periodoDisp = htmlspecialchars($gita['periodo']      ?? '');
$mezzoDisp   = htmlspecialchars($gita['mezzo']        ?? '');
$classiDisp  = htmlspecialchars($gita['classi']       ?? '');
$gi = $gita['giornoInizio'] ? date('d/m/Y', strtotime($gita['giornoInizio'])) : '';
$gf = $gita['giornoFine']   ? date('d/m/Y', strtotime($gita['giornoFine']))   : '';
$numAlunniDisp = $gita['numAlunni'] ?? '';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partecipanti</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="vetrina.css">
    <link rel="stylesheet" href="style_custom.css">
    <script src="vetrina.js" defer></script>
</head>
<body>
<div class="container">
<main class="content bozze-padding">

    <div class="hero-section" style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:1.5rem;">
        <div>
            <h2 style="margin-bottom:0.25rem;color:var(--blue-700);">Partecipanti</h2>
            <p style="color:#64748b;margin:0;">Gita: <strong><?php echo $destDisplay; ?></strong></p>
        </div>
        <a href="mieGite.php" class="button outline" style="text-decoration:none;">&#8592; Torna alle Mie Gite</a>
    </div>

    <div style="display:flex;flex-wrap:wrap;gap:1rem;background:var(--blue-50,#eff6ff);border-radius:10px;padding:1rem 1.5rem;margin-bottom:1.5rem;">
        <?php if ($gi): ?><span style="font-size:0.9rem;"><strong>Date:</strong> <?php echo $gi; ?> &rarr; <?php echo $gf; ?></span><?php endif; ?>
        <?php if ($periodoDisp): ?><span style="font-size:0.9rem;"><strong>Periodo:</strong> <?php echo $periodoDisp; ?></span><?php endif; ?>
        <?php if ($mezzoDisp): ?><span style="font-size:0.9rem;"><strong>Mezzo:</strong> <?php echo $mezzoDisp; ?></span><?php endif; ?>
        <?php if ($classiDisp): ?><span style="font-size:0.9rem;"><strong>Classi:</strong> <?php echo $classiDisp; ?></span><?php endif; ?>
        <?php if ($numAlunniDisp !== ''): ?><span style="font-size:0.9rem;"><strong>Alunni previsti:</strong> <?php echo $numAlunniDisp; ?></span><?php endif; ?>
    </div>

    <?php if (($_GET['acc'] ?? '') === '1'): ?>
        <div class="alert alert-success" style="margin-bottom:1rem;">Dati accompagnatore aggiornati.</div>
    <?php endif; ?>
    <?php if ($messaggio === 'ok'): ?>
        <div class="alert alert-success" style="margin-bottom:1rem;">Partecipante aggiunto con successo.</div>
    <?php elseif ($messaggio === 'error'): ?>
        <div class="alert alert-error" style="margin-bottom:1rem;">Errore durante l'inserimento. Riprova.</div>
    <?php elseif ($messaggio === 'campi'): ?>
        <div class="alert alert-warning" style="margin-bottom:1rem;">Compila tutti i campi obbligatori.</div>
    <?php endif; ?>

    <!-- ══ TABELLA ACCOMPAGNATORI ════════════════════════════════════════════ -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <h3 style="color:var(--blue-700);margin:0;">
            Accompagnatori
            <span style="font-size:0.88rem;font-weight:400;color:#64748b;">(<?php echo $resAcc ? mysqli_num_rows($resAcc) : 0; ?>)</span>
        </h3>
    </div>

    <div class="table-section" style="margin-bottom:2rem;">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Documento</th>
                        <th>N. Documento</th>
                        <th>Scadenza</th>
                        <th>Note</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($resAcc && mysqli_num_rows($resAcc) > 0):
                    $nAcc = 1;
                    while ($acc = mysqli_fetch_assoc($resAcc)):
                        $aId      = intval($acc['id']);
                        $aNome    = htmlspecialchars($acc['nome_u']    ?? '');
                        $aCognome = htmlspecialchars($acc['cognome_u'] ?? '');
                        $aDoc     = htmlspecialchars($acc['documento']  ?? '');
                        $aNDoc    = htmlspecialchars($acc['nDocumento'] ?? '');
                        $aScad    = $acc['scadenza'] ? date('d/m/Y', strtotime($acc['scadenza'])) : '';
                        $aNote    = htmlspecialchars($acc['note'] ?? '');
                        $aDocJ    = htmlspecialchars($acc['documento']  ?? '', ENT_QUOTES);
                        $aNDocJ   = htmlspecialchars($acc['nDocumento'] ?? '', ENT_QUOTES);
                        $aScadV   = $acc['scadenza'] ?? '';
                        $aNoteJ   = htmlspecialchars($acc['note'] ?? '', ENT_QUOTES);
                        // solo l'accompagnatore stesso (o commissione) puo modificare i propri dati
                        $canEdit = ($acc['idutente'] == $idUtenteLoggato || $ruolo == 2);
                ?>
                    <tr>
                        <td><?php echo $nAcc++; ?></td>
                        <td><?php echo $aNome; ?></td>
                        <td><?php echo $aCognome; ?></td>
                        <td><?php echo $aDoc  ?: '<span style="color:#94a3b8;">—</span>'; ?></td>
                        <td><?php echo $aNDoc ?: '<span style="color:#94a3b8;">—</span>'; ?></td>
                        <td><?php echo $aScad ?: '<span style="color:#94a3b8;">—</span>'; ?></td>
                        <td><?php echo $aNote ?: '<span style="color:#94a3b8;">—</span>'; ?></td>
                        <td>
                            <?php if ($canEdit): ?>
                            <button type="button" class="button xs"
                                data-acc-id="<?php echo $aId; ?>"
                                data-nome="<?php echo $aNome; ?> <?php echo $aCognome; ?>"
                                data-doc="<?php echo $aDocJ; ?>"
                                data-ndoc="<?php echo $aNDocJ; ?>"
                                data-scad="<?php echo $aScadV; ?>"
                                data-note="<?php echo $aNoteJ; ?>"
                                onclick="apriModAcc(this)">✏️ Modifica</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="8" style="text-align:center;color:#94a3b8;">Nessun accompagnatore registrato.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ══ TABELLA PARTECIPANTI ══════════════════════════════════════════════ -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <h3 style="color:var(--blue-700);margin:0;">
            Lista Partecipanti
            <span style="font-size:0.88rem;font-weight:400;color:#64748b;">(<?php echo $totPart; ?> inseriti)</span>
        </h3>
        <button class="button" onclick="document.getElementById('modalAggiungi').classList.remove('hidden')">+ Aggiungi Partecipante</button>
    </div>

    <div class="table-section">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cognome</th>
                        <th>Nome</th>
                        <th>Classe</th>
                        <th>Documento</th>
                        <th>N. Documento</th>
                        <th>Scadenza</th>
                        <th>Note</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($totPart > 0):
                    $n = 1;
                    while ($p = mysqli_fetch_assoc($resPart)):
                        $pId      = intval($p['id']);
                        $pNome    = htmlspecialchars($p['nome']);
                        $pCognome = htmlspecialchars($p['cognome']);
                        $pClasse  = htmlspecialchars($p['classe']);
                        $pDoc     = htmlspecialchars($p['documento']  ?? '');
                        $pNDoc    = htmlspecialchars($p['nDocumento'] ?? '');
                        $pScad    = $p['scadenza'] ? date('d/m/Y', strtotime($p['scadenza'])) : '';
                        $pNote    = htmlspecialchars($p['descrizione'] ?? '');
                ?>
                    <tr>
                        <td><?php echo $n++; ?></td>
                        <td><?php echo $pCognome; ?></td>
                        <td><?php echo $pNome; ?></td>
                        <td><?php echo $pClasse; ?></td>
                        <td><?php echo $pDoc  ?: ''; ?></td>
                        <td><?php echo $pNDoc ?: ''; ?></td>
                        <td><?php echo $pScad ?: ''; ?></td>
                        <td><?php echo $pNote ?: ''; ?></td>
                        <td>
                            <form method="POST" action="partecipanti.php?id=<?php echo $idGita; ?>" style="margin:0;" onsubmit="return confirm('Rimuovere questo partecipante?');">
                                <input type="hidden" name="action"  value="elimina">
                                <input type="hidden" name="id_part" value="<?php echo $pId; ?>">
                                <button type="submit" class="button cancel xs">Rimuovi</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="9" style="text-align:center;color:#94a3b8;">Nessun partecipante inserito.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<!-- ══════════════ MODAL — Modifica dati accompagnatore ══════════════════════ -->
<div class="modal-overlay hidden" id="modalModAcc">
<div class="modal wide-modal">
<div class="modal-header">
    <h3 id="modAccTit">Modifica dati documento</h3>
    <button class="close-btn" onclick="document.getElementById('modalModAcc').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
<form id="formModAcc" method="POST" action="partecipanti.php?id=<?php echo $idGita; ?>">
    <input type="hidden" name="action" value="mod_acc">
    <input type="hidden" name="acc_id" id="modAccId">
    <div class="form-grid">
        <div class="form-group">
            <label>Tipo documento</label>
            <select name="acc_documento" id="modAccDoc" class="form-control">
                <option value="">— Seleziona —</option>
                <option value="Carta d'identita">Carta d'identità</option>
                <option value="Passaporto">Passaporto</option>
            </select>
        </div>
        <div class="form-group">
            <label>N. Documento</label>
            <input type="text" name="acc_nDocumento" id="modAccNDoc" class="form-control" placeholder="es. CA12345AA">
        </div>
        <div class="form-group">
            <label>Scadenza documento</label>
            <input type="date" name="acc_scadenza" id="modAccScad" class="form-control">
        </div>
        <div class="form-group full-row">
            <label>Allergeni / Note</label>
            <input type="text" name="acc_note" id="modAccNote" class="form-control" placeholder="es. allergie, intolleranze...">
        </div>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modalModAcc').classList.add('hidden')">Annulla</button>
    <button class="button" onclick="document.getElementById('formModAcc').submit()">Salva</button>
</div>
</div>
</div>

<!-- ══════════════ MODAL — Aggiungi Partecipante ══════════════════════════════ -->
<div class="modal-overlay hidden" id="modalAggiungi">
<div class="modal wide-modal">
<div class="modal-header">
    <h3>Aggiungi Partecipante</h3>
    <button class="close-btn" onclick="document.getElementById('modalAggiungi').classList.add('hidden')">&times;</button>
</div>
<div class="modal-body">
<form id="formAggiungi" method="POST" action="partecipanti.php?id=<?php echo $idGita; ?>">
    <input type="hidden" name="action" value="aggiungi">
    <div class="form-grid">
        <div class="form-group">
            <label>Nome *</label>
            <input type="text" name="nome" class="form-control" placeholder="es. Mario" required>
        </div>
        <div class="form-group">
            <label>Cognome *</label>
            <input type="text" name="cognome" class="form-control" placeholder="es. Rossi" required>
        </div>
        <div class="form-group">
            <label>Classe *</label>
            <select name="classe" class="form-control" required>
                <option value="">— Seleziona —</option>
                <option value="5AII">5AII</option>
                <option value="5BII">5BII</option>
                <option value="5CII">5CII</option>
                <option value="5DIT">5DIT</option>
                <option value="5AEA">5AEA</option>
                <option value="5BEA">5BEA</option>
                <option value="5CEA">5CEA</option>
                <option value="5AL">5AL</option>
                <option value="5BL">5BL</option>
                <option value="5CL">5CL</option>
            </select>
        </div>
        <div class="form-group">
            <label>Tipo documento *</label>
            <select name="documento" class="form-control" required>
                <option value="">— Seleziona —</option>
                <option value="Carta d'identita">Carta d'identità</option>
                <option value="Passaporto">Passaporto</option>
            </select>
        </div>
        <div class="form-group">
            <label>N. Documento *</label>
            <input type="text" name="nDocumento" class="form-control" placeholder="es. CA12345AA" required>
        </div>
        <div class="form-group">
            <label>Scadenza documento *</label>
            <input type="date" name="scadenza" class="form-control" required>
        </div>
        <div class="form-group full-row">
            <label>Allergeni / Note (facoltativo)</label>
            <input type="text" name="note" class="form-control" placeholder="es. allergie, intolleranze...">
        </div>
    </div>
</form>
</div>
<div class="modal-footer">
    <button class="button cancel" onclick="document.getElementById('modalAggiungi').classList.add('hidden')">Annulla</button>
    <button class="button" type="submit" form="formAggiungi">Aggiungi</button>
</div>
</div>
</div>

<footer>
    <div class="footer-container">
        <div class="footer-left">
            <p><strong>Gestione Gite Scolastiche</strong></p>
        </div>
    </div>
</footer>
</div>

<script>
function apriModAcc(btn) {
    var d = btn.dataset;
    document.getElementById('modAccTit').textContent  = 'Dati documento: ' + d.nome;
    document.getElementById('modAccId').value         = d.accId;
    document.getElementById('modAccDoc').value        = d.doc  || '';
    document.getElementById('modAccNDoc').value       = d.ndoc || '';
    document.getElementById('modAccScad').value       = d.scad || '';
    document.getElementById('modAccNote').value       = d.note || '';
    document.getElementById('modalModAcc').classList.remove('hidden');
}

window.addEventListener('click', function(e) {
    ['modalAggiungi','modalModAcc'].forEach(function(id) {
        var m = document.getElementById(id);
        if (e.target === m) m.classList.add('hidden');
    });
});
<?php if ($messaggio === 'campi' || $messaggio === 'error'): ?>
document.getElementById('modalAggiungi').classList.remove('hidden');
<?php endif; ?>
</script>
</body>
</html>
