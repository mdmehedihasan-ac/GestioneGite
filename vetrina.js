function toggleProfile(e) {
    if (e) e.stopPropagation();
    const arrow = document.querySelector(".profile-arrow");
    const modal = document.querySelector(".profile-modal");
    if (!arrow || !modal) return;

    const isOpen = arrow.classList.contains("open");
    if (isOpen) {
        arrow.classList.remove("open");
        modal.classList.add("hidden");
    } else {
        arrow.classList.add("open");
        modal.classList.remove("hidden");
    }
}

// Chiudi il dropdown profilo cliccando fuori
document.addEventListener("click", function(e) {
    const container = document.querySelector(".profile-container");
    const modal = document.querySelector(".profile-modal");
    const arrow = document.querySelector(".profile-arrow");
    if (!container || !modal) return;
    if (!container.contains(e.target)) {
        modal.classList.add("hidden");
        if (arrow) arrow.classList.remove("open");
    }
});

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
