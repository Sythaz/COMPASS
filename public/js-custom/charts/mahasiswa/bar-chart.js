/**
 * Modul Reusable Morris Bar Chart
 */
const MorrisBarChart = (function () {
    const instances = {}; // Simpan instance chart

    /**
     * Fungsi utama untuk inisialisasi chart
     * @param {string} elementId - ID elemen DOM
     * @param {Array} data - Data chart
     * @param {Object} options - Konfigurasi tambahan
     */
    function init(elementId, data, options = {}) {
        const defaultOptions = {
            xkey: "y",
            ykeys: ["a", "b", "c", "d"], // Updated to include 4 values
            labels: ["Nilai A", "Nilai B", "Nilai C", "Nilai D"], // Updated labels
            barColors: ["#7571F9", "#F97575", "#75F9A1", "#F9E775"], // Updated colors
            hideHover: "auto",
            gridLineColor: "#eaeaea",
            resize: true,
            parseTime: false,
            barSizeRatio: 0.3
        };

        const config = Object.assign({}, defaultOptions, options);

        // Validasi elemen
        const element = document.getElementById(elementId);
        if (!element) {
            console.error(`Elemen #${elementId} tidak ditemukan.`);
            return;
        }

        // Tambahkan data ke konfig
        config.element = elementId;
        config.data = data;

        // Inisialisasi Morris Bar Chart
        instances[elementId] = new Morris.Bar(config);

        // Event resize
        window.addEventListener("resize", () => {
            setTimeout(() => {
                instances[elementId].redraw();
            }, 300);
        });
    }

    return {
        init,
    };
})();

