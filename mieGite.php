<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Gite - Le Mie Gite</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="vetrina.css">
    <link rel="stylesheet" href="style_custom.css">
    <script src="vetrina.js" defer></script>
</head>
<body>
    <?php 
        include('nav.php'); 
        $idUtenteLoggato = $_SESSION['id_utente'];
        $messaggio = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
            if ($_POST['action'] == 'modifica_gita_organizzata') {
                $idGita = intval($_POST['idGita'] ?? 0);
                $dataInizio = $_POST['dataInizio'] ?? '';
                $dataFine = $_POST['dataFine'] ?? '';
                $alunni = intval($_POST['alunni'] ?? 0);
                $alunniDisabili = intval($_POST['alunniDisabili'] ?? 0);
                $docenti = intval($_POST['docenti'] ?? 0);
                $classi = $_POST['classi'] ?? '';
                $orarioPartenza = $_POST['orarioPartenza'] ?: null;
                $orarioArrivo = $_POST['orarioArrivo'] ?: null;
                $costoMezzi = floatval($_POST['costoMezzi'] ?? 0);
                $costoAttivita = floatval($_POST['costoAttivita'] ?? 0);

                $queryPropCosto = "SELECT p.Costo FROM propostagita p JOIN gitaorganizzata g ON p.IDProposta = g.IDProposta WHERE g.IDGita = $idGita";
                $resCosto = mysqli_query($conn, $queryPropCosto);
                $rigaCosto = mysqli_fetch_assoc($resCosto);
                $costoTotale = ($rigaCosto['Costo'] * $alunni) + $costoMezzi + $costoAttivita;

                $istr = mysqli_prepare($conn, "UPDATE gitaorganizzata SET DataInizio=?, DataFine=?, NumAlunni=?, NumDocentiAccompagnatori=?, NumAlunniDisabili=?, ClassiPartecipanti=?, OrarioPartenza=?, OrarioArrivo=?, CostoMezzi=?, CostoAttivita=?, CostoTot=? WHERE IDGita=? AND IDUtente=?");
                mysqli_stmt_bind_param($istr, "ssiiisssdddii", $dataInizio, $dataFine, $alunni, $docenti, $alunniDisabili, $classi, $orarioPartenza, $orarioArrivo, $costoMezzi, $costoAttivita, $costoTotale, $idGita, $idUtenteLoggato);

                if (mysqli_stmt_execute($istr)) {
                    $messaggio = "<div style='background-color:#d4edda;color:#155724;padding:10px;margin-bottom:20px;border-radius:5px;'>Gita modificata con successo.</div>";
                } else {
                    $messaggio = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;margin-bottom:20px;border-radius:5px;'>Errore durante la modifica.</div>";
                }
                mysqli_stmt_close($istr);
            }
        }
    ?>

    <div class="container">
        <main class="content home-padding">

            <div class="hero-section">
                <h2 style="margin-bottom: 1rem; color: var(--blue-700);">Le Mie Gite</h2>
                <p>Tutte le gite che hai creato o a cui partecipi.
                Controlla lo stato, modifica i dettagli o elimina quelle non più necessarie.</p>
            </div>

            <?php echo $messaggio; ?>

            <div style="margin-top: 2rem;">
                <h2 style="margin-bottom: 1rem; color: var(--blue-700);">Gite create da me</h2>
                <div class="miegite-grid">
                    <?php 
                        $query = "
                            SELECT g.*, p.Destinazione, s.Stato 
                            FROM gitaorganizzata g 
                            JOIN propostagita p ON g.IDProposta = p.IDProposta 
                            JOIN statogita s ON g.IDStato = s.IDStato 
                            WHERE g.IDUtente = $idUtenteLoggato
                            ORDER BY g.DataInizio ASC
                        ";
                        $risultatoMie = mysqli_query($conn, $query);

                        if (mysqli_num_rows($risultatoMie) > 0) {
                            while ($row = mysqli_fetch_assoc($risultatoMie)) {
                                $dataInizio = date('d/m/Y', strtotime($row['DataInizio']));
                                $dataFine = date('d/m/Y', strtotime($row['DataFine']));
                                
                                $classeBadge = 'badge-secondary';
                                if ($row['Stato'] == 'Approvata') $classeBadge = 'badge-success';
                                if ($row['Stato'] == 'Inserita') $classeBadge = 'badge-warning';
                                if ($row['Stato'] == 'Conclusa') $classeBadge = 'badge-primary';
                                if ($row['Stato'] == 'NonApprovata') $classeBadge = 'badge-danger';
                                ?>
                                <div class="miegite-card" <?php 
                                     echo 'data-dest="' . htmlspecialchars($row['Destinazione']) . '" ';
                                     echo 'data-stato="' . htmlspecialchars($row['Stato']) . '" ';
                                     echo 'data-classe="N/D" ';
                                     echo 'data-inizio="' . $dataInizio . '" ';
                                     echo 'data-fine="' . $dataFine . '" ';
                                     echo 'data-mezzo="" ';
                                     echo 'data-alunni="' . $row['NumAlunni'] . '" ';
                                     echo 'data-docenti="' . $row['NumDocentiAccompagnatori'] . '" ';
                                     echo 'data-costo="' . number_format($row['CostoTot'], 2, ',', '.') . '" ';
                                     echo 'data-note=""';
                                ?>>
                                    <div class="miegite-card-header">
                                        <h3 class="miegite-card-title"><?php echo htmlspecialchars($row['Destinazione']); ?></h3>
                                        <span class="badge <?php echo $classeBadge; ?>"><?php echo htmlspecialchars($row['Stato']); ?></span>
                                    </div>
                                    <div class="miegite-card-body">
                                        <div class="miegite-card-info">
                                            <span><strong>Date:</strong> <?php echo $dataInizio . ' &#8211; ' . $dataFine; ?></span>
                                            <span><strong>Partecipanti:</strong> <?php echo $row['NumAlunni'] . ' alunni, ' . $row['NumDocentiAccompagnatori'] . ' docenti'; ?></span>
                                            <span><strong>Costo:</strong> &#8364; <?php echo number_format($row['CostoTot'], 2, ',', '.'); ?></span>
                                        </div>
                                    </div>
                                    <div class="miegite-card-footer">
                                        <button class="xs outline btn-dettagli-gita">Dettagli</button>
                                        <button class="xs outline btn-modifica-gita">Modifica</button>
                                        <button class="xs cancel btn-elimina-gita">Elimina</button>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p>Non hai ancora creato nessuna gita.</p>";
                        }
                    ?>
                </div>
            </div>

            <div style="margin-top: 3rem;">
                <h2 style="margin-bottom: 1rem; color: var(--blue-700);">Gite a cui partecipo</h2>
                <div class="miegite-grid">
                    <?php
                        $query = "SELECT g.*, p.Destinazione, s.Stato FROM gitaorganizzata g JOIN propostagita p ON g.IDProposta = p.IDProposta JOIN statogita s ON g.IDStato = s.IDStato WHERE g.IDUtente = $idUtenteLoggato AND g.IDStato = 5 ORDER BY g.DataInizio ASC";
                        $risultatoPartecipo = mysqli_query($conn, $query);

                        if (mysqli_num_rows($risultatoPartecipo) > 0) {
                            while ($row = mysqli_fetch_assoc($risultatoPartecipo)) {
                                $dataInizio = date('d/m/Y', strtotime($row['DataInizio']));
                                $dataFine = date('d/m/Y', strtotime($row['DataFine']));
                                $orPartenza = $row['OrarioPartenza'] ? substr($row['OrarioPartenza'], 0, 5) : '';
                                $orArrivo = $row['OrarioArrivo'] ? substr($row['OrarioArrivo'], 0, 5) : '';
                                $dataInizioRaw = $row['DataInizio'];
                                $dataFineRaw = $row['DataFine'];
                                ?>
                                <div class="miegite-card"
                                    data-id="<?php echo $row['IDGita']; ?>"
                                    data-dest="<?php echo htmlspecialchars($row['Destinazione']); ?>"
                                    data-stato="<?php echo htmlspecialchars($row['Stato']); ?>"
                                    data-inizio="<?php echo $dataInizio; ?>"
                                    data-fine="<?php echo $dataFine; ?>"
                                    data-inizio-raw="<?php echo $dataInizioRaw; ?>"
                                    data-fine-raw="<?php echo $dataFineRaw; ?>"
                                    data-alunni="<?php echo $row['NumAlunni']; ?>"
                                    data-alunni-disabili="<?php echo $row['NumAlunniDisabili']; ?>"
                                    data-docenti="<?php echo $row['NumDocentiAccompagnatori']; ?>"
                                    data-classi="<?php echo htmlspecialchars($row['ClassiPartecipanti'] ?? ''); ?>"
                                    data-or-partenza="<?php echo $orPartenza; ?>"
                                    data-or-arrivo="<?php echo $orArrivo; ?>"
                                    data-costo-mezzi="<?php echo number_format($row['CostoMezzi'], 2, ',', '.'); ?>"
                                    data-costo-att="<?php echo number_format($row['CostoAttivita'], 2, ',', '.'); ?>"
                                    data-costo="<?php echo number_format($row['CostoTot'], 2, ',', '.'); ?>"
                                    data-mezzo="" data-classe="<?php echo htmlspecialchars($row['ClassiPartecipanti'] ?? ''); ?>" data-note="">
                                    <div class="miegite-card-header">
                                        <h3 class="miegite-card-title"><?php echo htmlspecialchars($row['Destinazione']); ?></h3>
                                        <span class="badge badge-primary"><?php echo htmlspecialchars($row['Stato']); ?></span>
                                    </div>
                                    <div class="miegite-card-body">
                                        <div class="miegite-card-info">
                                            <span><strong>Date:</strong> <?php echo $dataInizio . ' &#8211; ' . $dataFine; ?></span>
                                            <?php if ($row['ClassiPartecipanti']) { ?>
                                            <span><strong>Classi:</strong> <?php echo htmlspecialchars($row['ClassiPartecipanti']); ?></span>
                                            <?php } ?>
                                            <span><strong>Partecipanti:</strong> <?php echo $row['NumAlunni'] . ' alunni, ' . $row['NumDocentiAccompagnatori'] . ' docenti'; ?></span>
                                            <span><strong>Costo:</strong> &#8364; <?php echo number_format($row['CostoTot'], 2, ',', '.'); ?></span>
                                        </div>
                                    </div>
                                    <div class="miegite-card-footer">
                                        <button class="xs outline btn-dettagli-gita">Dettagli</button>
                                        <button class="xs outline btn-modifica-partecipo">Modifica</button>
                                        <a href="partecipanti.php?id=<?php echo $row['IDGita']; ?>" class="xs button" style="text-decoration:none;">Partecipanti</a>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p>Nessuna gita organizzata al momento.</p>";
                        }
                    ?>
                </div>
            </div>

        </main>

        <div class="modal-overlay hidden" id="modalDettagli">
            <div class="modal wide-modal">
                <div class="modal-header">
                    <h3 id="titoloDettagli">Dettagli Gita</h3>
                    <button class="close-btn" onclick="closeModal('modalDettagli')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-grid" style="pointer-events:none;">
                        <div class="form-group">
                            <label>Destinazione</label>
                            <input type="text" id="detDest" readonly>
                        </div>
                        <div class="form-group">
                            <label>Stato</label>
                            <input type="text" id="detStato" readonly>
                        </div>
                        <div class="form-group">
                            <label>Classe</label>
                            <input type="text" id="detClasse" readonly>
                        </div>
                        <div class="form-group">
                            <label>Mezzo di Trasporto</label>
                            <input type="text" id="detMezzo" readonly>
                        </div>
                        <div class="form-group">
                            <label>Data Inizio</label>
                            <input type="text" id="detInizio" readonly>
                        </div>
                        <div class="form-group">
                            <label>Data Fine</label>
                            <input type="text" id="detFine" readonly>
                        </div>
                        <div class="form-group">
                            <label>Alunni</label>
                            <input type="text" id="detAlunni" readonly>
                        </div>
                        <div class="form-group">
                            <label>Docenti</label>
                            <input type="text" id="detDocenti" readonly>
                        </div>
                        <div class="form-group">
                            <label>Costo Totale</label>
                            <input type="text" id="detCosto" readonly>
                        </div>
                        <div class="form-group">
                            <label>Note</label>
                            <input type="text" id="detNote" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="button cancel" onclick="closeModal('modalDettagli')">Chiudi</button>
                </div>
            </div>
        </div>

        <div class="modal-overlay hidden" id="modalModifica">
            <div class="modal wide-modal">
                <div class="modal-header">
                    <h3>Modifica Gita</h3>
                    <button class="close-btn" onclick="closeModal('modalModifica')">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formModifica" class="form-grid">
                        <div class="form-group">
                            <label for="modDest">Destinazione</label>
                            <input type="text" id="modDest" placeholder="es. Parigi">
                        </div>
                        <div class="form-group">
                            <label for="modClasse">Classe</label>
                            <input type="text" id="modClasse" placeholder="es. 5A Informatica">
                        </div>
                        <div class="form-group">
                            <label for="modMezzo">Mezzo di Trasporto</label>
                            <select id="modMezzo">
                                <option value="Autobus">Autobus GT</option>
                                <option value="Treno">Treno Alta Velocit&#224;</option>
                                <option value="Aereo">Aereo</option>
                                <option value="Nave">Nave / Traghetto</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="modCosto">Costo Totale (&#8364;)</label>
                            <input type="number" id="modCosto" placeholder="0">
                        </div>
                        <div class="form-group">
                            <label for="modInizio">Data Inizio</label>
                            <input type="date" id="modInizio">
                        </div>
                        <div class="form-group">
                            <label for="modFine">Data Fine</label>
                            <input type="date" id="modFine">
                        </div>
                        <div class="form-group">
                            <label for="modAlunni">Alunni</label>
                            <input type="number" id="modAlunni" placeholder="es. 30">
                        </div>
                        <div class="form-group">
                            <label for="modDocenti">Docenti</label>
                            <input type="number" id="modDocenti" placeholder="es. 3">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="button cancel" onclick="closeModal('modalModifica')">Annulla</button>
                    <button class="button" type="submit" form="formModifica">Salva Modifiche</button>
                </div>
            </div>
        </div>

        <div class="modal-overlay hidden" id="modalElimina">
            <div class="modal" style="max-width: 420px;">
                <div class="modal-header">
                    <h3>Conferma Eliminazione</h3>
                    <button class="close-btn" onclick="closeModal('modalElimina')">&times;</button>
                </div>
                <div class="modal-body" style="text-align: center;">
                    <p>Sei sicuro di voler eliminare la gita</p>
                    <p><strong id="eliminaDest"></strong>?</p>
                    <p style="margin-top: 0.5rem; font-size:0.85rem; color: var(--my-gray);">Questa azione non pu&#242; essere annullata.</p>
                </div>
                <div class="modal-footer" style="justify-content: center;">
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
