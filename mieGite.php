<?php<!DOCTYPE html>

include('nav.php');<html lang="it">

$idUtenteLoggato = $_SESSION['id_utente'];<head>

$messaggio = "";    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {    <title>Gestione Gite - Le Mie Gite</title>

    if ($_POST['action'] == 'modifica_gita_organizzata') {    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        $idGita = intval($_POST['idGita'] ?? 0);    <link rel="stylesheet" href="style.css">

        $dataInizio = $_POST['dataInizio'] ?? '';    <link rel="stylesheet" href="vetrina.css">

        $dataFine = $_POST['dataFine'] ?? '';    <link rel="stylesheet" href="style_custom.css">

        $alunni = intval($_POST['alunni'] ?? 0);    <script src="vetrina.js" defer></script>

        $alunniDisabili = intval($_POST['alunniDisabili'] ?? 0);</head>

        $docenti = intval($_POST['docenti'] ?? 0);<body>

        $classi = $_POST['classi'] ?? '';    <?php 

        $orarioPartenza = $_POST['orarioPartenza'] ?: null;        include('nav.php'); 

        $orarioArrivo = $_POST['orarioArrivo'] ?: null;        $idUtenteLoggato = $_SESSION['id_utente'];

        $costoMezzi = floatval($_POST['costoMezzi'] ?? 0);        $messaggio = "";

        $costoAttivita = floatval($_POST['costoAttivita'] ?? 0);

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

        $queryCosto = "SELECT CostoTot FROM gitaorganizzata WHERE IDGita = $idGita";            if ($_POST['action'] == 'modifica_gita_organizzata') {

        $resCosto = mysqli_query($conn, $queryCosto);                $idGita = intval($_POST['idGita'] ?? 0);

        $rigaCosto = mysqli_fetch_assoc($resCosto);                $dataInizio = $_POST['dataInizio'] ?? '';

        $costoTotale = ($rigaCosto['CostoTot'] * $alunni) + $costoMezzi + $costoAttivita;                $dataFine = $_POST['dataFine'] ?? '';

                $alunni = intval($_POST['alunni'] ?? 0);

        $istr = mysqli_prepare($conn, "UPDATE gitaorganizzata SET DataInizio=?, DataFine=?, NumAlunni=?, NumDocentiAccompagnatori=?, NumAlunniDisabili=?, ClassiPartecipanti=?, OrarioPartenza=?, OrarioArrivo=?, CostoMezzi=?, CostoAttivita=?, CostoTot=? WHERE IDGita=? AND IDUtente=?");                $alunniDisabili = intval($_POST['alunniDisabili'] ?? 0);

        mysqli_stmt_bind_param($istr, "ssiiisssdddii", $dataInizio, $dataFine, $alunni, $docenti, $alunniDisabili, $classi, $orarioPartenza, $orarioArrivo, $costoMezzi, $costoAttivita, $costoTotale, $idGita, $idUtenteLoggato);                $docenti = intval($_POST['docenti'] ?? 0);

        if (mysqli_stmt_execute($istr)) {                $classi = $_POST['classi'] ?? '';

            $messaggio = "<div style='background-color:#d4edda;color:#155724;padding:10px;margin-bottom:20px;border-radius:5px;'>Gita modificata con successo.</div>";                $orarioPartenza = $_POST['orarioPartenza'] ?: null;

        } else {                $orarioArrivo = $_POST['orarioArrivo'] ?: null;

            $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore durante la modifica.</div>";                $costoMezzi = floatval($_POST['costoMezzi'] ?? 0);

        }                $costoAttivita = floatval($_POST['costoAttivita'] ?? 0);

        mysqli_stmt_close($istr);

    }                $queryPropCosto = "SELECT p.Costo FROM propostagita p JOIN gitaorganizzata g ON p.IDProposta = g.IDProposta WHERE g.IDGita = $idGita";

}                $resCosto = mysqli_query($conn, $queryPropCosto);

?>                $rigaCosto = mysqli_fetch_assoc($resCosto);

<!DOCTYPE html>                $costoTotale = ($rigaCosto['Costo'] * $alunni) + $costoMezzi + $costoAttivita;

<html lang="it">

<head>                $istr = mysqli_prepare($conn, "UPDATE gitaorganizzata SET DataInizio=?, DataFine=?, NumAlunni=?, NumDocentiAccompagnatori=?, NumAlunniDisabili=?, ClassiPartecipanti=?, OrarioPartenza=?, OrarioArrivo=?, CostoMezzi=?, CostoAttivita=?, CostoTot=? WHERE IDGita=? AND IDUtente=?");

    <meta charset="UTF-8">                mysqli_stmt_bind_param($istr, "ssiiisssdddii", $dataInizio, $dataFine, $alunni, $docenti, $alunniDisabili, $classi, $orarioPartenza, $orarioArrivo, $costoMezzi, $costoAttivita, $costoTotale, $idGita, $idUtenteLoggato);

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestione Gite - Le Mie Gite</title>                if (mysqli_stmt_execute($istr)) {

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">                    $messaggio = "<div style='background-color:#d4edda;color:#155724;padding:10px;margin-bottom:20px;border-radius:5px;'>Gita modificata con successo.</div>";

    <link rel="stylesheet" href="style.css">                } else {

    <link rel="stylesheet" href="vetrina.css">                    $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore durante la modifica.</div>";

    <link rel="stylesheet" href="style_custom.css">                }

    <script src="vetrina.js" defer></script>                mysqli_stmt_close($istr);

</head>            }

