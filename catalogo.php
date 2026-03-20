<?php 
    include('nav.php');
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
    <script src="vetrina.js" defer></script>
<body>

    <div class="container">
        <main class="content home-padding">
            
            <section class="hero-section" style="display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <h1>Catalogo Proposte</h1>
                    <p>Elenco delle mete approvate dalla Commissione. Scegli una proposta per organizzare la gita.</p>
                </div>
                <div>
                    <button class="button" id="btnNuovaProposta">Nuova Proposta</button>
                </div>
            </section>

            <section class="table-section" style="margin-top: 2rem;">
                <div class="table-container">
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
                            <tr>
                                <td>Roma - Musei Vaticani</td>
                                <td>Treno Alta Velocità</td>
                                <td>Marzo - Aprile</td>
                                <td>20</td>
                                <td>50</td>
                                <td>€ 180,00</td>
                                <td><button class="xs outline btn-modifica">Modifica</button></td>
                                <td><button class="xs cancel">Elimina</button></td>
                                <td><button class="xs">Crea Gita</button></td>
                            </tr>
                            <tr>
                                <td>Napoli e Pompei</td>
                                <td>Pullman GT</td>
                                <td>Maggio</td>
                                <td>40</td>
                                <td>100</td>
                                <td>€ 120,00</td>
                                <td><button class="xs outline btn-modifica">Modifica</button></td>
                                <td><button class="xs cancel">Elimina</button></td>
                                <td><button class="xs">Crea Gita</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

        <div class="modal-overlay hidden" id="modalOverlay">
            <div class="modal wide-modal">
                <div class="modal-header">
                    <h3 id="modalTitle">Nuova Proposta di Gita</h3>
                    <button class="close-btn" id="closeModal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formNuovaProposta" class="form-grid">
                        
                        <div class="form-group">
                            <label for="destinazione">Destinazione</label>
                            <input type="text" id="destinazione" placeholder="es. Parigi">
                        </div>
                        
                        <div class="form-group">
                            <label for="mezzo">Mezzo di Trasporto</label>
                            <select id="mezzo">
                                <option value="Autobus">Autobus GT</option>
                                <option value="Treno">Treno Alta Velocità</option>
                                <option value="Aereo">Aereo</option>
                                <option value="Nave">Nave / Traghetto</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="periodo">Periodo Ideale</label>
                            <input type="text" id="periodo" placeholder="es. Aprile 2026">
                        </div>

                        <div class="form-group">
                            <label for="costo">Costo Stimato (€)</label>
                            <input type="number" id="costo" step="0.01" placeholder="0.00">
                        </div>

                        <div class="form-group">
                            <label for="minPart">Minimo Partecipanti</label>
                            <input type="number" id="minPart" placeholder="es. 15">
                        </div>

                        <div class="form-group">
                            <label for="maxPart">Massimo Partecipanti</label>
                            <input type="number" id="maxPart" placeholder="es. 30">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="button cancel" id="cancelModal">Annulla</button>
                    <button class="button" type="submit" form="formNuovaProposta" id="submitModalBtn">Registra Proposta</button>
                </div>
            </div>
        </div>

        <footer>
            <div class="footer-container">
                <div class="footer-left">
                    <p><strong>Gestione Gite Scolastiche</strong></p>
                    <p class="footer-copyright">© 2026 - Piattaforma Interna</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        const modal = document.getElementById('modalOverlay');
        const openBtn = document.getElementById('btnNuovaProposta');
        const closeBtn = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelModal');
        
        // Nuovi riferimenti per il modulo e i testi
        const form = document.getElementById('formNuovaProposta');
        const modalTitle = document.getElementById('modalTitle');
        const submitModalBtn = document.getElementById('submitModalBtn');

        // 1. APRI MODALE IN MODALITÀ "NUOVA PROPOSTA"
        openBtn.addEventListener('click', () => {
            form.reset(); // Svuota tutti i campi
            modalTitle.innerText = "Nuova Proposta di Gita";
            submitModalBtn.innerText = "Registra Proposta";
            modal.classList.remove('hidden');
        });
        
        // 2. APRI MODALE IN MODALITÀ "MODIFICA"
        const btnModificaList = document.querySelectorAll('.btn-modifica');
        
        btnModificaList.forEach(btn => {
            btn.addEventListener('click', function() {
                // Trova la riga (tr) della tabella corrispondente al bottone cliccato
                const riga = this.closest('tr');
                const celle = riga.querySelectorAll('td');

                // Estrae i testi dalle colonne della tabella
                const valDestinazione = celle[0].innerText;
                const valMezzo = celle[1].innerText;
                const valPeriodo = celle[2].innerText;
                const valMinPart = celle[3].innerText;
                const valMaxPart = celle[4].innerText;
                // Pulisce il costo (toglie € e trasforma la virgola in punto per l'input number)
                const valCosto = celle[5].innerText.replace('€', '').trim().replace(',', '.');

                // Inserisce i valori estratti nei campi di input della modale
                document.getElementById('destinazione').value = valDestinazione;
                document.getElementById('periodo').value = valPeriodo;
                document.getElementById('minPart').value = valMinPart;
                document.getElementById('maxPart').value = valMaxPart;
                document.getElementById('costo').value = parseFloat(valCosto);

                // Seleziona l'opzione corretta nel menu a tendina (select)
                const selectMezzo = document.getElementById('mezzo');
                for(let i = 0; i < selectMezzo.options.length; i++) {
                    if(selectMezzo.options[i].text.includes(valMezzo) || selectMezzo.options[i].value === valMezzo) {
                        selectMezzo.selectedIndex = i;
                        break;
                    }
                }

                // Cambia il titolo e il bottone
                modalTitle.innerText = "Modifica Proposta di Gita";
                submitModalBtn.innerText = "Salva Modifiche";

                // Mostra la modale
                modal.classList.remove('hidden');
            });
        });

        // FUNZIONI DI CHIUSURA MODALE
        const closeModal = () => modal.classList.add('hidden');
        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        window.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    </script>
</body>
</html>