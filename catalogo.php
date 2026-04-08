<?php <?php 

include('nav.php'); include('nav.php'); 

$messaggio = "";$messaggio = "";

$mostraSuccesso = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {    if ($_POST['action'] == 'nuova_proposta') {

        $destinazione = $_POST['destinazione'] ?? '';

    if ($_POST['action'] == 'nuova_proposta') {        $mezzo = $_POST['mezzo'] ?? '';

        $destinazione = $_POST['destinazione'] ?? '';        $periodo = $_POST['periodo'] ?? '';

        $costo = floatval($_POST['costo'] ?? 0);        $costo = $_POST['costo'] ?? 0;

        $idUtente = $_SESSION['id_utente'] ?? 0;        $minPart = $_POST['minPart'] ?? 0;

        $stato = 1;        $maxPart = $_POST['maxPart'] ?? 0;

        $idUtente = $_SESSION['id_utente'] ?? 0;

        $istr = mysqli_prepare($conn, "INSERT INTO gitaorganizzata (IDUtente, IDStato, ClassiPartecipanti, CostoTot) VALUES (?, ?, ?, ?)");

        mysqli_stmt_bind_param($istr, "iisd", $idUtente, $stato, $destinazione, $costo);        $istr = mysqli_prepare($conn, "INSERT INTO propostagita (Destinazione, MezzoDiTrasporto, Periodo, MinPartecipanti, MaxPartecipanti, Costo, IDUtente) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if (mysqli_stmt_execute($istr)) {        mysqli_stmt_bind_param($istr, "sssiidi", $destinazione, $mezzo, $periodo, $minPart, $maxPart, $costo, $idUtente);

            $mostraSuccesso = true;

        } else {        $mostraSuccesso = false;

            $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore durante l'aggiunta della proposta.</div>";

        }        if (mysqli_stmt_execute($istr)) {

        mysqli_stmt_close($istr);            $idNuovaProposta = mysqli_insert_id($conn);

            $stato = 1;

    } elseif ($_POST['action'] == 'modifica_proposta') {            $alunni = 0;

        $idGita = intval($_POST['idGita'] ?? 0);            $docenti = 0;

        $destinazione = $_POST['destinazione'] ?? '';            $costoTotale = $costo;

        $costo = floatval($_POST['costo'] ?? 0);

            $istrGita = mysqli_prepare($conn, "INSERT INTO gitaorganizzata (IDProposta, IDUtente, DataInizio, DataFine, NumAlunni, NumDocentiAccompagnatori, CostoTot, IDStato) VALUES (?, ?, CURDATE(), CURDATE(), ?, ?, ?, ?)");

        $istr = mysqli_prepare($conn, "UPDATE gitaorganizzata SET ClassiPartecipanti=?, CostoTot=? WHERE IDGita=?");            mysqli_stmt_bind_param($istrGita, "iiiidi", $idNuovaProposta, $idUtente, $alunni, $docenti, $costoTotale, $stato);

        mysqli_stmt_bind_param($istr, "sdi", $destinazione, $costo, $idGita);            mysqli_stmt_execute($istrGita);

        if (mysqli_stmt_execute($istr)) {            mysqli_stmt_close($istrGita);

            $messaggio = "<div style='background-color:#d4edda;color:#155724;padding:10px;margin-bottom:20px;border-radius:5px;'>Proposta modificata con successo.</div>";

        } else {            $mostraSuccesso = true;

            $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore durante la modifica.</div>";        } else {

        }            $messaggio = "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>Errore durante l'aggiunta della proposta.</div>";

        mysqli_stmt_close($istr);        }

        mysqli_stmt_close($istr);

    } elseif ($_POST['action'] == 'organizza_gita') {

        $idGita = intval($_POST['idGita'] ?? 0);    } elseif ($_POST['action'] == 'modifica_proposta') {

        $dataInizio = $_POST['dataInizio'] ?? '';        $idProposta = $_POST['idProposta'] ?? 0;

        $dataFine = $_POST['dataFine'] ?? '';        $destinazione = $_POST['destinazione'] ?? '';

        $alunni = intval($_POST['alunni'] ?? 0);        $mezzo = $_POST['mezzo'] ?? '';

        $alunniDisabili = intval($_POST['alunniDisabili'] ?? 0);        $periodo = $_POST['periodo'] ?? '';

        $docenti = intval($_POST['docenti'] ?? 0);        $costo = $_POST['costo'] ?? 0;

        $classi = $_POST['classi'] ?? '';        $minPart = $_POST['minPart'] ?? 0;

        $orarioPartenza = $_POST['orarioPartenza'] ?: null;        $maxPart = $_POST['maxPart'] ?? 0;

        $orarioArrivo = $_POST['orarioArrivo'] ?: null;

        $costoMezzi = floatval($_POST['costoMezzi'] ?? 0);        $istr = mysqli_prepare($conn, "UPDATE propostagita SET Destinazione=?, MezzoDiTrasporto=?, Periodo=?, MinPartecipanti=?, MaxPartecipanti=?, Costo=? WHERE IDProposta=?");

        $costoAttivita = floatval($_POST['costoAttivita'] ?? 0);        mysqli_stmt_bind_param($istr, "sssiidi", $destinazione, $mezzo, $periodo, $minPart, $maxPart, $costo, $idProposta);

        $idUtente = $_SESSION['id_utente'] ?? 0;

        if (mysqli_stmt_execute($istr)) {

        $queryCosto = "SELECT CostoTot FROM gitaorganizzata WHERE IDGita = $idGita";            $messaggio = "<div style='background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>Proposta modificata con successo.</div>";

        $resCosto = mysqli_query($conn, $queryCosto);        } else {

        $rigaCosto = mysqli_fetch_assoc($resCosto);            $messaggio = "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>Errore durante la modifica della proposta.</div>";

        $costoBase = floatval($rigaCosto['CostoTot'] ?? 0);        }

        $costoTotale = ($costoBase * $alunni) + $costoMezzi + $costoAttivita;        mysqli_stmt_close($istr);

        $statoOrg = 4;

    } elseif ($_POST['action'] == 'organizza_gita') {

        $istr = mysqli_prepare($conn, "INSERT INTO gitaorganizzata (IDUtente, DataInizio, DataFine, NumAlunni, NumDocentiAccompagnatori, NumAlunniDisabili, CostoTot, IDStato, OrarioPartenza, OrarioArrivo, CostoMezzi, CostoAttivita, ClassiPartecipanti) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");        $idProposta = $_POST['idProposta'] ?? 0;

        mysqli_stmt_bind_param($istr, "issiiidissdds", $idUtente, $dataInizio, $dataFine, $alunni, $docenti, $alunniDisabili, $costoTotale, $statoOrg, $orarioPartenza, $orarioArrivo, $costoMezzi, $costoAttivita, $classi);        $dataInizio = $_POST['dataInizio'] ?? '';

        if (mysqli_stmt_execute($istr)) {        $dataFine = $_POST['dataFine'] ?? '';

            $messaggio = "<div style='background-color:#d4edda;color:#155724;padding:10px;margin-bottom:20px;border-radius:5px;'>Gita organizzata con successo!</div>";        $alunni = intval($_POST['alunni'] ?? 0);

        } else {        $alunniDisabili = intval($_POST['alunniDisabili'] ?? 0);

            $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore durante l'organizzazione della gita.</div>";        $docenti = intval($_POST['docenti'] ?? 0);

        }        $classi = $_POST['classi'] ?? '';

        mysqli_stmt_close($istr);        $orarioPartenza = $_POST['orarioPartenza'] ?? null;

        $orarioArrivo = $_POST['orarioArrivo'] ?? null;

    } elseif ($_POST['action'] == 'elimina_proposta') {        $costoMezzi = floatval($_POST['costoMezzi'] ?? 0);

        $idGita = intval($_POST['idGita'] ?? 0);        $costoAttivita = floatval($_POST['costoAttivita'] ?? 0);

        $istr = mysqli_prepare($conn, "DELETE FROM gitaorganizzata WHERE IDGita=?");        $idUtente = $_SESSION['id_utente'] ?? 0;

        mysqli_stmt_bind_param($istr, "i", $idGita);

        if (mysqli_stmt_execute($istr)) {        $queryProp = "SELECT Costo FROM propostagita WHERE IDProposta = $idProposta";

            $messaggio = "<div style='background-color:#d4edda;color:#155724;padding:10px;margin-bottom:20px;border-radius:5px;'>Proposta eliminata con successo.</div>";        $resProp = mysqli_query($conn, $queryProp);

        } else {        $rigaProp = mysqli_fetch_assoc($resProp);

            $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore durante l'eliminazione.</div>";        $costoTotale = ($rigaProp['Costo'] * $alunni) + $costoMezzi + $costoAttivita;

        }        $statoOrganizzata = 5;

        mysqli_stmt_close($istr);

    }        $istr = mysqli_prepare($conn, "INSERT INTO gitaorganizzata (IDProposta, IDUtente, DataInizio, DataFine, NumAlunni, NumDocentiAccompagnatori, NumAlunniDisabili, CostoTot, IDStato, OrarioPartenza, OrarioArrivo, CostoMezzi, CostoAttivita, ClassiPartecipanti) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

}        mysqli_stmt_bind_param($istr, "iissiiididdsss", $idProposta, $idUtente, $dataInizio, $dataFine, $alunni, $docenti, $alunniDisabili, $costoTotale, $statoOrganizzata, $orarioPartenza, $orarioArrivo, $costoMezzi, $costoAttivita, $classi);

?>

<!DOCTYPE html>        if (mysqli_stmt_execute($istr)) {

<html lang="it">            $messaggio = "<div style='background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>Gita organizzata con successo!</div>";

<head>        } else {

    <meta charset="UTF-8">            $messaggio = "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>Errore durante l'organizzazione della gita.</div>";

    <meta name="viewport" content="width=device-width, initial-scale=1.0">        }

    <title>Gestione Gite - Catalogo Proposte</title>        mysqli_stmt_close($istr);

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">    } elseif ($_POST['action'] == 'elimina_proposta') {

    <link rel="stylesheet" href="vetrina.css">        $idProposta = $_POST['idProposta'] ?? 0;

    <link rel="stylesheet" href="style_custom.css">

    <script src="vetrina.js"></script>        $eliminaGite = mysqli_prepare($conn, "DELETE FROM gitaorganizzata WHERE IDProposta=?");

</head>        mysqli_stmt_bind_param($eliminaGite, "i", $idProposta);

<body>        mysqli_stmt_execute($eliminaGite);

        mysqli_stmt_close($eliminaGite);

    <div class="container">

        <main class="content bozze-padding">        $istr = mysqli_prepare($conn, "DELETE FROM propostagita WHERE IDProposta=?");

                    mysqli_stmt_bind_param($istr, "i", $idProposta);

            <div class="hero-section" style="display:flex; justify-content:space-between; align-items:flex-end;">

                <div>        if (mysqli_stmt_execute($istr)) {

                    <h2 style="margin-bottom:1rem; color:var(--blue-700);">Catalogo Proposte</h2>            $messaggio = "<div style='background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>Proposta eliminata con successo.</div>";

                    <p>Elenco delle proposte approvate dalla Commissione. Scegli una proposta per organizzare la gita.</p>        } else {

                </div>            $messaggio = "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>Errore durante l'eliminazione della proposta.</div>";

                <div>        }

                    <button class="button" id="btnNuova">Nuova Proposta</button>        mysqli_stmt_close($istr);

                </div>    }

            </div>}

