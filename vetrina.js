function toggleProfile(e) {
    if (e) e.stopPropagation();
    var freccia = document.querySelector(".profile-arrow");
    var menu = document.querySelector(".profile-modal");
    if (!freccia || !menu) return;

    if (freccia.classList.contains("open")) {
        freccia.classList.remove("open");
        menu.classList.add("hidden");
    } else {
        freccia.classList.add("open");
        menu.classList.remove("hidden");
    }
}

document.addEventListener("click", function(e) {
    var contenitore = document.querySelector(".profile-container");
    var menu = document.querySelector(".profile-modal");
    var freccia = document.querySelector(".profile-arrow");
    if (!contenitore || !menu) return;
    if (!contenitore.contains(e.target)) {
        menu.classList.add("hidden");
        if (freccia) freccia.classList.remove("open");
    }
});

function openModal(idModale) {
    var modale = document.getElementById(idModale);
    if (modale) {
        modale.classList.remove("hidden");
        document.body.style.overflow = "hidden";
    }
}

function closeModal(idModale) {
    var modale = document.getElementById(idModale);
    if (modale) {
        modale.classList.add("hidden");
        document.body.style.overflow = "";
    }
}

window.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.classList.add('hidden');
        document.body.style.overflow = "";
    }
});

document.addEventListener('DOMContentLoaded', function() {
    var bottoniChiudi = document.querySelectorAll('.close-btn');
    for (var i = 0; i < bottoniChiudi.length; i++) {
        bottoniChiudi[i].addEventListener('click', function(e) {
            var modale = e.target.closest('.modal-overlay');
            if (modale) {
                modale.classList.add('hidden');
                document.body.style.overflow = "";
            }
        });
    }
});