<body>        }

    <div class="container">    ?>

        <main class="content home-padding">

    <div class="container">

            <div class="hero-section">        <main class="content home-padding">

                <h2 style="margin-bottom:1rem; color:var(--blue-700);">Le Mie Gite</h2>

                <p>Tutte le gite che hai creato o organizzato. Controlla lo stato e modifica i dettagli.</p>            <div class="hero-section">

            </div>                <h2 style="margin-bottom: 1rem; color: var(--blue-700);">Le Mie Gite</h2>

                <p>Tutte le gite che hai creato o a cui partecipi.

            <?php echo $messaggio; ?>                Controlla lo stato, modifica i dettagli o elimina quelle non più necessarie.</p>

            </div>

            <div style="margin-top:2rem;">

                <h3 style="margin-bottom:1rem; color:var(--blue-700);">Gite create da me (bozze)</h3>            <?php echo $messaggio; ?>

                <div class="miegite-grid">

                    <?php             <div style="margin-top: 2rem;">

                        $query = "SELECT g.*, s.Stato FROM gitaorganizzata g JOIN statogita s ON g.IDStato = s.IDStato WHERE g.IDUtente = $idUtenteLoggato AND g.IDStato IN (1, 2, 3) ORDER BY g.IDGita DESC";                <h2 style="margin-bottom: 1rem; color: var(--blue-700);">Gite create da me</h2>

                        $risultatoMie = mysqli_query($conn, $query);                <div class="miegite-grid">

                    <?php 

                        if (mysqli_num_rows($risultatoMie) > 0) {                        $query = "

                            while ($row = mysqli_fetch_assoc($risultatoMie)) {                            SELECT g.*, p.Destinazione, s.Stato 

                                $classeBadge = 'badge-secondary';                            FROM gitaorganizzata g 

                                if ($row['Stato'] == 'Approvata') $classeBadge = 'badge-success';                            JOIN propostagita p ON g.IDProposta = p.IDProposta 

                                if ($row['Stato'] == 'Bocciata') $classeBadge = 'badge-danger';                            JOIN statogita s ON g.IDStato = s.IDStato 

                                if ($row['Stato'] == 'Bozza') $classeBadge = 'badge-warning';                            WHERE g.IDUtente = $idUtenteLoggato

                                $dest = htmlspecialchars($row['ClassiPartecipanti'] ?? 'N/D');                            ORDER BY g.DataInizio ASC

                                $costo = number_format($row['CostoTot'], 2, ',', '.');                        ";

                                ?>                        $risultatoMie = mysqli_query($conn, $query);

                                <div class="miegite-card"

                                    data-id="<?php echo $row['IDGita']; ?>"                        if (mysqli_num_rows($risultatoMie) > 0) {

                                    data-dest="<?php echo $dest; ?>"                            while ($row = mysqli_fetch_assoc($risultatoMie)) {

                                    data-stato="<?php echo htmlspecialchars($row['Stato']); ?>"                                $dataInizio = date('d/m/Y', strtotime($row['DataInizio']));

                                    data-costo="<?php echo $costo; ?>">                                $dataFine = date('d/m/Y', strtotime($row['DataFine']));

                                    <div class="miegite-card-header">                                

                                        <h3 class="miegite-card-title"><?php echo $dest; ?></h3>                                $classeBadge = 'badge-secondary';

                                        <span class="badge <?php echo $classeBadge; ?>"><?php echo htmlspecialchars($row['Stato']); ?></span>                                if ($row['Stato'] == 'Approvata') $classeBadge = 'badge-success';

                                    </div>                                if ($row['Stato'] == 'Inserita') $classeBadge = 'badge-warning';

                                    <div class="miegite-card-body">                                if ($row['Stato'] == 'Conclusa') $classeBadge = 'badge-primary';

                                        <div class="miegite-card-info">                                if ($row['Stato'] == 'NonApprovata') $classeBadge = 'badge-danger';

                                            <span><strong>Costo stimato:</strong> &euro; <?php echo $costo; ?></span>                                ?>

                                        </div>                                <div class="miegite-card" <?php 

                                    </div>                                     echo 'data-dest="' . htmlspecialchars($row['Destinazione']) . '" ';

                                    <div class="miegite-card-footer">                                     echo 'data-stato="' . htmlspecialchars($row['Stato']) . '" ';

                                        <button class="xs outline btn-dettagli-gita">Dettagli</button>                                     echo 'data-classe="N/D" ';

                                    </div>                                     echo 'data-inizio="' . $dataInizio . '" ';

                                </div>                                     echo 'data-fine="' . $dataFine . '" ';

                                <?php                                     echo 'data-mezzo="" ';

                            }                                     echo 'data-alunni="' . $row['NumAlunni'] . '" ';

                        } else {                                     echo 'data-docenti="' . $row['NumDocentiAccompagnatori'] . '" ';

                            echo "<p>Non hai ancora creato nessuna proposta.</p>";                                     echo 'data-costo="' . number_format($row['CostoTot'], 2, ',', '.') . '" ';

                        }                                     echo 'data-note=""';

                    ?>                                ?>>

                </div>                                    <div class="miegite-card-header">

            </div>                                        <h3 class="miegite-card-title"><?php echo htmlspecialchars($row['Destinazione']); ?></h3>

                                        <span class="badge <?php echo $classeBadge; ?>"><?php echo htmlspecialchars($row['Stato']); ?></span>

            <div style="margin-top:3rem;">                                    </div>

                <h3 style="margin-bottom:1rem; color:var(--blue-700);">Gite che ho organizzato</h3>                                    <div class="miegite-card-body">

                <div class="miegite-grid">                                        <div class="miegite-card-info">

                    <?php                                            <span><strong>Date:</strong> <?php echo $dataInizio . ' &#8211; ' . $dataFine; ?></span>

                        $query = "SELECT g.*, s.Stato FROM gitaorganizzata g JOIN statogita s ON g.IDStato = s.IDStato WHERE g.IDUtente = $idUtenteLoggato AND g.IDStato IN (4, 5) ORDER BY g.DataInizio ASC";                                            <span><strong>Partecipanti:</strong> <?php echo $row['NumAlunni'] . ' alunni, ' . $row['NumDocentiAccompagnatori'] . ' docenti'; ?></span>

                        $risultatoPartecipo = mysqli_query($conn, $query);                                            <span><strong>Costo:</strong> &#8364; <?php echo number_format($row['CostoTot'], 2, ',', '.'); ?></span>

                                        </div>

                        if (mysqli_num_rows($risultatoPartecipo) > 0) {                                    </div>

                            while ($row = mysqli_fetch_assoc($risultatoPartecipo)) {                                    <div class="miegite-card-footer">

                                $dataInizio = $row['DataInizio'] ? date('d/m/Y', strtotime($row['DataInizio'])) : '-';                                        <button class="xs outline btn-dettagli-gita">Dettagli</button>

                                $dataFine = $row['DataFine'] ? date('d/m/Y', strtotime($row['DataFine'])) : '-';                                        <button class="xs outline btn-modifica-gita">Modifica</button>

                                $dataInizioRaw = $row['DataInizio'] ?? '';                                        <button class="xs cancel btn-elimina-gita">Elimina</button>

                                $dataFineRaw = $row['DataFine'] ?? '';                                    </div>

                                $orPartenza = $row['OrarioPartenza'] ? substr($row['OrarioPartenza'], 0, 5) : '';                                </div>

                                $orArrivo = $row['OrarioArrivo'] ? substr($row['OrarioArrivo'], 0, 5) : '';                                <?php

                                $dest = htmlspecialchars($row['ClassiPartecipanti'] ?? 'N/D');                            }

                                $classeBadge = ($row['Stato'] == 'Conclusa') ? 'badge-primary' : 'badge-success';                        } else {

                                ?>                            echo "<p>Non hai ancora creato nessuna gita.</p>";

                                <div class="miegite-card"                        }

                                    data-id="<?php echo $row['IDGita']; ?>"                    ?>

                                    data-dest="<?php echo $dest; ?>"                </div>

                                    data-stato="<?php echo htmlspecialchars($row['Stato']); ?>"            </div>

                                    data-inizio="<?php echo $dataInizio; ?>"

                                    data-fine="<?php echo $dataFine; ?>"            <div style="margin-top: 3rem;">

                                    data-inizio-raw="<?php echo $dataInizioRaw; ?>"                <h2 style="margin-bottom: 1rem; color: var(--blue-700);">Gite a cui partecipo</h2>

                                    data-fine-raw="<?php echo $dataFineRaw; ?>"                <div class="miegite-grid">

                                    data-alunni="<?php echo $row['NumAlunni']; ?>"                    <?php

                                    data-alunni-disabili="<?php echo $row['NumAlunniDisabili']; ?>"                        $query = "SELECT g.*, p.Destinazione, s.Stato FROM gitaorganizzata g JOIN propostagita p ON g.IDProposta = p.IDProposta JOIN statogita s ON g.IDStato = s.IDStato WHERE g.IDUtente = $idUtenteLoggato AND g.IDStato = 5 ORDER BY g.DataInizio ASC";

                                    data-docenti="<?php echo $row['NumDocentiAccompagnatori']; ?>"                        $risultatoPartecipo = mysqli_query($conn, $query);

                                    data-classi="<?php echo $dest; ?>"

                                    data-or-partenza="<?php echo $orPartenza; ?>"                        if (mysqli_num_rows($risultatoPartecipo) > 0) {

                                    data-or-arrivo="<?php echo $orArrivo; ?>"                            while ($row = mysqli_fetch_assoc($risultatoPartecipo)) {

                                    data-costo-mezzi="<?php echo number_format($row['CostoMezzi'], 2, ',', '.'); ?>"                                $dataInizio = date('d/m/Y', strtotime($row['DataInizio']));

                                    data-costo-att="<?php echo number_format($row['CostoAttivita'], 2, ',', '.'); ?>"                                $dataFine = date('d/m/Y', strtotime($row['DataFine']));

                                    data-costo="<?php echo number_format($row['CostoTot'], 2, ',', '.'); ?>">                                $orPartenza = $row['OrarioPartenza'] ? substr($row['OrarioPartenza'], 0, 5) : '';

                                    <div class="miegite-card-header">                                $orArrivo = $row['OrarioArrivo'] ? substr($row['OrarioArrivo'], 0, 5) : '';

                                        <h3 class="miegite-card-title"><?php echo $dest; ?></h3>                                $dataInizioRaw = $row['DataInizio'];

                                        <span class="badge <?php echo $classeBadge; ?>"><?php echo htmlspecialchars($row['Stato']); ?></span>                                $dataFineRaw = $row['DataFine'];

                                    </div>                                ?>

                                    <div class="miegite-card-body">                                <div class="miegite-card"

                                        <div class="miegite-card-info">                                    data-id="<?php echo $row['IDGita']; ?>"

                                            <span><strong>Date:</strong> <?php echo $dataInizio . ' &ndash; ' . $dataFine; ?></span>                                    data-dest="<?php echo htmlspecialchars($row['Destinazione']); ?>"

                                            <?php if ($row['ClassiPartecipanti']) { ?>                                    data-stato="<?php echo htmlspecialchars($row['Stato']); ?>"

                                            <span><strong>Classi:</strong> <?php echo $dest; ?></span>                                    data-inizio="<?php echo $dataInizio; ?>"

                                            <?php } ?>                                    data-fine="<?php echo $dataFine; ?>"

                                            <span><strong>Partecipanti:</strong> <?php echo $row['NumAlunni'] . ' alunni, ' . $row['NumDocentiAccompagnatori'] . ' docenti'; ?></span>                                    data-inizio-raw="<?php echo $dataInizioRaw; ?>"

                                            <span><strong>Costo:</strong> &euro; <?php echo number_format($row['CostoTot'], 2, ',', '.'); ?></span>                                    data-fine-raw="<?php echo $dataFineRaw; ?>"

                                        </div>                                    data-alunni="<?php echo $row['NumAlunni']; ?>"

                                    </div>                                    data-alunni-disabili="<?php echo $row['NumAlunniDisabili']; ?>"

                                    <div class="miegite-card-footer">                                    data-docenti="<?php echo $row['NumDocentiAccompagnatori']; ?>"

                                        <button class="xs outline btn-dettagli-gita">Dettagli</button>                                    data-classi="<?php echo htmlspecialchars($row['ClassiPartecipanti'] ?? ''); ?>"

                                        <button class="xs outline btn-modifica-partecipo">Modifica</button>                                    data-or-partenza="<?php echo $orPartenza; ?>"

                                        <a href="partecipanti.php?id=<?php echo $row['IDGita']; ?>" class="xs button" style="text-decoration:none;">Partecipanti</a>                                    data-or-arrivo="<?php echo $orArrivo; ?>"

                                    </div>                                    data-costo-mezzi="<?php echo number_format($row['CostoMezzi'], 2, ',', '.'); ?>"

                                </div>                                    data-costo-att="<?php echo number_format($row['CostoAttivita'], 2, ',', '.'); ?>"

                                <?php                                    data-costo="<?php echo number_format($row['CostoTot'], 2, ',', '.'); ?>"

                            }                                    data-mezzo="" data-classe="<?php echo htmlspecialchars($row['ClassiPartecipanti'] ?? ''); ?>" data-note="">

                        } else {                                    <div class="miegite-card-header">

                            echo "<p>Nessuna gita organizzata al momento.</p>";                                        <h3 class="miegite-card-title"><?php echo htmlspecialchars($row['Destinazione']); ?></h3>

                        }                                        <span class="badge badge-primary"><?php echo htmlspecialchars($row['Stato']); ?></span>

                    ?>                                    </div>

                </div>                                    <div class="miegite-card-body">

            </div>                                        <div class="miegite-card-info">

                                            <span><strong>Date:</strong> <?php echo $dataInizio . ' &#8211; ' . $dataFine; ?></span>

        </main>                                            <?php if ($row['ClassiPartecipanti']) { ?>

                                            <span><strong>Classi:</strong> <?php echo htmlspecialchars($row['ClassiPartecipanti']); ?></span>

        <div class="modal-overlay hidden" id="modalDettagli">                                            <?php } ?>

            <div class="modal wide-modal">                                            <span><strong>Partecipanti:</strong> <?php echo $row['NumAlunni'] . ' alunni, ' . $row['NumDocentiAccompagnatori'] . ' docenti'; ?></span>

                <div class="modal-header">                                            <span><strong>Costo:</strong> &#8364; <?php echo number_format($row['CostoTot'], 2, ',', '.'); ?></span>

                    <h3 id="titoloDettagli">Dettagli Gita</h3>                                        </div>

                    <button class="close-btn" onclick="closeModal('modalDettagli')">&times;</button>                                    </div>

                </div>                                    <div class="miegite-card-footer">

                <div class="modal-body">                                        <button class="xs outline btn-dettagli-gita">Dettagli</button>

                    <div class="form-grid" style="pointer-events:none;">                                        <button class="xs outline btn-modifica-partecipo">Modifica</button>

                        <div class="form-group">                                        <a href="partecipanti.php?id=<?php echo $row['IDGita']; ?>" class="xs button" style="text-decoration:none;">Partecipanti</a>

                            <label>Destinazione</label>                                    </div>

                            <input type="text" id="detDest" readonly>                                </div>

                        </div>                                <?php

                        <div class="form-group">                            }

                            <label>Stato</label>                        } else {

                            <input type="text" id="detStato" readonly>                            echo "<p>Nessuna gita organizzata al momento.</p>";

                        </div>                        }

                        <div class="form-group">                    ?>

                            <label>Costo</label>                </div>

                            <input type="text" id="detCosto" readonly>            </div>

                        </div>

                    </div>        </main>

                </div>

                <div class="modal-footer">        <div class="modal-overlay hidden" id="modalDettagli">

                    <button class="button cancel" onclick="closeModal('modalDettagli')">Chiudi</button>            <div class="modal wide-modal">

                </div>                <div class="modal-header">

            </div>                    <h3 id="titoloDettagli">Dettagli Gita</h3>

        </div>                    <button class="close-btn" onclick="closeModal('modalDettagli')">&times;</button>

                </div>

        <div class="modal-overlay hidden" id="modalModificaPartecipo">                <div class="modal-body">

            <div class="modal wide-modal">                    <div class="form-grid" style="pointer-events:none;">

                <div class="modal-header">                        <div class="form-group">

                    <h3 id="titoloModificaPartecipo">Modifica Gita</h3>                            <label>Destinazione</label>

                    <button class="close-btn" id="chiudiModificaPartecipo">&times;</button>                            <input type="text" id="detDest" readonly>

                </div>                        </div>

                <div class="modal-body">                        <div class="form-group">

                    <form id="formModificaPartecipo" class="form-grid" method="POST" action="mieGite.php">                            <label>Stato</label>

                        <input type="hidden" name="action" value="modifica_gita_organizzata">                            <input type="text" id="detStato" readonly>

                        <input type="hidden" name="idGita" id="modPartIdGita" value="">                        </div>

                        <div class="form-group">                        <div class="form-group">

                            <label for="modPartInizio">Data Inizio</label>                            <label>Classe</label>

                            <input type="date" id="modPartInizio" name="dataInizio" required>                            <input type="text" id="detClasse" readonly>

                        </div>                        </div>

                        <div class="form-group">                        <div class="form-group">

                            <label for="modPartFine">Data Fine</label>                            <label>Mezzo di Trasporto</label>

                            <input type="date" id="modPartFine" name="dataFine" required>                            <input type="text" id="detMezzo" readonly>

                        </div>                        </div>

                        <div class="form-group">                        <div class="form-group">

                            <label for="modPartOrPart">Orario Partenza</label>                            <label>Data Inizio</label>

                            <input type="time" id="modPartOrPart" name="orarioPartenza">                            <input type="text" id="detInizio" readonly>

                        </div>                        </div>

                        <div class="form-group">                        <div class="form-group">

                            <label for="modPartOrArr">Orario Arrivo</label>                            <label>Data Fine</label>

                            <input type="time" id="modPartOrArr" name="orarioArrivo">                            <input type="text" id="detFine" readonly>

                        </div>                        </div>

                        <div class="form-group">                        <div class="form-group">

                            <label for="modPartAlunni">Numero Alunni</label>                            <label>Alunni</label>

                            <input type="number" id="modPartAlunni" name="alunni" min="0" required>                            <input type="text" id="detAlunni" readonly>

                        </div>                        </div>

                        <div class="form-group">                        <div class="form-group">

                            <label for="modPartDisabili">di cui Disabili</label>                            <label>Docenti</label>

                            <input type="number" id="modPartDisabili" name="alunniDisabili" min="0" value="0">                            <input type="text" id="detDocenti" readonly>

                        </div>                        </div>

                        <div class="form-group">                        <div class="form-group">

                            <label for="modPartDocenti">Numero Docenti</label>                            <label>Costo Totale</label>

                            <input type="number" id="modPartDocenti" name="docenti" min="0" required>                            <input type="text" id="detCosto" readonly>

                        </div>                        </div>

                        <div class="form-group">                        <div class="form-group">

                            <label for="modPartClassi">Classi Partecipanti</label>                            <label>Note</label>

                            <input type="text" id="modPartClassi" name="classi" placeholder="es. 5A, 5B">                            <input type="text" id="detNote" readonly>

                        </div>                        </div>

                        <div class="form-group">                    </div>

                            <label for="modPartCostoMezzi">Costo Mezzi (&euro;)</label>                </div>

                            <input type="number" step="0.01" id="modPartCostoMezzi" name="costoMezzi" min="0" value="0">                <div class="modal-footer">

                        </div>                    <button class="button cancel" onclick="closeModal('modalDettagli')">Chiudi</button>

                        <div class="form-group">                </div>

                            <label for="modPartCostoAtt">Costo Attivit&agrave; (&euro;)</label>            </div>

                            <input type="number" step="0.01" id="modPartCostoAtt" name="costoAttivita" min="0" value="0">        </div>

                        </div>

                    </form>        <div class="modal-overlay hidden" id="modalModifica">

                </div>            <div class="modal wide-modal">

                <div class="modal-footer">                <div class="modal-header">

                    <button class="button cancel" id="annullaModificaPartecipo">Annulla</button>                    <h3>Modifica Gita</h3>

                    <button class="button" type="submit" form="formModificaPartecipo">Salva Modifiche</button>                    <button class="close-btn" onclick="closeModal('modalModifica')">&times;</button>

                </div>                </div>

            </div>                <div class="modal-body">

        </div>                    <form id="formModifica" class="form-grid">

                        <div class="form-group">

        <footer>                            <label for="modDest">Destinazione</label>

            <div class="footer-container">                            <input type="text" id="modDest" placeholder="es. Parigi">

                <div class="footer-left">                        </div>

                    <p><strong>Gestione Gite Scolastiche</strong></p>                        <div class="form-group">

                    <p class="footer-copyright">&copy; 2026 - Piattaforma Interna</p>                            <label for="modClasse">Classe</label>

                </div>                            <input type="text" id="modClasse" placeholder="es. 5A Informatica">

            </div>                        </div>

        </footer>                        <div class="form-group">

    </div>                            <label for="modMezzo">Mezzo di Trasporto</label>

                            <select id="modMezzo">

    <script>                                <option value="Autobus">Autobus GT</option>

        var bottoniDettagli = document.querySelectorAll('.btn-dettagli-gita');                                <option value="Treno">Treno Alta Velocit&#224;</option>

        for (var i = 0; i < bottoniDettagli.length; i++) {                                <option value="Aereo">Aereo</option>

            bottoniDettagli[i].addEventListener('click', function() {                                <option value="Nave">Nave / Traghetto</option>

                var scheda = this.closest('.miegite-card');                            </select>

                document.getElementById('titoloDettagli').innerText = scheda.dataset.dest;                        </div>

                document.getElementById('detDest').value = scheda.dataset.dest;                        <div class="form-group">

                document.getElementById('detStato').value = scheda.dataset.stato;                            <label for="modCosto">Costo Totale (&#8364;)</label>

                document.getElementById('detCosto').value = '\u20ac ' + scheda.dataset.costo;                            <input type="number" id="modCosto" placeholder="0">

                openModal('modalDettagli');                        </div>

            });                        <div class="form-group">

        }                            <label for="modInizio">Data Inizio</label>

                            <input type="date" id="modInizio">

        var modaleModPartecipo = document.getElementById('modalModificaPartecipo');                        </div>

                        <div class="form-group">

        var bottoniModPartecipo = document.querySelectorAll('.btn-modifica-partecipo');                            <label for="modFine">Data Fine</label>

        for (var i = 0; i < bottoniModPartecipo.length; i++) {                            <input type="date" id="modFine">

            bottoniModPartecipo[i].addEventListener('click', function() {                        </div>

                var scheda = this.closest('.miegite-card');                        <div class="form-group">

                document.getElementById('titoloModificaPartecipo').innerText = 'Modifica: ' + scheda.dataset.dest;                            <label for="modAlunni">Alunni</label>

                document.getElementById('modPartIdGita').value = scheda.dataset.id;                            <input type="number" id="modAlunni" placeholder="es. 30">

                document.getElementById('modPartInizio').value = scheda.dataset.inizioRaw;                        </div>

                document.getElementById('modPartFine').value = scheda.dataset.fineRaw;                        <div class="form-group">

                document.getElementById('modPartOrPart').value = scheda.dataset.orPartenza;                            <label for="modDocenti">Docenti</label>

                document.getElementById('modPartOrArr').value = scheda.dataset.orArrivo;                            <input type="number" id="modDocenti" placeholder="es. 3">

                document.getElementById('modPartAlunni').value = scheda.dataset.alunni;                        </div>

                document.getElementById('modPartDisabili').value = scheda.dataset.alunniDisabili;                    </form>

                document.getElementById('modPartDocenti').value = scheda.dataset.docenti;                </div>

                document.getElementById('modPartClassi').value = scheda.dataset.classi;                <div class="modal-footer">

                var cMezzi = scheda.dataset.costoMezzi.replace('.', '').replace(',', '.');                    <button class="button cancel" onclick="closeModal('modalModifica')">Annulla</button>

                document.getElementById('modPartCostoMezzi').value = parseFloat(cMezzi) || 0;                    <button class="button" type="submit" form="formModifica">Salva Modifiche</button>

                var cAtt = scheda.dataset.costoAtt.replace('.', '').replace(',', '.');                </div>

                document.getElementById('modPartCostoAtt').value = parseFloat(cAtt) || 0;            </div>

                modaleModPartecipo.classList.remove('hidden');        </div>

            });

        }        <div class="modal-overlay hidden" id="modalElimina">

            <div class="modal" style="max-width: 420px;">

        function chiudiModificaPartecipo() { modaleModPartecipo.classList.add('hidden'); }                <div class="modal-header">

        document.getElementById('chiudiModificaPartecipo').addEventListener('click', chiudiModificaPartecipo);                    <h3>Conferma Eliminazione</h3>

        document.getElementById('annullaModificaPartecipo').addEventListener('click', chiudiModificaPartecipo);                    <button class="close-btn" onclick="closeModal('modalElimina')">&times;</button>

        window.addEventListener('click', function(e) {                </div>

            if (e.target === modaleModPartecipo) chiudiModificaPartecipo();                <div class="modal-body" style="text-align: center;">

        });                    <p>Sei sicuro di voler eliminare la gita</p>

    </script>                    <p><strong id="eliminaDest"></strong>?</p>

                    <p style="margin-top: 0.5rem; font-size:0.85rem; color: var(--my-gray);">Questa azione non pu&#242; essere annullata.</p>

</body>                </div>

</html>                <div class="modal-footer" style="justify-content: center;">

                    <button class="button outline" onclick="closeModal('modalElimina')">Annulla</button>
                    <button class="button cancel">Elimina</button>
                </div>
            </div>
        </div>

        <div class="modal-overlay hidden" id="modalModificaPartecipo">
            <div class="modal wide-modal">
                <div class="modal-header">
                    <h3 id="titoloModificaPartecipo">Modifica Gita</h3>
                    <button class="close-btn" id="chiudiModificaPartecipo">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formModificaPartecipo" class="form-grid" method="POST" action="mieGite.php">
                        <input type="hidden" name="action" value="modifica_gita_organizzata">
                        <input type="hidden" name="idGita" id="modPartIdGita" value="">
                        <div class="form-group">
                            <label for="modPartInizio">Data Inizio</label>
                            <input type="date" id="modPartInizio" name="dataInizio" required>
                        </div>
                        <div class="form-group">
                            <label for="modPartFine">Data Fine</label>
                            <input type="date" id="modPartFine" name="dataFine" required>
                        </div>
                        <div class="form-group">
                            <label for="modPartOrPart">Orario Partenza</label>
                            <input type="time" id="modPartOrPart" name="orarioPartenza">
                        </div>
                        <div class="form-group">
                            <label for="modPartOrArr">Orario Arrivo</label>
                            <input type="time" id="modPartOrArr" name="orarioArrivo">
                        </div>
                        <div class="form-group">
                            <label for="modPartAlunni">Numero Alunni</label>
                            <input type="number" id="modPartAlunni" name="alunni" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="modPartDisabili">di cui Disabili</label>
                            <input type="number" id="modPartDisabili" name="alunniDisabili" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label for="modPartDocenti">Numero Docenti</label>
                            <input type="number" id="modPartDocenti" name="docenti" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="modPartClassi">Classi Partecipanti</label>
                            <input type="text" id="modPartClassi" name="classi" placeholder="es. 5A, 5B">
                        </div>
                        <div class="form-group">
                            <label for="modPartCostoMezzi">Costo Mezzi (&euro;)</label>
                            <input type="number" step="0.01" id="modPartCostoMezzi" name="costoMezzi" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label for="modPartCostoAtt">Costo Attivit&agrave; (&euro;)</label>
                            <input type="number" step="0.01" id="modPartCostoAtt" name="costoAttivita" min="0" value="0">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="button cancel" id="annullaModificaPartecipo">Annulla</button>
                    <button class="button" type="submit" form="formModificaPartecipo">Salva Modifiche</button>
                </div>
            </div>
        </div>

        <footer>
            <div class="footer-container">
                <div class="footer-left">
                    <p><strong>Gestione Gite Scolastiche</strong></p>
                    <p class="footer-copyright">&#169; 2026 - Piattaforma Interna</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        var bottoniDettagli = document.querySelectorAll('.btn-dettagli-gita');
        for (var i = 0; i < bottoniDettagli.length; i++) {
            bottoniDettagli[i].addEventListener('click', function() {
                var scheda = this.closest('.miegite-card');
                document.getElementById('titoloDettagli').innerText = scheda.dataset.dest;
                document.getElementById('detDest').value = scheda.dataset.dest;
                document.getElementById('detStato').value = scheda.dataset.stato;
                document.getElementById('detClasse').value = scheda.dataset.classe;
                document.getElementById('detMezzo').value = scheda.dataset.mezzo;
                document.getElementById('detInizio').value = scheda.dataset.inizio;
                document.getElementById('detFine').value = scheda.dataset.fine;
                document.getElementById('detAlunni').value = scheda.dataset.alunni;
                document.getElementById('detDocenti').value = scheda.dataset.docenti;
                document.getElementById('detCosto').value = scheda.dataset.costo;
                document.getElementById('detNote').value = scheda.dataset.note;
                openModal('modalDettagli');
            });
        }

        var bottoniModifica = document.querySelectorAll('.btn-modifica-gita');
        for (var i = 0; i < bottoniModifica.length; i++) {
            bottoniModifica[i].addEventListener('click', function() {
                var scheda = this.closest('.miegite-card');
                document.getElementById('modDest').value = scheda.dataset.dest;
                document.getElementById('modClasse').value = scheda.dataset.classe;
                document.getElementById('modAlunni').value = scheda.dataset.alunni;
                document.getElementById('modDocenti').value = scheda.dataset.docenti;

                var costoTesto = scheda.dataset.costo.replace('&#8364;','').replace('.','').replace(',','.').trim();
                document.getElementById('modCosto').value = Number(costoTesto);

                var select = document.getElementById('modMezzo');
                for (var j = 0; j < select.options.length; j++) {
                    if (scheda.dataset.mezzo.indexOf(select.options[j].value) >= 0) {
                        select.selectedIndex = j;
                        break;
                    }
                }
                openModal('modalModifica');
            });
        }

        var bottoniElimina = document.querySelectorAll('.btn-elimina-gita');
        for (var i = 0; i < bottoniElimina.length; i++) {
            bottoniElimina[i].addEventListener('click', function() {
                var scheda = this.closest('.miegite-card');
                document.getElementById('eliminaDest').innerText = scheda.dataset.dest;
                openModal('modalElimina');
            });
        }

        var modaleModPartecipo = document.getElementById('modalModificaPartecipo');

        var bottoniModPartecipo = document.querySelectorAll('.btn-modifica-partecipo');
        for (var i = 0; i < bottoniModPartecipo.length; i++) {
            bottoniModPartecipo[i].addEventListener('click', function() {
                var scheda = this.closest('.miegite-card');
                document.getElementById('titoloModificaPartecipo').innerText = 'Modifica: ' + scheda.dataset.dest;
                document.getElementById('modPartIdGita').value = scheda.dataset.id;
                document.getElementById('modPartInizio').value = scheda.dataset.inizioRaw;
                document.getElementById('modPartFine').value = scheda.dataset.fineRaw;
                document.getElementById('modPartOrPart').value = scheda.dataset.orPartenza;
                document.getElementById('modPartOrArr').value = scheda.dataset.orArrivo;
                document.getElementById('modPartAlunni').value = scheda.dataset.alunni;
                document.getElementById('modPartDisabili').value = scheda.dataset.alunniDisabili;
                document.getElementById('modPartDocenti').value = scheda.dataset.docenti;
                document.getElementById('modPartClassi').value = scheda.dataset.classi;

                var cMezzi = scheda.dataset.costoMezzi.replace('.', '').replace(',', '.');
                document.getElementById('modPartCostoMezzi').value = parseFloat(cMezzi) || 0;

                var cAtt = scheda.dataset.costoAtt.replace('.', '').replace(',', '.');
                document.getElementById('modPartCostoAtt').value = parseFloat(cAtt) || 0;

                modaleModPartecipo.classList.remove('hidden');
            });
        }

        function chiudiModificaPartecipo() {
            modaleModPartecipo.classList.add('hidden');
        }

        document.getElementById('chiudiModificaPartecipo').addEventListener('click', chiudiModificaPartecipo);
        document.getElementById('annullaModificaPartecipo').addEventListener('click', chiudiModificaPartecipo);

        window.addEventListener('click', function(e) {
            if (e.target === modaleModPartecipo) chiudiModificaPartecipo();
        });
    </script>

</body>
</html>
