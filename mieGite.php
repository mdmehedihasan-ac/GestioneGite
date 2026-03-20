<?php 
    include('nav.php');
?>
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

    <div class="container">
        <main class="content home-padding">

            <div class="hero-section">
                <h1>Le Mie Gite</h1>
                <p>Tutte le gite che hai creato o a cui partecipi. Controlla lo stato, modifica i dettagli o elimina quelle non più necessarie.</p>
            </div>

            <div style="margin-top: 2rem;">
                <h2 style="margin-bottom: 1rem; color: var(--blue-700);">Gite create da me</h2>
                <div class="miegite-grid">

                    <div class="miegite-card"
                         data-dest="Roma - Musei Vaticani"
                         data-stato="Approvata"
                         data-classe="5AII"
                         data-inizio="15/04/2026"
                         data-fine="18/04/2026"
                         data-mezzo="Treno Alta Velocit&#224;"
                         data-alunni="45"
                         data-docenti="4"
                         data-costo="&#8364; 8.100,00"
                         data-note="Visita guidata ai Musei Vaticani, Cappella Sistina e Colosseo. Pernottamento in hotel 3 stelle.">
                        <div class="miegite-card-header">
                            <h3 class="miegite-card-title">Roma - Musei Vaticani</h3>
                            <span class="badge badge-success">Approvata</span>
                        </div>
                        <div class="miegite-card-body">
                            <div class="miegite-card-info">
                                <span><strong>Classe:</strong> 5AII</span>
                                <span><strong>Date:</strong> 15/04/2026 &#8211; 18/04/2026</span>
                                <span><strong>Mezzo:</strong> Treno Alta Velocit&#224;</span>
                                <span><strong>Partecipanti:</strong> 45 alunni, 4 docenti</span>
                                <span><strong>Costo:</strong> &#8364; 8.100,00</span>
                            </div>
                        </div>
                        <div class="miegite-card-footer">
                            <button class="xs outline btn-dettagli-gita">Dettagli</button>
                            <button class="xs outline btn-modifica-gita">Modifica</button>
                            <button class="xs cancel btn-elimina-gita">Elimina</button>
                        </div>
                    </div>

                    <div class="miegite-card"
                         data-dest="Napoli e Pompei"
                         data-stato="Inserita"
                         data-classe="4BEA"
                         data-inizio="10/05/2026"
                         data-fine="12/05/2026"
                         data-mezzo="Pullman GT"
                         data-alunni="40"
                         data-docenti="3"
                         data-costo="&#8364; 4.800,00"
                         data-note="Tour archeologico di Pompei e visita al centro storico di Napoli. Pranzo incluso.">
                        <div class="miegite-card-header">
                            <h3 class="miegite-card-title">Napoli e Pompei</h3>
                            <span class="badge badge-warning">Inserita</span>
                        </div>
                        <div class="miegite-card-body">
                            <div class="miegite-card-info">
                                <span><strong>Classe:</strong> 4BEA</span>
                                <span><strong>Date:</strong> 10/05/2026 &#8211; 12/05/2026</span>
                                <span><strong>Mezzo:</strong> Pullman GT</span>
                                <span><strong>Partecipanti:</strong> 40 alunni, 3 docenti</span>
                                <span><strong>Costo:</strong> &#8364; 4.800,00</span>
                            </div>
                        </div>
                        <div class="miegite-card-footer">
                            <button class="xs outline btn-dettagli-gita">Dettagli</button>
                            <button class="xs outline btn-modifica-gita">Modifica</button>
                            <button class="xs cancel btn-elimina-gita">Elimina</button>
                        </div>
                    </div>

                    <div class="miegite-card"
                         data-dest="CERN di Ginevra"
                         data-stato="Bozza"
                         data-classe="5CL"
                         data-inizio="20/02/2026"
                         data-fine="23/02/2026"
                         data-mezzo="Aereo"
                         data-alunni="20"
                         data-docenti="2"
                         data-costo="&#8364; 7.000,00"
                         data-note="Visita al CERN, laboratori di fisica delle particelle. Volo diretto Milano-Ginevra.">
                        <div class="miegite-card-header">
                            <h3 class="miegite-card-title">CERN di Ginevra</h3>
                            <span class="badge badge-secondary">Bozza</span>
                        </div>
                        <div class="miegite-card-body">
                            <div class="miegite-card-info">
                                <span><strong>Classe:</strong> 5CL</span>
                                <span><strong>Date:</strong> 20/02/2026 &#8211; 23/02/2026</span>
                                <span><strong>Mezzo:</strong> Aereo</span>
                                <span><strong>Partecipanti:</strong> 20 alunni, 2 docenti</span>
                                <span><strong>Costo:</strong> &#8364; 7.000,00</span>
                            </div>
                        </div>
                        <div class="miegite-card-footer">
                            <button class="xs outline btn-dettagli-gita">Dettagli</button>
                            <button class="xs outline btn-modifica-gita">Modifica</button>
                            <button class="xs cancel btn-elimina-gita">Elimina</button>
                        </div>
                    </div>

                </div>
            </div>

            <div style="margin-top: 3rem;">
                <h2 style="margin-bottom: 1rem; color: var(--blue-700);">Gite a cui partecipo</h2>
                <div class="miegite-grid">

                    <div class="miegite-card"
                         data-dest="Firenze Rinascimentale"
                         data-stato="Conclusa"
                         data-classe="5AII"
                         data-inizio="10/10/2025"
                         data-fine="12/10/2025"
                         data-mezzo="Pullman GT"
                         data-alunni="24"
                         data-docenti="2"
                         data-costo="&#8364; 3.500,00"
                         data-note="Tour della Galleria degli Uffizi, Duomo e Ponte Vecchio. Gita conclusa con successo.">
                        <div class="miegite-card-header">
                            <h3 class="miegite-card-title">Firenze Rinascimentale</h3>
                            <span class="badge badge-primary">Conclusa</span>
                        </div>
                        <div class="miegite-card-body">
                            <div class="miegite-card-info">
                                <span><strong>Classe:</strong> 5AII</span>
                                <span><strong>Date:</strong> 10/10/2025 &#8211; 12/10/2025</span>
                                <span><strong>Mezzo:</strong> Pullman GT</span>
                                <span><strong>Partecipanti:</strong> 24 alunni, 2 docenti</span>
                                <span><strong>Costo:</strong> &#8364; 3.500,00</span>
                            </div>
                        </div>
                        <div class="miegite-card-footer">
                            <button class="xs outline btn-dettagli-gita">Dettagli</button>
                        </div>
                    </div>

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
    </script>

</body>
</html>
