<?php include('nav.php'); ?><!DOCTYPE html>

<!DOCTYPE html><html lang="it">

<html lang="it"><head>

<head>    <meta charset="UTF-8">

    <meta charset="UTF-8">    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <title>Gestione Gite - In Programma</title>

    <title>Gestione Gite - In Programma</title>    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="style.css">    <link rel="stylesheet" href="vetrina.css">

    <link rel="stylesheet" href="vetrina.css">    <link rel="stylesheet" href="style_custom.css">

    <link rel="stylesheet" href="style_custom.css">    <script src="vetrina.js" defer></script>

    <script src="vetrina.js" defer></script></head>

</head><body>

<body>    <?php include('nav.php'); ?>

    <div class="container">

        <main class="content home-padding">

                <div class="container">

            <div class="hero-section">        <main class="content home-padding">

                <h2 style="margin-bottom:1rem; color:var(--blue-700);">Gite in Programma</h2>            

                <p>Elenco di tutte le gite in organizzazione o concluse. Monitora date, partecipanti e stato.</p>            <div class="hero-section">

            </div>                <h2 style="margin-bottom: 1rem; color: var(--blue-700);">Gite in Programma</h2>

                <p>Elenco generale di tutte le gite organizzate e il loro stato di avanzamento. Monitora le date, i partecipanti e le approvazioni.</p>

            <div class="table-section" style="margin-top:2rem;">            </div>

                <div class="table-container">

                    <table>            <div class="table-section" style="margin-top: 2rem;">

                        <thead>                <div class="table-container">

                            <tr>                    <table>

                                <th>Destinazione / Classi</th>                        <thead>

                                <th>Data Inizio</th>                            <tr>

                                <th>Data Fine</th>                                <th>Destinazione</th>

                                <th>Alunni</th>                                <th>Data Inizio</th>

                                <th>Docenti</th>                                <th>Data Fine</th>

                                <th>Costo Totale</th>                                <th>Classi</th>

                                <th>Stato</th>                                <th>Alunni</th>

                                <th>Azioni</th>                                <th>Docenti</th>

                            </tr>                                <th>Costo Totale</th>

                        </thead>                                <th>Stato</th>

                        <tbody>                                <th>Azioni</th>

                            <?php                            </tr>

                                $query = "SELECT g.*, s.Stato FROM gitaorganizzata g JOIN statogita s ON g.IDStato = s.IDStato WHERE g.IDStato IN (4, 5) ORDER BY g.DataInizio ASC";                        </thead>

                                $risultato = mysqli_query($conn, $query);                        <tbody>

                            <?php

                                if (mysqli_num_rows($risultato) > 0) {                                $query = "SELECT g.*, p.Destinazione, s.Stato FROM gitaorganizzata g JOIN propostagita p ON g.IDProposta = p.IDProposta JOIN statogita s ON g.IDStato = s.IDStato ORDER BY g.DataInizio ASC";

                                    while ($row = mysqli_fetch_assoc($risultato)) {                                $risultato = mysqli_query($conn, $query);

                                        $dataInizio = $row['DataInizio'] ? date('d/m/Y', strtotime($row['DataInizio'])) : '-';

                                        $dataFine = $row['DataFine'] ? date('d/m/Y', strtotime($row['DataFine'])) : '-';                                if (mysqli_num_rows($risultato) > 0) {

                                        $orarioPart = $row['OrarioPartenza'] ? substr($row['OrarioPartenza'], 0, 5) : '-';                                    while ($row = mysqli_fetch_assoc($risultato)) {

                                        $orarioArr = $row['OrarioArrivo'] ? substr($row['OrarioArrivo'], 0, 5) : '-';                                        $dataInizio = date('d/m/Y', strtotime($row['DataInizio']));

                                        $classi = htmlspecialchars($row['ClassiPartecipanti'] ?? '-');                                        $dataFine = date('d/m/Y', strtotime($row['DataFine']));

                                        $dest = htmlspecialchars($row['ClassiPartecipanti'] ?? 'N/D');                                        $orarioPart = $row['OrarioPartenza'] ? substr($row['OrarioPartenza'], 0, 5) : '-';

                                        $orarioArr = $row['OrarioArrivo'] ? substr($row['OrarioArrivo'], 0, 5) : '-';

                                        $classeBadge = 'badge-success';                                        $classi = $row['ClassiPartecipanti'] ? htmlspecialchars($row['ClassiPartecipanti']) : '-';

                                        if ($row['Stato'] == 'Conclusa') $classeBadge = 'badge-primary';

                                        $classeBadge = 'badge-secondary';

                                        echo "<tr>";                                        if ($row['Stato'] == 'Approvata') $classeBadge = 'badge-success';

                                        echo "<td><strong>$dest</strong></td>";                                        if ($row['Stato'] == 'Inserita') $classeBadge = 'badge-warning';

                                        echo "<td>$dataInizio</td>";                                        if ($row['Stato'] == 'Conclusa') $classeBadge = 'badge-primary';

                                        echo "<td>$dataFine</td>";                                        if ($row['Stato'] == 'NonApprovata') $classeBadge = 'badge-danger';

                                        echo "<td>" . $row['NumAlunni'] . " <small style='color:#64748b;'>(" . $row['NumAlunniDisabili'] . " dis.)</small></td>";                                        if ($row['Stato'] == 'Organizzata') $classeBadge = 'badge-success';

                                        echo "<td>" . $row['NumDocentiAccompagnatori'] . "</td>";

                                        echo "<td>&euro; " . number_format($row['CostoTot'], 2, ',', '.') . "</td>";                                        echo "<tr>";

                                        echo "<td><span class='badge $classeBadge'>" . htmlspecialchars($row['Stato']) . "</span></td>";                                        echo "<td>" . htmlspecialchars($row['Destinazione']) . "</td>";

                                        echo "<td>                                        echo "<td>" . $dataInizio . "</td>";

                                            <button class='xs outline btn-dettagli-prog'                                        echo "<td>" . $dataFine . "</td>";

                                                data-dest='$dest'                                        echo "<td>" . $classi . "</td>";

                                                data-inizio='$dataInizio'                                        echo "<td>" . $row['NumAlunni'] . " <small style='color:#64748b;'>(" . $row['NumAlunniDisabili'] . " dis.)</small></td>";

                                                data-fine='$dataFine'                                        echo "<td>" . $row['NumDocentiAccompagnatori'] . "</td>";

                                                data-classi='" . htmlspecialchars($row['ClassiPartecipanti'] ?? '') . "'                                        echo "<td>&euro; " . number_format($row['CostoTot'], 2, ',', '.') . "</td>";

                                                data-alunni='" . $row['NumAlunni'] . "'                                        echo "<td><span class='badge $classeBadge'>" . htmlspecialchars($row['Stato']) . "</span></td>";

                                                data-disabili='" . $row['NumAlunniDisabili'] . "'                                        echo "<td>

                                                data-docenti='" . $row['NumDocentiAccompagnatori'] . "'                                            <button class='xs outline btn-dettagli-prog'

                                                data-partenza='$orarioPart'                                                data-dest='" . htmlspecialchars($row['Destinazione']) . "'

                                                data-arrivo='$orarioArr'                                                data-inizio='$dataInizio'

                                                data-costo='" . number_format($row['CostoTot'], 2, ',', '.') . "'                                                data-fine='$dataFine'

                                                data-costo-mezzi='" . number_format($row['CostoMezzi'], 2, ',', '.') . "'                                                data-classi='" . htmlspecialchars($row['ClassiPartecipanti'] ?? '') . "'

                                                data-costo-att='" . number_format($row['CostoAttivita'], 2, ',', '.') . "'                                                data-alunni='" . $row['NumAlunni'] . "'

                                                data-stato='" . htmlspecialchars($row['Stato']) . "'                                                data-disabili='" . $row['NumAlunniDisabili'] . "'

                                            >Dettagli</button>                                                data-docenti='" . $row['NumDocentiAccompagnatori'] . "'

                                        </td>";                                                data-partenza='$orarioPart'

                                        echo "</tr>";                                                data-arrivo='$orarioArr'

                                    }                                                data-costo='" . number_format($row['CostoTot'], 2, ',', '.') . "'

                                } else {                                                data-costo-mezzi='" . number_format($row['CostoMezzi'], 2, ',', '.') . "'

                                    echo "<tr><td colspan='8' style='text-align:center;'>Nessuna gita in programma.</td></tr>";                                                data-costo-att='" . number_format($row['CostoAttivita'], 2, ',', '.') . "'

                                }                                                data-stato='" . htmlspecialchars($row['Stato']) . "'

                            ?>                                            >Dettagli</button>

                        </tbody>                                        </td>";

                    </table>                                        echo "</tr>";

                </div>                                    }

            </div>                                } else {

                                    echo "<tr><td colspan='9' style='text-align:center;'>Nessuna gita in programma.</td></tr>";

        </main>                                }

                            ?>

        <div class="modal-overlay hidden" id="modalDettagliProg">                        </tbody>

            <div class="modal wide-modal">                    </table>

                <div class="modal-header">                </div>

                    <h3 id="titoloProg">Dettagli Gita</h3>            </div>

                    <button class="close-btn" id="chiudiProg">&times;</button>

                </div>        </main>

                <div class="modal-body">

                    <div class="form-grid" style="pointer-events:none;">        <div class="modal-overlay hidden" id="modalDettagliProg">

                        <div class="form-group">            <div class="modal wide-modal">

                            <label>Destinazione</label>                <div class="modal-header">

                            <input type="text" id="progDest" readonly>                    <h3 id="titoloProg">Dettagli Gita</h3>

                        </div>                    <button class="close-btn" id="chiudiProg">&times;</button>

                        <div class="form-group">                </div>

                            <label>Stato</label>                <div class="modal-body">

                            <input type="text" id="progStato" readonly>                    <div class="form-grid" style="pointer-events:none;">

                        </div>                        <div class="form-group">

                        <div class="form-group">                            <label>Destinazione</label>

                            <label>Data Inizio</label>                            <input type="text" id="progDest" readonly>

                            <input type="text" id="progInizio" readonly>                        </div>

                        </div>                        <div class="form-group">

                        <div class="form-group">                            <label>Stato</label>

                            <label>Data Fine</label>                            <input type="text" id="progStato" readonly>

                            <input type="text" id="progFine" readonly>                        </div>

                        </div>                        <div class="form-group">

                        <div class="form-group">                            <label>Data Inizio</label>

                            <label>Orario Partenza</label>                            <input type="text" id="progInizio" readonly>

                            <input type="text" id="progPartenza" readonly>                        </div>

                        </div>                        <div class="form-group">

                        <div class="form-group">                            <label>Data Fine</label>

                            <label>Orario Arrivo</label>                            <input type="text" id="progFine" readonly>

                            <input type="text" id="progArrivo" readonly>                        </div>

                        </div>                        <div class="form-group">

                        <div class="form-group">                            <label>Orario Partenza</label>

                            <label>Classi</label>                            <input type="text" id="progPartenza" readonly>

                            <input type="text" id="progClassi" readonly>                        </div>

                        </div>                        <div class="form-group">

                        <div class="form-group">                            <label>Orario Arrivo</label>

                            <label>Alunni (di cui disabili)</label>                            <input type="text" id="progArrivo" readonly>

                            <input type="text" id="progAlunni" readonly>                        </div>

                        </div>                        <div class="form-group">

                        <div class="form-group">                            <label>Classi</label>

                            <label>Docenti</label>                            <input type="text" id="progClassi" readonly>

                            <input type="text" id="progDocenti" readonly>                        </div>

                        </div>                        <div class="form-group">

                        <div class="form-group">                            <label>Alunni (di cui disabili)</label>

                            <label>Costo Totale</label>                            <input type="text" id="progAlunni" readonly>

                            <input type="text" id="progCosto" readonly>                        </div>

                        </div>                        <div class="form-group">

                        <div class="form-group">                            <label>Docenti</label>

                            <label>di cui Mezzi</label>                            <input type="text" id="progDocenti" readonly>

                            <input type="text" id="progCostoMezzi" readonly>                        </div>

                        </div>                        <div class="form-group">

                        <div class="form-group">                            <label>Costo Totale</label>

                            <label>di cui Attivit&agrave;</label>                            <input type="text" id="progCosto" readonly>

                            <input type="text" id="progCostoAtt" readonly>                        </div>

                        </div>                        <div class="form-group">

                    </div>                            <label>di cui Mezzi</label>

                </div>                            <input type="text" id="progCostoMezzi" readonly>

                <div class="modal-footer">                        </div>

                    <button class="button cancel" id="chiudiProgBtn">Chiudi</button>                        <div class="form-group">

                </div>                            <label>di cui Attivit&agrave;</label>

            </div>                            <input type="text" id="progCostoAtt" readonly>

        </div>                        </div>

                    </div>

        <footer>                </div>

            <div class="footer-container">                <div class="modal-footer">

                <div class="footer-left">                    <button class="button cancel" id="chiudiProgBtn">Chiudi</button>

                    <p><strong>Gestione Gite Scolastiche</strong></p>                </div>

                </div>            </div>

            </div>        </div>

        </footer>

    </div>        <footer>

            <div class="footer-container">

    <script>                <div class="footer-left">

        var modaleDettagli = document.getElementById('modalDettagliProg');                    <p><strong>Gestione Gite Scolastiche</strong></p>

                </div>

        var bottoni = document.querySelectorAll('.btn-dettagli-prog');            </div>

        for (var i = 0; i < bottoni.length; i++) {        </footer>

            bottoni[i].addEventListener('click', function() {    </div>

                var b = this;

                document.getElementById('titoloProg').innerText = b.dataset.dest;    <script>

                document.getElementById('progDest').value = b.dataset.dest;        var modaleDettagli = document.getElementById('modalDettagliProg');

                document.getElementById('progStato').value = b.dataset.stato;

                document.getElementById('progInizio').value = b.dataset.inizio;        var bottoni = document.querySelectorAll('.btn-dettagli-prog');

                document.getElementById('progFine').value = b.dataset.fine;        for (var i = 0; i < bottoni.length; i++) {

                document.getElementById('progPartenza').value = b.dataset.partenza;            bottoni[i].addEventListener('click', function() {

                document.getElementById('progArrivo').value = b.dataset.arrivo;                var b = this;

                document.getElementById('progClassi').value = b.dataset.classi || '-';                document.getElementById('titoloProg').innerText = b.dataset.dest;

                document.getElementById('progAlunni').value = b.dataset.alunni + ' (' + b.dataset.disabili + ' disabili)';                document.getElementById('progDest').value = b.dataset.dest;

                document.getElementById('progDocenti').value = b.dataset.docenti;                document.getElementById('progStato').value = b.dataset.stato;

                document.getElementById('progCosto').value = '\u20ac ' + b.dataset.costo;                document.getElementById('progInizio').value = b.dataset.inizio;

                document.getElementById('progCostoMezzi').value = '\u20ac ' + b.dataset.costoMezzi;                document.getElementById('progFine').value = b.dataset.fine;

                document.getElementById('progCostoAtt').value = '\u20ac ' + b.dataset.costoAtt;                document.getElementById('progPartenza').value = b.dataset.partenza;

                modaleDettagli.classList.remove('hidden');                document.getElementById('progArrivo').value = b.dataset.arrivo;

            });                document.getElementById('progClassi').value = b.dataset.classi || '-';

        }                document.getElementById('progAlunni').value = b.dataset.alunni + ' (' + b.dataset.disabili + ' disabili)';

                document.getElementById('progDocenti').value = b.dataset.docenti;

        function chiudiProg() { modaleDettagli.classList.add('hidden'); }                document.getElementById('progCosto').value = '€ ' + b.dataset.costo;

        document.getElementById('chiudiProg').addEventListener('click', chiudiProg);                document.getElementById('progCostoMezzi').value = '€ ' + b.dataset.costoMezzi;

        document.getElementById('chiudiProgBtn').addEventListener('click', chiudiProg);                document.getElementById('progCostoAtt').value = '€ ' + b.dataset.costoAtt;

        window.addEventListener('click', function(e) {                modaleDettagli.classList.remove('hidden');

            if (e.target === modaleDettagli) chiudiProg();            });

        });        }

    </script>

        function chiudiProg() {

</body>            modaleDettagli.classList.add('hidden');

</html>        }


        document.getElementById('chiudiProg').addEventListener('click', chiudiProg);
        document.getElementById('chiudiProgBtn').addEventListener('click', chiudiProg);
        window.addEventListener('click', function(e) {
            if (e.target === modaleDettagli) chiudiProg();
        });
    </script>

</body>
</html>