?>

            <?php echo $messaggio; ?><!DOCTYPE html>

<html lang="it">

            <div class="table-section" style="margin-top:2rem;"><head>

                <div class="table-container table-catalogo">    <meta charset="UTF-8">

                    <table>    <meta name="viewport" content="width=device-width, initial-scale=1.0">

                        <thead>    <title>Gestione Gite - Catalogo Proposte</title>

                            <tr>    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

                                <th>Destinazione / Descrizione</th>    

                                <th>Costo Stimato</th>    <link rel="stylesheet" href="style.css">

                                <th>Docente</th>    <link rel="stylesheet" href="vetrina.css">

                                <th>Modifica</th>    <link rel="stylesheet" href="style_custom.css">

                                <th>Elimina</th>    <script src="vetrina.js"></script>

                                <th>Organizza</th></head>

                            </tr><body>

                        </thead>

                        <tbody>    <div class="container">

                            <?php         <main class="content bozze-padding">

                                $query = "SELECT g.*, u.Nome, u.Cognome FROM gitaorganizzata g JOIN utente u ON g.IDUtente = u.IDUtente WHERE g.IDStato = 2 ORDER BY g.IDGita DESC";            

                                $risultato = mysqli_query($conn, $query);            <div class="hero-section" style="display: flex; justify-content: space-between; align-items: flex-end;">

                <div>

                                if (mysqli_num_rows($risultato) > 0) {                    <h2 style="margin-bottom: 1rem; color: var(--blue-700);">Catalogo Proposte</h2>

                                    while ($row = mysqli_fetch_assoc($risultato)) {                    <p>Elenco delle mete approvate dalla Commissione. Scegli una proposta per organizzare la gita.</p>

                                        $dest = htmlspecialchars($row['ClassiPartecipanti'] ?? 'N/D');                </div>

                                        $docente = htmlspecialchars($row['Nome'] . ' ' . $row['Cognome']);                <div>

                                        $costo = number_format($row['CostoTot'], 2, ',', '.');                    <button class="button" id="btnNuova">Nuova Proposta</button>

                                        echo "<tr>";                </div>

                                        echo "<td><strong>$dest</strong></td>";            </div>

                                        echo "<td>&euro; $costo</td>";

                                        echo "<td>$docente</td>";            <?php echo $messaggio; ?>

                                        echo "<td><button class='xs outline btn-modifica' data-id='{$row['IDGita']}' data-dest='" . htmlspecialchars($row['ClassiPartecipanti'] ?? '') . "' data-costo='{$row['CostoTot']}'>Modifica</button></td>";

                                        echo "<td><button class='xs cancel btn-elimina' data-id='{$row['IDGita']}'>Elimina</button></td>";            <div class="table-section" style="margin-top: 2rem;">

                                        echo "<td><button class='xs btn-organizza' data-id='{$row['IDGita']}' data-dest='" . htmlspecialchars($row['ClassiPartecipanti'] ?? 'Gita') . "' data-costo='{$row['CostoTot']}'>Organizza</button></td>";                <div class="table-container table-catalogo">

                                        echo "</tr>";                    <table>

                                    }                        <thead>

                                } else {                            <tr>

                                    echo "<tr><td colspan='6' style='text-align:center;'>Nessuna proposta approvata presente.</td></tr>";                                <th>Destinazione</th>

                                }                                <th>Mezzo di Trasporto</th>

                            ?>                                <th>Periodo</th>

                        </tbody>                                <th>Min Part.</th>

                    </table>                                <th>Max Part.</th>

                </div>                                <th>Costo Stimato</th>

            </div>                                <th>Modifica</th>

        </main>                                <th>Elimina</th>

                                <th>Azione</th>

        <div class="modal-overlay hidden" id="modalOverlay">                            </tr>

            <div class="modal wide-modal">                        </thead>

                <div class="modal-header">                        <tbody>

                    <h3 id="modalTitle">Nuova Proposta di Gita</h3>                            <?php 

                    <button class="close-btn" id="closeModal">&times;</button>                                $query = "SELECT p.* FROM propostagita p JOIN gitaorganizzata g ON p.IDProposta = g.IDProposta WHERE g.IDStato = 2 ORDER BY p.IDProposta DESC";

                </div>                                $result = mysqli_query($conn, $query);

                <div class="modal-body">

                    <form id="formNuovaProposta" class="form-grid" method="POST" action="catalogo.php">                                if (mysqli_num_rows($result) > 0) {

                        <input type="hidden" name="action" id="formAction" value="nuova_proposta">                                    while ($row = mysqli_fetch_assoc($result)) {

                        <input type="hidden" name="idGita" id="formIdGita" value="">                                        echo "<tr>";

                        <div class="form-group">                                        echo "<td>" . htmlspecialchars($row['Destinazione']) . "</td>";

                            <label for="destinazione">Destinazione / Descrizione</label>                                        echo "<td>" . htmlspecialchars($row['MezzoDiTrasporto']) . "</td>";

                            <input type="text" id="destinazione" name="destinazione" placeholder="es. Parigi - Museo del Louvre" required>                                        echo "<td>" . htmlspecialchars($row['Periodo']) . "</td>";

                        </div>                                        echo "<td>" . htmlspecialchars($row['MinPartecipanti']) . "</td>";

                        <div class="form-group">                                        echo "<td>" . htmlspecialchars($row['MaxPartecipanti']) . "</td>";

                            <label for="costo">Costo Stimato a testa (&euro;)</label>                                        echo "<td>€ " . number_format($row['Costo'], 2, ',', '.') . "</td>";

                            <input type="number" step="0.01" id="costo" name="costo" placeholder="0" required>                                        echo "<td><button class='xs outline btn-modifica' data-id='".$row['IDProposta']."'>Modifica</button></td>";

                        </div>                                        echo "<td><button class='xs cancel btn-elimina' data-id='".$row['IDProposta']."'>Elimina</button></td>";

                    </form>                                        echo "<td><button class='xs btn-organizza' data-id='".$row['IDProposta']."' data-dest='".htmlspecialchars($row['Destinazione'])."'>Organizza</button></td>";

                </div>                                        echo "</tr>";

                <div class="modal-footer">                                    }

                    <button class="button cancel" id="cancelModal">Annulla</button>                                } else {

                    <button class="button" type="submit" form="formNuovaProposta" id="submitModalBtn">Registra Proposta</button>                                    echo "<tr><td colspan='9' style='text-align:center;'>Nessuna proposta presente.</td></tr>";

                </div>                                }

            </div>                            ?>

        </div>                        </tbody>

                    </table>

        <div class="modal-overlay hidden" id="modalDeleteOverlay">                </div>

            <div class="modal">            </div>

                <div class="modal-header">        </main>

                    <h3>Conferma Eliminazione</h3>

                    <button class="close-btn" id="closeDeleteModal">&times;</button>        <div class="modal-overlay hidden" id="modalOverlay">

                </div>            <div class="modal wide-modal">

                <div class="modal-body">                <div class="modal-header">

                    <p style="margin-bottom:20px;">Sei sicuro di voler eliminare questa proposta? L'operazione non pu&ograve; essere annullata.</p>                    <h3 id="modalTitle">Nuova Proposta di Gita</h3>

                </div>                    <button class="close-btn" id="closeModal">&times;</button>

                <div class="modal-footer">                </div>

                    <form id="formEliminaProposta" method="POST" action="catalogo.php" style="display:flex;gap:10px;width:100%;justify-content:flex-end;margin:0;">                <div class="modal-body">

                        <input type="hidden" name="action" value="elimina_proposta">                    <form id="formNuovaProposta" class="form-grid" method="POST" action="catalogo.php">

                        <input type="hidden" name="idGita" id="formDeleteIdGita" value="">                        <input type="hidden" name="action" id="formAction" value="nuova_proposta">

                        <button type="button" class="button outline" id="cancelDeleteModal">Annulla</button>                        <input type="hidden" name="idProposta" id="formIdProposta" value="">

                        <button type="submit" class="button cancel">S&igrave;, Elimina</button>                        <div class="form-group">

                    </form>                            <label for="destinazione">Destinazione</label>

                </div>                            <input type="text" id="destinazione" name="destinazione" placeholder="es. Parigi" required>

            </div>                        </div>

        </div>                        

                        <div class="form-group">

        <div class="modal-overlay hidden" id="modalOrganizza">                            <label for="mezzo">Mezzo di Trasporto</label>

            <div class="modal wide-modal">                            <select id="mezzo" name="mezzo">

                <div class="modal-header">                                <option value="Autobus">Autobus GT</option>

                    <h3 id="titoloOrganizza">Organizza Gita</h3>                                <option value="Treno">Treno Alta Velocità</option>

                    <button class="close-btn" id="chiudiOrganizza">&times;</button>                                <option value="Aereo">Aereo</option>

                </div>                                <option value="Nave">Nave / Traghetto</option>

                <div class="modal-body">                            </select>

                    <form id="formOrganizza" class="form-grid" method="POST" action="catalogo.php">                        </div>

                        <input type="hidden" name="action" value="organizza_gita">

                        <input type="hidden" name="idGita" id="organizzaIdGita" value="">                        <div class="form-group">

                        <div class="form-group">                            <label for="periodo">Periodo Ideale</label>

                            <label for="dataInizio">Data Inizio</label>                            <input type="text" id="periodo" name="periodo" placeholder="es. Aprile 2026" required>

                            <input type="date" id="dataInizio" name="dataInizio" required>                        </div>

                        </div>

                        <div class="form-group">                        <div class="form-group">

                            <label for="dataFine">Data Fine</label>                            <label for="costo">Costo Stimato (€)</label>

                            <input type="date" id="dataFine" name="dataFine" required>                            <input type="number" step="0.01" id="costo" name="costo" placeholder="0" required>

                        </div>                        </div>

                        <div class="form-group">

                            <label for="orarioPartenza">Orario Partenza</label>                        <div class="form-group">

                            <input type="time" id="orarioPartenza" name="orarioPartenza">                            <label for="minPart">Minimo Partecipanti</label>

                        </div>                            <input type="number" id="minPart" name="minPart" placeholder="es. 15" required>

                        <div class="form-group">                        </div>

                            <label for="orarioArrivo">Orario Arrivo</label>

                            <input type="time" id="orarioArrivo" name="orarioArrivo">                        <div class="form-group">

                        </div>                            <label for="maxPart">Massimo Partecipanti</label>

                        <div class="form-group">                            <input type="number" id="maxPart" name="maxPart" placeholder="es. 30" required>

                            <label for="alunni">Numero Alunni</label>                        </div>

                            <input type="number" id="alunni" name="alunni" placeholder="es. 30" required min="0">

                        </div>                    </form>

                        <div class="form-group">                </div>

                            <label for="alunniDisabili">di cui Disabili</label>                <div class="modal-footer">

                            <input type="number" id="alunniDisabili" name="alunniDisabili" min="0" value="0">                    <button class="button cancel" id="cancelModal">Annulla</button>

                        </div>                    <button class="button" type="submit" form="formNuovaProposta" id="submitModalBtn">Registra Proposta</button>

                        <div class="form-group">                </div>

                            <label for="docenti">Numero Docenti</label>            </div>

                            <input type="number" id="docenti" name="docenti" placeholder="es. 3" required min="0">        </div>

                        </div>

                        <div class="form-group">        <div class="modal-overlay hidden" id="modalDeleteOverlay">

                            <label for="classi">Classi Partecipanti</label>            <div class="modal">

                            <input type="text" id="classi" name="classi" placeholder="es. 5A, 5B">                <div class="modal-header">

                        </div>                    <h3>Conferma Eliminazione</h3>

                        <div class="form-group">                    <button class="close-btn" id="closeDeleteModal">&times;</button>

                            <label for="costoMezzi">Costo Mezzi (&euro;)</label>                </div>

                            <input type="number" step="0.01" id="costoMezzi" name="costoMezzi" min="0" value="0">                <div class="modal-body">

                        </div>                    <p style="margin-bottom: 20px;">Sei sicuro di voler eliminare questa proposta? L'operazione non può essere annullata.</p>

                        <div class="form-group">                </div>

                            <label for="costoAttivita">Costo Attivit&agrave; (&euro;)</label>                <div class="modal-footer">

                            <input type="number" step="0.01" id="costoAttivita" name="costoAttivita" min="0" value="0">                    <form id="formEliminaProposta" method="POST" action="catalogo.php" style="display: flex; gap: 10px; width: 100%; justify-content: flex-end; margin: 0;">

                        </div>                        <input type="hidden" name="action" value="elimina_proposta">

                    </form>                        <input type="hidden" name="idProposta" id="formDeleteIdProposta" value="">

                </div>                        <button type="button" class="button outline" id="cancelDeleteModal">Annulla</button>

                <div class="modal-footer">                        <button type="submit" class="button cancel">Sì, Elimina</button>

                    <button class="button cancel" id="annullaOrganizza">Annulla</button>                    </form>

                    <button class="button" type="submit" form="formOrganizza">Conferma</button>                </div>

                </div>            </div>

            </div>        </div>

        </div>

        <footer>

        <footer>            <div class="footer-container">

            <div class="footer-container">                <div class="footer-left">

                <div class="footer-left">                    <p><strong>Gestione Gite Scolastiche</strong></p>

                    <p><strong>Gestione Gite Scolastiche</strong></p>                </div>

                </div>            </div>

            </div>        </footer>

        </footer>    </div>

    </div>

        <div class="modal-overlay hidden" id="modalOrganizza">

    <script>            <div class="modal wide-modal">

        var modale = document.getElementById('modalOverlay');                <div class="modal-header">

        var btnApri = document.getElementById('btnNuova');                    <h3 id="titoloOrganizza">Organizza Gita</h3>

        var btnChiudi = document.getElementById('closeModal');                    <button class="close-btn" id="chiudiOrganizza">&times;</button>

        var btnAnnulla = document.getElementById('cancelModal');                </div>

        var modulo = document.getElementById('formNuovaProposta');                <div class="modal-body">

        var titolo = document.getElementById('modalTitle');                    <form id="formOrganizza" class="form-grid" method="POST" action="catalogo.php">

        var btnInvia = document.getElementById('submitModalBtn');                        <input type="hidden" name="action" value="organizza_gita">

        var formAction = document.getElementById('formAction');                        <input type="hidden" name="idProposta" id="organizzaIdProposta" value="">

        var formIdGita = document.getElementById('formIdGita');                        <div class="form-group">

                            <label for="dataInizio">Data Inizio</label>

        btnApri.addEventListener('click', function() {                            <input type="date" id="dataInizio" name="dataInizio" required>

            modulo.reset();                        </div>

            formAction.value = 'nuova_proposta';                        <div class="form-group">

            formIdGita.value = '';                            <label for="dataFine">Data Fine</label>

            titolo.innerText = 'Nuova Proposta di Gita';                            <input type="date" id="dataFine" name="dataFine" required>

            btnInvia.innerText = 'Registra Proposta';                        </div>

            modale.classList.remove('hidden');                        <div class="form-group">

        });                            <label for="orarioPartenza">Orario Partenza</label>

                            <input type="time" id="orarioPartenza" name="orarioPartenza">

        var listaModifica = document.querySelectorAll('.btn-modifica');                        </div>

        for (var i = 0; i < listaModifica.length; i++) {                        <div class="form-group">

            listaModifica[i].addEventListener('click', function() {                            <label for="orarioArrivo">Orario Arrivo</label>

                modulo.reset();                            <input type="time" id="orarioArrivo" name="orarioArrivo">

                document.getElementById('destinazione').value = this.getAttribute('data-dest');                        </div>

                document.getElementById('costo').value = this.getAttribute('data-costo');                        <div class="form-group">

                formAction.value = 'modifica_proposta';                            <label for="alunni">Numero Alunni</label>

                formIdGita.value = this.getAttribute('data-id');                            <input type="number" id="alunni" name="alunni" placeholder="es. 30" required min="0">

                titolo.innerText = 'Modifica Proposta';                        </div>

                btnInvia.innerText = 'Salva Modifiche';                        <div class="form-group">

                modale.classList.remove('hidden');                            <label for="alunniDisabili">di cui Disabili</label>

            });                            <input type="number" id="alunniDisabili" name="alunniDisabili" placeholder="es. 2" min="0" value="0">

        }                        </div>

                        <div class="form-group">

        function chiudiModale() { modale.classList.add('hidden'); }                            <label for="docenti">Numero Docenti</label>

        btnChiudi.addEventListener('click', chiudiModale);                            <input type="number" id="docenti" name="docenti" placeholder="es. 3" required min="0">

        btnAnnulla.addEventListener('click', chiudiModale);                        </div>

                        <div class="form-group">

        var modaleDelete = document.getElementById('modalDeleteOverlay');                            <label for="classi">Classi Partecipanti</label>

        var btnChiudiDelete = document.getElementById('closeDeleteModal');                            <input type="text" id="classi" name="classi" placeholder="es. 5A, 5B, 4A">

        var btnAnnullaDelete = document.getElementById('cancelDeleteModal');                        </div>

                        <div class="form-group">

        var listaElimina = document.querySelectorAll('.btn-elimina');                            <label for="costoMezzi">Costo Mezzi (&euro;)</label>

        for (var k = 0; k < listaElimina.length; k++) {                            <input type="number" step="0.01" id="costoMezzi" name="costoMezzi" placeholder="0" min="0" value="0">

            listaElimina[k].addEventListener('click', function() {                        </div>

                document.getElementById('formDeleteIdGita').value = this.getAttribute('data-id');                        <div class="form-group">

                modaleDelete.classList.remove('hidden');                            <label for="costoAttivita">Costo Attivit&agrave; (&euro;)</label>

            });                            <input type="number" step="0.01" id="costoAttivita" name="costoAttivita" placeholder="0" min="0" value="0">

        }                        </div>

                    </form>

        function chiudiModaleDelete() { modaleDelete.classList.add('hidden'); }                </div>

        btnChiudiDelete.addEventListener('click', chiudiModaleDelete);                <div class="modal-footer">

        btnAnnullaDelete.addEventListener('click', chiudiModaleDelete);                    <button class="button cancel" id="annullaOrganizza">Annulla</button>

                    <button class="button" type="submit" form="formOrganizza">Conferma</button>

        var modaleOrganizza = document.getElementById('modalOrganizza');                </div>

            </div>

        var listaOrganizza = document.querySelectorAll('.btn-organizza');        </div>

        for (var z = 0; z < listaOrganizza.length; z++) {

            listaOrganizza[z].addEventListener('click', function() {    <script>

                document.getElementById('formOrganizza').reset();        var modale = document.getElementById('modalOverlay');

                document.getElementById('organizzaIdGita').value = this.getAttribute('data-id');        var btnApri = document.getElementById('btnNuova');

                document.getElementById('titoloOrganizza').innerText = 'Organizza: ' + this.getAttribute('data-dest');        var btnChiudi = document.getElementById('closeModal');

                modaleOrganizza.classList.remove('hidden');        var btnAnnulla = document.getElementById('cancelModal');

            });        var modulo = document.getElementById('formNuovaProposta');

        }        var titolo = document.getElementById('modalTitle');

        var btnInvia = document.getElementById('submitModalBtn');

        function chiudiOrganizza() { modaleOrganizza.classList.add('hidden'); }        var formAction = document.getElementById('formAction');

        document.getElementById('chiudiOrganizza').addEventListener('click', chiudiOrganizza);        var formIdProposta = document.getElementById('formIdProposta');

        document.getElementById('annullaOrganizza').addEventListener('click', chiudiOrganizza);

        btnApri.addEventListener('click', function() {

        window.addEventListener('click', function(e) {            modulo.reset();

            if (e.target === modale) chiudiModale();            formAction.value = 'nuova_proposta';

            if (e.target === modaleDelete) chiudiModaleDelete();            formIdProposta.value = '';

            if (e.target === modaleOrganizza) chiudiOrganizza();            titolo.innerText = "Nuova Proposta di Gita";

        });            btnInvia.innerText = "Registra Proposta";

    </script>            modale.classList.remove('hidden');

        });

    <?php if ($mostraSuccesso) { ?>

    <div class="modal-overlay modal-successo" id="modalSuccessOverlay">        var listaModifica = document.querySelectorAll('.btn-modifica');

        <div class="modal">        for (var i = 0; i < listaModifica.length; i++) {

            <div class="modal-header">            listaModifica[i].addEventListener('click', function() {

                <h3>Nuova Proposta</h3>                var riga = this.closest('tr');

                <button class="close-btn" onclick="document.getElementById('modalSuccessOverlay').style.display='none'">&times;</button>                var celle = riga.querySelectorAll('td');

            </div>

            <div class="modal-body">                document.getElementById('destinazione').value = celle[0].innerText;

                <p>Proposta creata con successo! Attende l'approvazione dalla commissione.</p>                document.getElementById('periodo').value = celle[2].innerText;

            </div>                document.getElementById('minPart').value = celle[3].innerText;

            <div class="modal-footer">                document.getElementById('maxPart').value = celle[4].innerText;

                <button class="button" onclick="document.getElementById('modalSuccessOverlay').style.display='none'">OK, Chiudi</button>

            </div>                var costoTesto = celle[5].innerText.replace('€', '').trim().replace(',', '.');

        </div>                document.getElementById('costo').value = Number(costoTesto);

    </div>

    <?php } ?>                var selectMezzo = document.getElementById('mezzo');

</body>                var valMezzo = celle[1].innerText;

</html>                for (var j = 0; j < selectMezzo.options.length; j++) {

                    if (selectMezzo.options[j].text.indexOf(valMezzo) >= 0 || selectMezzo.options[j].value === valMezzo) {
                        selectMezzo.selectedIndex = j;
                        break;
                    }
                }

                formAction.value = 'modifica_proposta';
                formIdProposta.value = this.getAttribute('data-id');
                titolo.innerText = "Modifica Proposta di Gita";
                btnInvia.innerText = "Salva Modifiche";
                modale.classList.remove('hidden');
            });
        }

        function chiudiModale() {
            modale.classList.add('hidden');
        }

        btnChiudi.addEventListener('click', chiudiModale);
        btnAnnulla.addEventListener('click', chiudiModale);

        var modaleDelete = document.getElementById('modalDeleteOverlay');
        var btnChiudiDelete = document.getElementById('closeDeleteModal');
        var btnAnnullaDelete = document.getElementById('cancelDeleteModal');
        var formDeleteIdProposta = document.getElementById('formDeleteIdProposta');

        var listaElimina = document.querySelectorAll('.btn-elimina');
        for (var k = 0; k < listaElimina.length; k++) {
            listaElimina[k].addEventListener('click', function() {
                formDeleteIdProposta.value = this.getAttribute('data-id');
                modaleDelete.classList.remove('hidden');
            });
        }

        function chiudiModaleDelete() {
            modaleDelete.classList.add('hidden');
        }

        btnChiudiDelete.addEventListener('click', chiudiModaleDelete);
        btnAnnullaDelete.addEventListener('click', chiudiModaleDelete);

        window.addEventListener('click', function(e) {
            if (e.target === modale) chiudiModale();
            if (e.target === modaleDelete) chiudiModaleDelete();
            if (e.target === modaleOrganizza) chiudiOrganizza();
        });

        var modaleOrganizza = document.getElementById('modalOrganizza');
        var organizzaIdProposta = document.getElementById('organizzaIdProposta');
        var titoloOrganizza = document.getElementById('titoloOrganizza');

        var listaOrganizza = document.querySelectorAll('.btn-organizza');
        for (var z = 0; z < listaOrganizza.length; z++) {
            listaOrganizza[z].addEventListener('click', function() {
                organizzaIdProposta.value = this.getAttribute('data-id');
                titoloOrganizza.innerText = 'Organizza: ' + this.getAttribute('data-dest');
                document.getElementById('formOrganizza').reset();
                organizzaIdProposta.value = this.getAttribute('data-id');
                modaleOrganizza.classList.remove('hidden');
            });
        }

        function chiudiOrganizza() {
            modaleOrganizza.classList.add('hidden');
        }

        document.getElementById('chiudiOrganizza').addEventListener('click', chiudiOrganizza);
        document.getElementById('annullaOrganizza').addEventListener('click', chiudiOrganizza);
    </script>
    
    <?php if (isset($mostraSuccesso) && $mostraSuccesso) { ?>
    <div class="modal-overlay modal-successo" id="modalSuccessOverlay">
        <div class="modal">
            <div class="modal-header">
                <h3>Nuova Proposta</h3>
                <button class="close-btn" onclick="document.getElementById('modalSuccessOverlay').style.display='none'">&times;</button>
            </div>
            <div class="modal-body">
                <p>Proposta creata con successo! Si aspetta l'approvazione dalla commissione.</p>
            </div>
            <div class="modal-footer">
                <button class="button" onclick="document.getElementById('modalSuccessOverlay').style.display='none'">OK, Chiudi</button>
            </div>
        </div>
    </div>
    <?php } ?>
</body>
</html>