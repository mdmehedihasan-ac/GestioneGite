<?php<?php

include('nav.php');include('nav.php');



$idGita = isset($_GET['id']) ? intval($_GET['id']) : 0;$idGita = isset($_GET['id']) ? intval($_GET['id']) : 0;

$messaggio = "";$messaggio = "";



if ($idGita == 0) {if ($idGita == 0) {

    header("Location: mieGite.php");    header("Location: mieGite.php");

    exit;    exit;

}}



$queryGita = "SELECT g.*, s.Stato FROM gitaorganizzata g JOIN statogita s ON g.IDStato = s.IDStato WHERE g.IDGita = $idGita";$queryGita = "SELECT g.*, p.Destinazione FROM gitaorganizzata g JOIN propostagita p ON g.IDProposta = p.IDProposta WHERE g.IDGita = $idGita";

$risultatoGita = mysqli_query($conn, $queryGita);$risultatoGita = mysqli_query($conn, $queryGita);

if (mysqli_num_rows($risultatoGita) == 0) {if (mysqli_num_rows($risultatoGita) == 0) {

    header("Location: mieGite.php");    header("Location: mieGite.php");

    exit;    exit;

}}

$gita = mysqli_fetch_assoc($risultatoGita);$gita = mysqli_fetch_assoc($risultatoGita);

$dest = htmlspecialchars($gita['ClassiPartecipanti'] ?? 'Gita N.' . $idGita);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

    if ($_POST['action'] == 'aggiungi_partecipante') {

    if ($_POST['action'] == 'aggiungi_partecipante') {        $nome = $_POST['nome'] ?? '';

        $nome = $_POST['nome'] ?? '';        $cognome = $_POST['cognome'] ?? '';

        $cognome = $_POST['cognome'] ?? '';        $classe = $_POST['classe'] ?? '';

        $classe = $_POST['classe'] ?? '';        $descrizione = $_POST['descrizione'] ?? '';

        $descrizione = $_POST['descrizione'] ?? '';

        $istr = mysqli_prepare($conn, "INSERT INTO partecipanti (IDGita, Nome, Cognome, Classe, Descrizione) VALUES (?, ?, ?, ?, ?)");

        $istr = mysqli_prepare($conn, "INSERT INTO partecipanti (IDGita, Nome, Cognome, Classe, Descrizione) VALUES (?, ?, ?, ?, ?)");        mysqli_stmt_bind_param($istr, "issss", $idGita, $nome, $cognome, $classe, $descrizione);

        mysqli_stmt_bind_param($istr, "issss", $idGita, $nome, $cognome, $classe, $descrizione);

        if (mysqli_stmt_execute($istr)) {        if (mysqli_stmt_execute($istr)) {

            $messaggio = "<div style='background-color:#d4edda;color:#155724;padding:10px;margin-bottom:20px;border-radius:5px;'>Partecipante aggiunto con successo.</div>";            $messaggio = "<div class='msg-ok'>Partecipante aggiunto con successo.</div>";

        } else {        } else {

            $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore durante l'aggiunta.</div>";            $messaggio = "<div class='msg-err'>Errore durante l'aggiunta del partecipante.</div>";

        }        }

        mysqli_stmt_close($istr);        mysqli_stmt_close($istr);



    } elseif ($_POST['action'] == 'elimina_partecipante') {    } elseif ($_POST['action'] == 'elimina_partecipante') {

        $idPart = intval($_POST['idPartecipante'] ?? 0);        $idPart = intval($_POST['idPartecipante'] ?? 0);

        $istr = mysqli_prepare($conn, "DELETE FROM partecipanti WHERE IDPartecipante = ? AND IDGita = ?");        $istr = mysqli_prepare($conn, "DELETE FROM partecipanti WHERE IDPartecipante = ? AND IDGita = ?");

        mysqli_stmt_bind_param($istr, "ii", $idPart, $idGita);        mysqli_stmt_bind_param($istr, "ii", $idPart, $idGita);

        if (mysqli_stmt_execute($istr)) {        if (mysqli_stmt_execute($istr)) {

            $messaggio = "<div style='background-color:#d4edda;color:#155724;padding:10px;margin-bottom:20px;border-radius:5px;'>Partecipante rimosso.</div>";            $messaggio = "<div class='msg-ok'>Partecipante rimosso.</div>";

        } else {        } else {

            $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore durante la rimozione.</div>";            $messaggio = "<div class='msg-err'>Errore durante la rimozione.</div>";

        }        }

        mysqli_stmt_close($istr);        mysqli_stmt_close($istr);

    }    }

}}



