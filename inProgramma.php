<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Gite - In Programma</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="vetrina.css">
    <link rel="stylesheet" href="style_custom.css">
    <script src="vetrina.js" defer></script>
</head>
<body>
    <?php include('nav.php'); ?>


    <div class="container">
        <main class="content home-padding">
            
            <div class="hero-section">
                <h2 style="margin-bottom: 1rem; color: var(--blue-700);">Gite in Programma</h2>
                <p>Elenco generale di tutte le gite organizzate e il loro stato di avanzamento. Monitora le date, i partecipanti e le approvazioni.</p>
            </div>

            <div class="table-section" style="margin-top: 2rem;">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Destinazione</th>
                                <th>Data Inizio</th>
                                <th>Data Fine</th>
                                <th>Classi</th>
                                <th>Alunni</th>
                                <th>Docenti</th>
                                <th>Costo Totale</th>
                                <th>Stato</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query = "SELECT g.*, p.Destinazione, s.Stato FROM gitaorganizzata g JOIN propostagita p ON g.IDProposta = p.IDProposta JOIN statogita s ON g.IDStato = s.IDStato ORDER BY g.DataInizio ASC";
                                $risultato = mysqli_query($conn, $query);

                                if (mysqli_num_rows($risultato) > 0) {
                                    while ($row = mysqli_fetch_assoc($risultato)) {
                                        $dataInizio = date('d/m/Y', strtotime($row['DataInizio']));
                                        $dataFine = date('d/m/Y', strtotime($row['DataFine']));
                                        $orarioPart = $row['OrarioPartenza'] ? substr($row['OrarioPartenza'], 0, 5) : '-';
                                        $orarioArr = $row['OrarioArrivo'] ? substr($row['OrarioArrivo'], 0, 5) : '-';
                                        $classi = $row['ClassiPartecipanti'] ? htmlspecialchars($row['ClassiPartecipanti']) : '-';

                                        $classeBadge = 'badge-secondary';
                                        if ($row['Stato'] == 'Approvata') $classeBadge = 'badge-success';
                                        if ($row['Stato'] == 'Inserita') $classeBadge = 'badge-warning';
                                        if ($row['Stato'] == 'Conclusa') $classeBadge = 'badge-primary';
                                        if ($row['Stato'] == 'NonApprovata') $classeBadge = 'badge-danger';
                                        if ($row['Stato'] == 'Organizzata') $classeBadge = 'badge-success';

                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['Destinazione']) . "</td>";
                                        echo "<td>" . $dataInizio . "</td>";
                                        echo "<td>" . $dataFine . "</td>";
                                        echo "<td>" . $classi . "</td>";
                                        echo "<td>" . $row['NumAlunni'] . " <small style='color:#64748b;'>(" . $row['NumAlunniDisabili'] . " dis.)</small></td>";
                                        echo "<td>" . $row['NumDocentiAccompagnatori'] . "</td>";
                                        echo "<td>&euro; " . number_format($row['CostoTot'], 2, ',', '.') . "</td>";
                                        echo "<td><span class='badge $classeBadge'>" . htmlspecialchars($row['Stato']) . "</span></td>";
                                        echo "<td>
                                            <button class='xs outline btn-dettagli-prog'
                                                data-dest='" . htmlspecialchars($row['Destinazione']) . "'
                                                data-inizio='$dataInizio'
                                                data-fine='$dataFine'
                                                data-classi='" . htmlspecialchars($row['ClassiPartecipanti'] ?? '') . "'
                                                data-alunni='" . $row['NumAlunni'] . "'
                                                data-disabili='" . $row['NumAlunniDisabili'] . "'
                                                data-docenti='" . $row['NumDocentiAccompagnatori'] . "'
                                                data-partenza='$orarioPart'
                                                data-arrivo='$orarioArr'
                                                data-costo='" . number_format($row['CostoTot'], 2, ',', '.') . "'
                                                data-costo-mezzi='" . number_format($row['CostoMezzi'], 2, ',', '.') . "'
                                                data-costo-att='" . number_format($row['CostoAttivita'], 2, ',', '.') . "'
                                                data-stato='" . htmlspecialchars($row['Stato']) . "'
                                            >Dettagli</button>
                                        </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='9' style='text-align:center;'>Nessuna gita in programma.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

        <div class="modal-overlay hidden" id="modalDettagliProg">
            <div class="modal wide-modal">
                <div class="modal-header">
                    <h3 id="titoloProg">Dettagli Gita</h3>
                    <button class="close-btn" id="chiudiProg">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-grid" style="pointer-events:none;">
                        <div class="form-group">
                            <label>Destinazione</label>
                            <input type="text" id="progDest" readonly>
                        </div>
                        <div class="form-group">
                            <label>Stato</label>
                            <input type="text" id="progStato" readonly>
                        </div>
                        <div class="form-group">
                            <label>Data Inizio</label>
                            <input type="text" id="progInizio" readonly>
                        </div>
                        <div class="form-group">
                            <label>Data Fine</label>
                            <input type="text" id="progFine" readonly>
                        </div>
                        <div class="form-group">
                            <label>Orario Partenza</label>
                            <input type="text" id="progPartenza" readonly>
                        </div>
                        <div class="form-group">
                            <label>Orario Arrivo</label>
                            <input type="text" id="progArrivo" readonly>
                        </div>
                        <div class="form-group">
                            <label>Classi</label>
                            <input type="text" id="progClassi" readonly>
                        </div>
                        <div class="form-group">
                            <label>Alunni (di cui disabili)</label>
                            <input type="text" id="progAlunni" readonly>
                        </div>
                        <div class="form-group">
                            <label>Docenti</label>
                            <input type="text" id="progDocenti" readonly>
                        </div>
                        <div class="form-group">
                            <label>Costo Totale</label>
                            <input type="text" id="progCosto" readonly>
                        </div>
                        <div class="form-group">
                            <label>di cui Mezzi</label>
                            <input type="text" id="progCostoMezzi" readonly>
                        </div>
                        <div class="form-group">
                            <label>di cui Attivit&agrave;</label>
                            <input type="text" id="progCostoAtt" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="button cancel" id="chiudiProgBtn">Chiudi</button>
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
        var modaleDettagli = document.getElementById('modalDettagliProg');

        var bottoni = document.querySelectorAll('.btn-dettagli-prog');
        for (var i = 0; i < bottoni.length; i++) {
            bottoni[i].addEventListener('click', function() {
                var b = this;
                document.getElementById('titoloProg').innerText = b.dataset.dest;
                document.getElementById('progDest').value = b.dataset.dest;
                document.getElementById('progStato').value = b.dataset.stato;
                document.getElementById('progInizio').value = b.dataset.inizio;
                document.getElementById('progFine').value = b.dataset.fine;
                document.getElementById('progPartenza').value = b.dataset.partenza;
                document.getElementById('progArrivo').value = b.dataset.arrivo;
                document.getElementById('progClassi').value = b.dataset.classi || '-';
                document.getElementById('progAlunni').value = b.dataset.alunni + ' (' + b.dataset.disabili + ' disabili)';
                document.getElementById('progDocenti').value = b.dataset.docenti;
                document.getElementById('progCosto').value = '€ ' + b.dataset.costo;
                document.getElementById('progCostoMezzi').value = '€ ' + b.dataset.costoMezzi;
                document.getElementById('progCostoAtt').value = '€ ' + b.dataset.costoAtt;
                modaleDettagli.classList.remove('hidden');
            });
        }

        function chiudiProg() {
            modaleDettagli.classList.add('hidden');
        }

        document.getElementById('chiudiProg').addEventListener('click', chiudiProg);
        document.getElementById('chiudiProgBtn').addEventListener('click', chiudiProg);
        window.addEventListener('click', function(e) {
            if (e.target === modaleDettagli) chiudiProg();
        });
    </script>

</body>
</html>