<?php 
include('nav.php'); 
$messaggio = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'nuova_proposta') {
        $destinazione = $_POST['destinazione'] ?? '';
        $mezzo = $_POST['mezzo'] ?? '';
        $periodo = $_POST['periodo'] ?? '';
        $costo = $_POST['costo'] ?? 0;
        $minPart = $_POST['minPart'] ?? 0;
        $maxPart = $_POST['maxPart'] ?? 0;
        $idUtente = $_SESSION['id_utente'] ?? 0;

        $istr = mysqli_prepare($conn, "INSERT INTO propostagita (Destinazione, MezzoDiTrasporto, Periodo, MinPartecipanti, MaxPartecipanti, Costo, IDUtente) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($istr, "sssiidi", $destinazione, $mezzo, $periodo, $minPart, $maxPart, $costo, $idUtente);

        $mostraSuccesso = false;

        if (mysqli_stmt_execute($istr)) {
            $idNuovaProposta = mysqli_insert_id($conn);
            $stato = 1;
            $alunni = 0;
            $docenti = 0;
            $costoTotale = $costo;

            $istrGita = mysqli_prepare($conn, "INSERT INTO gitaorganizzata (IDProposta, IDUtente, DataInizio, DataFine, NumAlunni, NumDocentiAccompagnatori, CostoTot, IDStato) VALUES (?, ?, CURDATE(), CURDATE(), ?, ?, ?, ?)");
            mysqli_stmt_bind_param($istrGita, "iiiidi", $idNuovaProposta, $idUtente, $alunni, $docenti, $costoTotale, $stato);
            mysqli_stmt_execute($istrGita);
            mysqli_stmt_close($istrGita);

            $mostraSuccesso = true;
        } else {
            $messaggio = "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>Errore durante l'aggiunta della proposta.</div>";
        }
        mysqli_stmt_close($istr);

    } elseif ($_POST['action'] == 'modifica_proposta') {
        $idProposta = $_POST['idProposta'] ?? 0;
        $destinazione = $_POST['destinazione'] ?? '';
        $mezzo = $_POST['mezzo'] ?? '';
        $periodo = $_POST['periodo'] ?? '';
        $costo = $_POST['costo'] ?? 0;
        $minPart = $_POST['minPart'] ?? 0;
        $maxPart = $_POST['maxPart'] ?? 0;

        $istr = mysqli_prepare($conn, "UPDATE propostagita SET Destinazione=?, MezzoDiTrasporto=?, Periodo=?, MinPartecipanti=?, MaxPartecipanti=?, Costo=? WHERE IDProposta=?");
        mysqli_stmt_bind_param($istr, "sssiidi", $destinazione, $mezzo, $periodo, $minPart, $maxPart, $costo, $idProposta);

        if (mysqli_stmt_execute($istr)) {
            $messaggio = "<div style='background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>Proposta modificata con successo.</div>";
        } else {
            $messaggio = "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>Errore durante la modifica della proposta.</div>";
        }
        mysqli_stmt_close($istr);

    } elseif ($_POST['action'] == 'organizza_gita') {
        $idProposta = $_POST['idProposta'] ?? 0;
        $dataInizio = $_POST['dataInizio'] ?? '';
        $dataFine = $_POST['dataFine'] ?? '';
        $alunni = $_POST['alunni'] ?? 0;
        $docenti = $_POST['docenti'] ?? 0;
        $idUtente = $_SESSION['id_utente'] ?? 0;

        $queryProp = "SELECT Costo FROM propostagita WHERE IDProposta = $idProposta";
        $resProp = mysqli_query($conn, $queryProp);
        $rigaProp = mysqli_fetch_assoc($resProp);
        $costoTotale = $rigaProp['Costo'] * $alunni;
        $statoOrganizzata = 5;

        $istr = mysqli_prepare($conn, "INSERT INTO gitaorganizzata (IDProposta, IDUtente, DataInizio, DataFine, NumAlunni, NumDocentiAccompagnatori, CostoTot, IDStato) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($istr, "iissiidi", $idProposta, $idUtente, $dataInizio, $dataFine, $alunni, $docenti, $costoTotale, $statoOrganizzata);

        if (mysqli_stmt_execute($istr)) {
            $messaggio = "<div style='background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>Gita organizzata con successo!</div>";
        } else {
            $messaggio = "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>Errore durante l'organizzazione della gita.</div>";
        }
        mysqli_stmt_close($istr);

    } elseif ($_POST['action'] == 'elimina_proposta') {
        $idProposta = $_POST['idProposta'] ?? 0;

        $eliminaGite = mysqli_prepare($conn, "DELETE FROM gitaorganizzata WHERE IDProposta=?");
        mysqli_stmt_bind_param($eliminaGite, "i", $idProposta);
        mysqli_stmt_execute($eliminaGite);
        mysqli_stmt_close($eliminaGite);

        $istr = mysqli_prepare($conn, "DELETE FROM propostagita WHERE IDProposta=?");
        mysqli_stmt_bind_param($istr, "i", $idProposta);

        if (mysqli_stmt_execute($istr)) {
            $messaggio = "<div style='background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>Proposta eliminata con successo.</div>";
        } else {
            $messaggio = "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>Errore durante l'eliminazione della proposta.</div>";
        }
        mysqli_stmt_close($istr);
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Gite - Catalogo Proposte</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="vetrina.css">
    <link rel="stylesheet" href="style_custom.css">
    <script src="vetrina.js"></script>
</head>
<body>

    <div class="container">
        <main class="content bozze-padding">
            
            <div class="hero-section" style="display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <h2 style="margin-bottom: 1rem; color: var(--blue-700);">Catalogo Proposte</h2>
                    <p>Elenco delle mete approvate dalla Commissione. Scegli una proposta per organizzare la gita.</p>
                </div>
                <div>
                    <button class="button" id="btnNuova">Nuova Proposta</button>
                </div>
            </div>

            <?php echo $messaggio; ?>

            <div class="table-section" style="margin-top: 2rem;">
                <div class="table-container table-catalogo">
                    <table>
                        <thead>
                            <tr>
                                <th>Destinazione</th>
                                <th>Mezzo di Trasporto</th>
                                <th>Periodo</th>
                                <th>Min Part.</th>
                                <th>Max Part.</th>
                                <th>Costo Stimato</th>
                                <th>Modifica</th>
                                <th>Elimina</th>
                                <th>Azione</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $query = "SELECT p.* FROM propostagita p JOIN gitaorganizzata g ON p.IDProposta = g.IDProposta WHERE g.IDStato = 2 ORDER BY p.IDProposta DESC";
                                $result = mysqli_query($conn, $query);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['Destinazione']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['MezzoDiTrasporto']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Periodo']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['MinPartecipanti']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['MaxPartecipanti']) . "</td>";
                                        echo "<td>€ " . number_format($row['Costo'], 2, ',', '.') . "</td>";
                                        echo "<td><button class='xs outline btn-modifica' data-id='".$row['IDProposta']."'>Modifica</button></td>";
                                        echo "<td><button class='xs cancel btn-elimina' data-id='".$row['IDProposta']."'>Elimina</button></td>";
                                        echo "<td><button class='xs btn-organizza' data-id='".$row['IDProposta']."' data-dest='".htmlspecialchars($row['Destinazione'])."'>Organizza</button></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='9' style='text-align:center;'>Nessuna proposta presente.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <div class="modal-overlay hidden" id="modalOverlay">
            <div class="modal wide-modal">
                <div class="modal-header">
                    <h3 id="modalTitle">Nuova Proposta di Gita</h3>
                    <button class="close-btn" id="closeModal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formNuovaProposta" class="form-grid" method="POST" action="catalogo.php">
                        <input type="hidden" name="action" id="formAction" value="nuova_proposta">
                        <input type="hidden" name="idProposta" id="formIdProposta" value="">
                        <div class="form-group">
                            <label for="destinazione">Destinazione</label>
                            <input type="text" id="destinazione" name="destinazione" placeholder="es. Parigi" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="mezzo">Mezzo di Trasporto</label>
                            <select id="mezzo" name="mezzo">
                                <option value="Autobus">Autobus GT</option>
                                <option value="Treno">Treno Alta Velocità</option>
                                <option value="Aereo">Aereo</option>
                                <option value="Nave">Nave / Traghetto</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="periodo">Periodo Ideale</label>
                            <input type="text" id="periodo" name="periodo" placeholder="es. Aprile 2026" required>
                        </div>

                        <div class="form-group">
                            <label for="costo">Costo Stimato (€)</label>
                            <input type="number" step="0.01" id="costo" name="costo" placeholder="0" required>
                        </div>

                        <div class="form-group">
                            <label for="minPart">Minimo Partecipanti</label>
                            <input type="number" id="minPart" name="minPart" placeholder="es. 15" required>
                        </div>

                        <div class="form-group">
                            <label for="maxPart">Massimo Partecipanti</label>
                            <input type="number" id="maxPart" name="maxPart" placeholder="es. 30" required>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="button cancel" id="cancelModal">Annulla</button>
                    <button class="button" type="submit" form="formNuovaProposta" id="submitModalBtn">Registra Proposta</button>
                </div>
            </div>
        </div>

        <div class="modal-overlay hidden" id="modalDeleteOverlay">
            <div class="modal">
                <div class="modal-header">
                    <h3>Conferma Eliminazione</h3>
                    <button class="close-btn" id="closeDeleteModal">&times;</button>
                </div>
                <div class="modal-body">
                    <p style="margin-bottom: 20px;">Sei sicuro di voler eliminare questa proposta? L'operazione non può essere annullata.</p>
                </div>
                <div class="modal-footer">
                    <form id="formEliminaProposta" method="POST" action="catalogo.php" style="display: flex; gap: 10px; width: 100%; justify-content: flex-end; margin: 0;">
                        <input type="hidden" name="action" value="elimina_proposta">
                        <input type="hidden" name="idProposta" id="formDeleteIdProposta" value="">
                        <button type="button" class="button outline" id="cancelDeleteModal">Annulla</button>
                        <button type="submit" class="button cancel">Sì, Elimina</button>
                    </form>
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

        <div class="modal-overlay hidden" id="modalOrganizza">
            <div class="modal wide-modal">
                <div class="modal-header">
                    <h3 id="titoloOrganizza">Organizza Gita</h3>
                    <button class="close-btn" id="chiudiOrganizza">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formOrganizza" class="form-grid" method="POST" action="catalogo.php">
                        <input type="hidden" name="action" value="organizza_gita">
                        <input type="hidden" name="idProposta" id="organizzaIdProposta" value="">
                        <div class="form-group">
                            <label for="dataInizio">Data Inizio</label>
                            <input type="date" id="dataInizio" name="dataInizio" required>
                        </div>
                        <div class="form-group">
                            <label for="dataFine">Data Fine</label>
                            <input type="date" id="dataFine" name="dataFine" required>
                        </div>
                        <div class="form-group">
                            <label for="alunni">Numero Alunni</label>
                            <input type="number" id="alunni" name="alunni" placeholder="es. 30" required>
                        </div>
                        <div class="form-group">
                            <label for="docenti">Numero Docenti</label>
                            <input type="number" id="docenti" name="docenti" placeholder="es. 3" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="button cancel" id="annullaOrganizza">Annulla</button>
                    <button class="button" type="submit" form="formOrganizza">Conferma</button>
                </div>
            </div>
        </div>

    <script>
        var modale = document.getElementById('modalOverlay');
        var btnApri = document.getElementById('btnNuova');
        var btnChiudi = document.getElementById('closeModal');
        var btnAnnulla = document.getElementById('cancelModal');
        var modulo = document.getElementById('formNuovaProposta');
        var titolo = document.getElementById('modalTitle');
        var btnInvia = document.getElementById('submitModalBtn');
        var formAction = document.getElementById('formAction');
        var formIdProposta = document.getElementById('formIdProposta');

        btnApri.addEventListener('click', function() {
            modulo.reset();
            formAction.value = 'nuova_proposta';
            formIdProposta.value = '';
            titolo.innerText = "Nuova Proposta di Gita";
            btnInvia.innerText = "Registra Proposta";
            modale.classList.remove('hidden');
        });

        var listaModifica = document.querySelectorAll('.btn-modifica');
        for (var i = 0; i < listaModifica.length; i++) {
            listaModifica[i].addEventListener('click', function() {
                var riga = this.closest('tr');
                var celle = riga.querySelectorAll('td');

                document.getElementById('destinazione').value = celle[0].innerText;
                document.getElementById('periodo').value = celle[2].innerText;
                document.getElementById('minPart').value = celle[3].innerText;
                document.getElementById('maxPart').value = celle[4].innerText;

                var costoTesto = celle[5].innerText.replace('€', '').trim().replace(',', '.');
                document.getElementById('costo').value = Number(costoTesto);

                var selectMezzo = document.getElementById('mezzo');
                var valMezzo = celle[1].innerText;
                for (var j = 0; j < selectMezzo.options.length; j++) {
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