$queryPart = "SELECT * FROM partecipanti WHERE IDGita = $idGita ORDER BY Cognome ASC, Nome ASC";$queryPart = "SELECT * FROM partecipanti WHERE IDGita = $idGita ORDER BY Cognome ASC, Nome ASC";

?>$risultatoPart = mysqli_query($conn, $queryPart);

<!DOCTYPE html>?>

<html lang="it"><!DOCTYPE html>

<head><html lang="it">

    <meta charset="UTF-8"><head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <meta charset="UTF-8">

    <title>Partecipanti - <?php echo $dest; ?></title>    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">    <title>Partecipanti - <?php echo htmlspecialchars($gita['Destinazione']); ?></title>

    <link rel="stylesheet" href="style.css">    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="vetrina.css">    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="style_custom.css">    <link rel="stylesheet" href="vetrina.css">

    <script src="vetrina.js" defer></script>    <link rel="stylesheet" href="style_custom.css">

</head>    <script src="vetrina.js" defer></script>

<body>    <style>

    <div class="container">        .msg-ok { background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px; }

        <main class="content bozze-padding">        .msg-err { background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px; }

        .info-gita { display: flex; flex-wrap: wrap; gap: 1rem; background: var(--blue-50, #eff6ff); border-radius: 10px; padding: 1rem 1.5rem; margin-bottom: 2rem; }

            <div class="hero-section" style="display:flex; justify-content:space-between; align-items:flex-end;">        .info-gita span { font-size: 0.92rem; color: var(--blue-700, #1d4ed8); }

                <div>        .info-gita strong { color: #1e293b; }

                    <h2 style="margin-bottom:0.5rem; color:var(--blue-700);">Partecipanti</h2>    </style>

                    <p>Gita: <strong><?php echo $dest; ?></strong></p></head>

                </div><body>

                <a href="mieGite.php" class="button outline" style="text-decoration:none;">&#8592; Torna alle Mie Gite</a>    <div class="container">

            </div>        <main class="content bozze-padding">



            <div style="display:flex;flex-wrap:wrap;gap:1rem;background:var(--blue-50,#eff6ff);border-radius:10px;padding:1rem 1.5rem;margin-bottom:2rem;">            <div class="hero-section" style="display:flex; justify-content:space-between; align-items:flex-end;">

                <span style="font-size:0.92rem;color:var(--blue-700,#1d4ed8);"><strong style="color:#1e293b;">Data inizio:</strong> <?php echo $gita['DataInizio'] ? date('d/m/Y', strtotime($gita['DataInizio'])) : '-'; ?></span>                <div>

                <span style="font-size:0.92rem;color:var(--blue-700,#1d4ed8);"><strong style="color:#1e293b;">Data fine:</strong> <?php echo $gita['DataFine'] ? date('d/m/Y', strtotime($gita['DataFine'])) : '-'; ?></span>                    <h2 style="margin-bottom:0.5rem; color:var(--blue-700);">Partecipanti</h2>

                <span style="font-size:0.92rem;color:var(--blue-700,#1d4ed8);"><strong style="color:#1e293b;">Alunni previsti:</strong> <?php echo $gita['NumAlunni']; ?></span>                    <p>Gita: <strong><?php echo htmlspecialchars($gita['Destinazione']); ?></strong></p>

                <span style="font-size:0.92rem;color:var(--blue-700,#1d4ed8);"><strong style="color:#1e293b;">Docenti:</strong> <?php echo $gita['NumDocentiAccompagnatori']; ?></span>                </div>

                <?php if ($gita['ClassiPartecipanti']) { ?>                <a href="mieGite.php" class="button outline" style="text-decoration:none;">&#8592; Torna alle Mie Gite</a>

                <span style="font-size:0.92rem;color:var(--blue-700,#1d4ed8);"><strong style="color:#1e293b;">Classi:</strong> <?php echo $dest; ?></span>            </div>

                <?php } ?>

            </div>            <div class="info-gita">

                <span><strong>Data inizio:</strong> <?php echo date('d/m/Y', strtotime($gita['DataInizio'])); ?></span>

            <?php echo $messaggio; ?>                <span><strong>Data fine:</strong> <?php echo date('d/m/Y', strtotime($gita['DataFine'])); ?></span>

                <span><strong>Alunni previsti:</strong> <?php echo $gita['NumAlunni']; ?></span>

            <?php                <span><strong>Docenti:</strong> <?php echo $gita['NumDocentiAccompagnatori']; ?></span>

                $risultatoPart = mysqli_query($conn, $queryPart);                <?php if ($gita['ClassiPartecipanti']) { ?>

                $totPart = mysqli_num_rows($risultatoPart);                <span><strong>Classi:</strong> <?php echo htmlspecialchars($gita['ClassiPartecipanti']); ?></span>

            ?>                <?php } ?>

            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">

                <h3 style="color:var(--blue-700);">Lista Partecipanti            <?php echo $messaggio; ?>

                    <span style="font-size:0.9rem;font-weight:400;color:#64748b;">(<?php echo $totPart; ?> inseriti)</span>

                </h3>            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">

                <button class="button" id="btnAggiungi">+ Aggiungi Partecipante</button>                <h3 style="color:var(--blue-700);">Lista Partecipanti

            </div>                    <span style="font-size:0.9rem; font-weight:400; color:#64748b;">(<?php echo mysqli_num_rows($risultatoPart); ?> inseriti)</span>

                </h3>

            <div class="table-section">                <button class="button" id="btnAggiungi">+ Aggiungi Partecipante</button>

                <div class="table-container">            </div>

                    <table>

                        <thead>            <div class="table-section">

                            <tr>                <div class="table-container">

                                <th>#</th>                    <table>

                                <th>Cognome</th>                        <thead>

                                <th>Nome</th>                            <tr>

                                <th>Classe</th>                                <th>#</th>

                                <th>Note</th>                                <th>Cognome</th>

                                <th>Rimuovi</th>                                <th>Nome</th>

                            </tr>                                <th>Classe</th>

                        </thead>                                <th>Note</th>

                        <tbody>                                <th>Rimuovi</th>

                            <?php                            </tr>

                                $numero = 1;                        </thead>

                                if ($totPart > 0) {                        <tbody>

                                    while ($riga = mysqli_fetch_assoc($risultatoPart)) {                            <?php

                                        echo "<tr>";                                $risultatoPart = mysqli_query($conn, $queryPart);

                                        echo "<td>" . $numero++ . "</td>";                                $numero = 1;

                                        echo "<td>" . htmlspecialchars($riga['Cognome']) . "</td>";                                if (mysqli_num_rows($risultatoPart) > 0) {

                                        echo "<td>" . htmlspecialchars($riga['Nome']) . "</td>";                                    while ($riga = mysqli_fetch_assoc($risultatoPart)) {

                                        echo "<td>" . htmlspecialchars($riga['Classe']) . "</td>";                                        echo "<tr>";

                                        echo "<td>" . htmlspecialchars($riga['Descrizione'] ?? '') . "</td>";                                        echo "<td>" . $numero++ . "</td>";

                                        echo "<td>                                        echo "<td>" . htmlspecialchars($riga['Cognome']) . "</td>";

                                            <form method='POST' action='partecipanti.php?id=$idGita' style='margin:0;'>                                        echo "<td>" . htmlspecialchars($riga['Nome']) . "</td>";

                                                <input type='hidden' name='action' value='elimina_partecipante'>                                        echo "<td>" . htmlspecialchars($riga['Classe']) . "</td>";

                                                <input type='hidden' name='idPartecipante' value='" . $riga['IDPartecipante'] . "'>                                        echo "<td>" . htmlspecialchars($riga['Descrizione'] ?? '') . "</td>";

                                                <button type='submit' class='xs cancel'>Rimuovi</button>                                        echo "<td>

                                            </form>                                            <form method='POST' action='partecipanti.php?id=$idGita' style='margin:0;'>

                                        </td>";                                                <input type='hidden' name='action' value='elimina_partecipante'>

                                        echo "</tr>";                                                <input type='hidden' name='idPartecipante' value='" . $riga['IDPartecipante'] . "'>

                                    }                                                <button type='submit' class='xs cancel'>Rimuovi</button>

                                } else {                                            </form>

                                    echo "<tr><td colspan='6' style='text-align:center;'>Nessun partecipante inserito.</td></tr>";                                        </td>";

                                }                                        echo "</tr>";

                            ?>                                    }

                        </tbody>                                } else {

                    </table>                                    echo "<tr><td colspan='6' style='text-align:center;'>Nessun partecipante inserito.</td></tr>";

                </div>                                }

            </div>                            ?>

                        </tbody>

        </main>                    </table>

                </div>

        <div class="modal-overlay hidden" id="modalAggiungi">            </div>

            <div class="modal wide-modal">

                <div class="modal-header">        </main>

                    <h3>Aggiungi Partecipante</h3>

                    <button class="close-btn" id="chiudiAggiungi">&times;</button>        <div class="modal-overlay hidden" id="modalAggiungi">

                </div>            <div class="modal wide-modal">

                <div class="modal-body">                <div class="modal-header">

                    <form id="formAggiungi" class="form-grid" method="POST" action="partecipanti.php?id=<?php echo $idGita; ?>">                    <h3>Aggiungi Partecipante</h3>

                        <input type="hidden" name="action" value="aggiungi_partecipante">                    <button class="close-btn" id="chiudiAggiungi">&times;</button>

                        <div class="form-group">                </div>

                            <label for="nome">Nome</label>                <div class="modal-body">

                            <input type="text" id="nome" name="nome" placeholder="es. Mario" required>                    <form id="formAggiungi" class="form-grid" method="POST" action="partecipanti.php?id=<?php echo $idGita; ?>">

                        </div>                        <input type="hidden" name="action" value="aggiungi_partecipante">

                        <div class="form-group">                        <div class="form-group">

                            <label for="cognome">Cognome</label>                            <label for="nome">Nome</label>

                            <input type="text" id="cognome" name="cognome" placeholder="es. Rossi" required>                            <input type="text" id="nome" name="nome" placeholder="es. Mario" required>

                        </div>                        </div>

                        <div class="form-group">                        <div class="form-group">

                            <label for="classe">Classe</label>                            <label for="cognome">Cognome</label>

                            <input type="text" id="classe" name="classe" placeholder="es. 5A" required>                            <input type="text" id="cognome" name="cognome" placeholder="es. Rossi" required>

                        </div>                        </div>

                        <div class="form-group">                        <div class="form-group">

                            <label for="descrizione">Note (opzionale)</label>                            <label for="classe">Classe</label>

                            <input type="text" id="descrizione" name="descrizione" placeholder="es. allergie, disabilit&agrave;...">                            <input type="text" id="classe" name="classe" placeholder="es. 5A" required>

                        </div>                        </div>

                    </form>                        <div class="form-group">

                </div>                            <label for="descrizione">Note (opzionale)</label>

                <div class="modal-footer">                            <input type="text" id="descrizione" name="descrizione" placeholder="es. alunno disabile, allergie...">

                    <button class="button cancel" id="annullaAggiungi">Annulla</button>                        </div>

                    <button class="button" type="submit" form="formAggiungi">Aggiungi</button>                    </form>

                </div>                </div>

            </div>                <div class="modal-footer">

        </div>                    <button class="button cancel" id="annullaAggiungi">Annulla</button>

                    <button class="button" type="submit" form="formAggiungi">Aggiungi</button>

        <footer>                </div>

            <div class="footer-container">            </div>

                <div class="footer-left">        </div>

                    <p><strong>Gestione Gite Scolastiche</strong></p>

                </div>        <footer>

            </div>            <div class="footer-container">

        </footer>                <div class="footer-left">

    </div>                    <p><strong>Gestione Gite Scolastiche</strong></p>

                </div>

    <script>            </div>

        var modaleAggiungi = document.getElementById('modalAggiungi');        </footer>

    </div>

        document.getElementById('btnAggiungi').addEventListener('click', function() {

            document.getElementById('formAggiungi').reset();    <script>

            modaleAggiungi.classList.remove('hidden');        var modaleAggiungi = document.getElementById('modalAggiungi');

        });

        document.getElementById('btnAggiungi').addEventListener('click', function() {

        function chiudiAggiungi() { modaleAggiungi.classList.add('hidden'); }            document.getElementById('formAggiungi').reset();

        document.getElementById('chiudiAggiungi').addEventListener('click', chiudiAggiungi);            modaleAggiungi.classList.remove('hidden');

        document.getElementById('annullaAggiungi').addEventListener('click', chiudiAggiungi);        });

        window.addEventListener('click', function(e) {

            if (e.target === modaleAggiungi) chiudiAggiungi();        function chiudiAggiungi() {

        });            modaleAggiungi.classList.add('hidden');

    </script>        }

</body>

</html>        document.getElementById('chiudiAggiungi').addEventListener('click', chiudiAggiungi);

        document.getElementById('annullaAggiungi').addEventListener('click', chiudiAggiungi);

        window.addEventListener('click', function(e) {
            if (e.target === modaleAggiungi) chiudiAggiungi();
        });
    </script>
</body>
</html>
