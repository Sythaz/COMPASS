// charts/pie-chart.js
/**
 * Modul untuk menangani Flot Pie Chart
 * @module PieChart
 */
const PieChart = (function () {
    /**
     * Formatter untuk label pada chart
     * @param {string} label - Label data
     * @param {Object} series - Data series
     * @return {string} HTML untuk label
     */
    function labelFormatter(label, series) {
        return (
            "<div style='font-size:8pt; text-align:center; padding:2px; color:black;'>" +
            label +
            "<br/>" +
            Math.round(series.percent) +
            "%</div>"
        );
    }

    /**
     * Render pie chart
     * @param {Array} chartData - Data untuk chart
     * @param {string} elementId - ID elemen container (default: 'flotPie')
     */
    function renderChart(chartData, elementId = "flotPie") {
        if (!chartData || !chartData.length) {
            console.error("Tidak ada data untuk chart");
            return;
        }

        try {
            const showLegend = $(window).width() > 530;
            const $container = $("#" + elementId);

            if ($container.length === 0) {
                console.error("Container chart tidak ditemukan: " + elementId);
                return;
            }

            const options = {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        innerRadius: 0.4,
                        label: {
                            show: true,
                            radius: 2 / 3,
                            formatter: labelFormatter,
                            threshold: 0.1,
                        },
                    },
                },
                grid: {
                    hoverable: true,
                    clickable: true,
                },
                legend: {
                    show: showLegend,
                },
            };

            // Hapus chart lama jika ada
            if (window.pieCharts && window.pieCharts[elementId]) {
                $container.empty();
            }

            // Simpan chart dalam koleksi untuk referensi nanti
            if (!window.pieCharts) window.pieCharts = {};
            window.pieCharts[elementId] = $.plot(
                $container,
                chartData,
                options
            );

            // Tambahkan tooltip jika diinginkan
            $container.bind("plothover", function (event, pos, item) {
                if (item) {
                    const percentage = Math.round(item.series.percent);
                    $("#tooltip").remove();
                    showTooltip(
                        pos.pageX,
                        pos.pageY,
                        item.series.label + ": " + percentage + "%"
                    );
                } else {
                    $("#tooltip").remove();
                }
            });
        } catch (error) {
            console.error("Error rendering pie chart:", error);
        }
    }

    /**
     * Tampilkan tooltip
     * @private
     */
    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + "</div>")
            .css({
                position: "absolute",
                display: "none",
                top: y + 5,
                left: x + 5,
                border: "1px solid #fdd",
                padding: "2px",
                "background-color": "#fee",
                opacity: 0.8,
            })
            .appendTo("body")
            .fadeIn(200);
    }

    /**
     * Inisialisasi chart dan event handlers
     * @param {Array} data - Data untuk chart
     * @param {string} elementId - ID elemen container
     */
    function init(data, elementId = "flotPie") {
        // Render awal
        renderChart(data, elementId);

        // Event resize
        $(window).on("resize", function () {
            renderChart(data, elementId);
        });

        // Handler ketika sidebar toggle (jika ada)
        $(document).on("click", ".sidebar-toggle, .nav-toggle", function () {
            setTimeout(function () {
                renderChart(data, elementId);
            }, 300);
        });
    }

    // API publik
    return {
        init: init,
        render: renderChart,
    };
})();

// Export untuk CommonJS/ES6 jika diperlukan
if (typeof module !== "undefined" && module.exports) {
    module.exports = PieChart;
}