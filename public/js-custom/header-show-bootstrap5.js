document.addEventListener("DOMContentLoaded", function () {
    // Cari semua elemen dengan data-toggle="dropdown"
    var dropdownToggles = document.querySelectorAll('[data-toggle="dropdown"]');

    dropdownToggles.forEach(function (triggerEl) {
        // Pastikan elemen sudah punya kelas dropdown-toggle
        if (!triggerEl.classList.contains("dropdown-toggle")) {
            triggerEl.classList.add("dropdown-toggle");
        }

        // Event handler untuk toggle dropdown
        triggerEl.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();

            var dropdownMenu = triggerEl.nextElementSibling;
            if (
                dropdownMenu &&
                dropdownMenu.classList.contains("dropdown-menu")
            ) {
                dropdownMenu.classList.toggle("show");

                var expanded =
                    triggerEl.getAttribute("aria-expanded") === "true";
                triggerEl.setAttribute("aria-expanded", !expanded);

                // Update posisi dropdown dengan Popper.js
                if (typeof bootstrap !== "undefined") {
                    new bootstrap.Popper(triggerEl, dropdownMenu, {
                        placement: "bottom-start",
                    });
                }
            }
        });
    });

    // Tutup dropdown saat klik di luar
    document.addEventListener("click", function (e) {
        dropdownToggles.forEach(function (triggerEl) {
            var dropdownMenu = triggerEl.nextElementSibling;

            if (
                dropdownMenu &&
                dropdownMenu.classList.contains("dropdown-menu") &&
                dropdownMenu.classList.contains("show")
            ) {
                if (
                    !triggerEl.contains(e.target) &&
                    !dropdownMenu.contains(e.target)
                ) {
                    dropdownMenu.classList.remove("show");
                    triggerEl.setAttribute("aria-expanded", "false");
                }
            }
        });
    });
});
