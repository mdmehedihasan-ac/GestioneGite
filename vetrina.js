function toggleProfile() {
    const arrow = document.getElementsByClassName("profile-arrow")[0];
    const modal = document.getElementsByClassName("profile-modal")[0];
    if(arrow.classList.contains("open")) {
        arrow.classList.remove("open");
        modal.classList.add("hidden");
    } else {
        arrow.classList.add("open");
        modal.classList.remove("hidden");
    }
}

// Funzioni Modali
function openModal(modalId) {
    const modalOverlay = document.getElementById(modalId);
    if (modalOverlay) {
        modalOverlay.classList.remove("hidden");
        document.body.style.overflow = "hidden"; // Impedisci lo scorrimento dietro il modale
    }
}

function closeModal(modalId) {
    const modalOverlay = document.getElementById(modalId);
    if (modalOverlay) {
        modalOverlay.classList.add("hidden");
        document.body.style.overflow = ""; // Ripristina lo scorrimento
    }
}

// Chiudi il modale cliccando fuori
window.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.classList.add('hidden');
        document.body.style.overflow = "";
    }
});

// Inizializzazione
document.addEventListener('DOMContentLoaded', () => {
    // Aggiungi listener ai pulsanti di chiusura
    const closeButtons = document.querySelectorAll('.close-btn');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const modalOverlay = e.target.closest('.modal-overlay');
            if (modalOverlay) {
                modalOverlay.classList.add('hidden');
                document.body.style.overflow = "";
            }
        });
    });



});
