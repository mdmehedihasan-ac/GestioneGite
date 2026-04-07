<?php
include('nav.php');

$idGita = isset($_GET['id']) ? intval($_GET['id']) : 0;
$messaggio = "";

if ($idGita == 0) {
    header("Location: mieGite.php");
    exit;
}

$queryGita = "SELECT g.*, p.Destinazione FROM gitaorganizzata g JOIN propostagita p ON g.IDProposta = p.IDProposta WHERE g.IDGita = $idGita";
$risultatoGita = mysqli_query($conn, $queryGita);
if (mysqli_num_rows($risultatoGita) == 0) {
    header("Location: mieGite.php");
    exit;
}
$gita = mysqli_fetch_assoc($risultatoGita);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

    if ($_POST['action'] == 'aggiungi_partecipante') {
        $nome = $_POST['nome'] ?? '';
        $cognome = $_POST['cognome'] ?? '';
        $classe = $_POST['classe'] ?? '';
        $descrizione = $_POST['descrizione'] ?? '';

        $istr = mysqli_prepare($conn, "INSERT INTO partecipanti (IDGita, Nome, Cognome, Classe, Descrizione) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($istr, "issss", $idGita, $nome, $cognome, $classe, $descrizione);

        if (mysqli_stmt_execute($istr)) {
            $messaggio = "<div class='msg-ok'>Partecipante aggiunto con successo.</div>";
        } else {
            $messaggio = "<div class='msg-err'>Errore durante l'aggiunta del partecipante.</div>";
        }
        mysqli_stmt_close($istr);

    } elseif ($_POST['action'] == 'elimina_partecipante') {
        $idPart = intval($_POST['idPartecipante'] ?? 0);
        $istr = mysqli_prepare($conn, "DELETE FROM partecipanti WHERE IDPartecipante = ? AND IDGita = ?");
        mysqli_stmt_bind_param($istr, "ii", $idPart, $idGita);
        if (mysqli_stmt_execute($istr)) {
            $messaggio = "<div class='msg-ok'>Partecipante rimosso.</div>";
        } else {
            $messaggio = "<div class='msg-err'>Errore durante la rimozione.</div>";
        }
        mysqli_stmt_close($istr);
    }
}

$queryPart = "SELECT * FROM partecipanti WHERE IDGita = $idGita ORDER BY Cognome ASC, Nome ASC";
$risultatoPart = mysqli_query($conn, $queryPart);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partecipanti - <?php echo htmlspecialchars($gita['Destinazione']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="vetrina.css">
    <link rel="stylesheet" href="style_custom.css">
    <script src="vetrina.js" defer></script>
    <style>
        .msg-ok { background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .msg-err { background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .info-gita { display: flex; flex-wrap: wrap; gap: 1rem; background: var(--blue-50, #eff6ff); border-radius: 10px; padding: 1rem 1.5rem; margin-bottom: 2rem; }
        .info-gita span { font-size: 0.92rem; color: var(--blue-700, #1d4ed8); }
        .info-gita strong { color: #1e293b; }
    </style>
</head>
<body>
    <div class="container">
        <main class="content bozze-padding">

            <div class="hero-section" style="display:flex; justify-content:space-between; align-items:flex-end;">
                <div>
                    <h2 style="margin-bottom:0.5rem; color:var(--blue-700);">Partecipanti</h2>
                    <p>Gita: <strong><?php echo htmlspecialchars($gita['Destinazione']); ?></strong></p>
                </div>
                <a href="mieGite.php" class="button outline" style="text-decoration:none;">&#8592; Torna alle Mie Gite</a>
            </div>

            <div class="info-gita">
                <span><strong>Data inizio:</strong> <?php echo date('d/m/Y', strtotime($gita['DataInizio'])); ?></span>
                <span><strong>Data fine:</strong> <?php echo date('d/m/Y', strtotime($gita['DataFine'])); ?></span>
                <span><strong>Alunni previsti:</strong> <?php echo $gita['NumAlunni']; ?></span>
                <span><strong>Docenti:</strong> <?php echo $gita['NumDocentiAccompagnatori']; ?></span>
                <?php if ($gita['ClassiPartecipanti']) { ?>
                <span><strong>Classi:</strong> <?php echo htmlspecialchars($gita['ClassiPartecipanti']); ?></span>
                <?php } ?>
            </div>

            <?php echo $messaggio; ?>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                <h3 style="color:var(--blue-700);">Lista Partecipanti
                    <span style="font-size:0.9rem; font-weight:400; color:#64748b;">(<?php echo mysqli_num_rows($risultatoPart); ?> inseriti)</span>
                </h3>
                <button class="button" id="btnAggiungi">+ Aggiungi Partecipante</button>
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
                                <th>Note</th>
                                <th>Rimuovi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $risultatoPart = mysqli_query($conn, $queryPart);
                                $numero = 1;
                                if (mysqli_num_rows($risultatoPart) > 0) {
                                    while ($riga = mysqli_fetch_assoc($risultatoPart)) {
                                        echo "<tr>";
                                        echo "<td>" . $numero++ . "</td>";
                                        echo "<td>" . htmlspecialchars($riga['Cognome']) . "</td>";
                                        echo "<td>" . htmlspecialchars($riga['Nome']) . "</td>";
                                        echo "<td>" . htmlspecialchars($riga['Classe']) . "</td>";
                                        echo "<td>" . htmlspecialchars($riga['Descrizione'] ?? '') . "</td>";
                                        echo "<td>
                                            <form method='POST' action='partecipanti.php?id=$idGita' style='margin:0;'>
                                                <input type='hidden' name='action' value='elimina_partecipante'>
                                                <input type='hidden' name='idPartecipante' value='" . $riga['IDPartecipante'] . "'>
                                                <button type='submit' class='xs cancel'>Rimuovi</button>
                                            </form>
                                        </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' style='text-align:center;'>Nessun partecipante inserito.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

        <div class="modal-overlay hidden" id="modalAggiungi">
            <div class="modal wide-modal">
                <div class="modal-header">
                    <h3>Aggiungi Partecipante</h3>
                    <button class="close-btn" id="chiudiAggiungi">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formAggiungi" class="form-grid" method="POST" action="partecipanti.php?id=<?php echo $idGita; ?>">
                        <input type="hidden" name="action" value="aggiungi_partecipante">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" placeholder="es. Mario" required>
                        </div>
                        <div class="form-group">
                            <label for="cognome">Cognome</label>
                            <input type="text" id="cognome" name="cognome" placeholder="es. Rossi" required>
                        </div>
                        <div class="form-group">
                            <label for="classe">Classe</label>
                            <input type="text" id="classe" name="classe" placeholder="es. 5A" required>
                        </div>
                        <div class="form-group">
                            <label for="descrizione">Note (opzionale)</label>
                            <input type="text" id="descrizione" name="descrizione" placeholder="es. alunno disabile, allergie...">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="button cancel" id="annullaAggiungi">Annulla</button>
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
        var modaleAggiungi = document.getElementById('modalAggiungi');

        document.getElementById('btnAggiungi').addEventListener('click', function() {
            document.getElementById('formAggiungi').reset();
            modaleAggiungi.classList.remove('hidden');
        });

        function chiudiAggiungi() {
            modaleAggiungi.classList.add('hidden');
        }

        document.getElementById('chiudiAggiungi').addEventListener('click', chiudiAggiungi);
        document.getElementById('annullaAggiungi').addEventListener('click', chiudiAggiungi);

        window.addEventListener('click', function(e) {
            if (e.target === modaleAggiungi) chiudiAggiungi();
        });
    </script>
</body>
</html>
