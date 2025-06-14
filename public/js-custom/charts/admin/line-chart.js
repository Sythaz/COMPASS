// charts/line-chart.js
/**
 * Modul untuk menangani Flot Line Chart
 * @module LineChart
 */
const LineChart = (function () {
    /**
     * Buat data format Flot dari labels dan values
     * @param {Array} labels - Label untuk sumbu x
     * @param {Array} values - Nilai data
     * @return {Array} Data dalam format Flot
     */
    function prepareFlotData(labels, values) {
        return labels.map((label, i) => [i + 1, values[i]]);
    }

    /**
     * Render line chart
     * @param {Object} config - Konfigurasi chart
     * @param {Array} config.labels - Label untuk sumbu x
     * @param {Array} config.values - Nilai data
     * @param {string} config.label - Label untuk series
     * @param {string} config.color - Warna untuk series
     * @param {string} elementId - ID elemen container (default: 'flotLine')
     */
    function renderChart(config, elementId = "flotLine") {
        try {
            const {
                labels,
                values,
                label = "Data",
                color = "#7571F9",
            } = config;

            if (!labels || !values || !labels.length || !values.length) {
                console.error("Data tidak valid untuk line chart");
                return;
            }

            const $container = $("#" + elementId);

            if ($container.length === 0) {
                console.error("Container chart tidak ditemukan: " + elementId);
                return;
            }

            // Bersihkan container
            $container.empty();

            // Siapkan data untuk chart
            const flotData = prepareFlotData(labels, values);

            // Inisialisasi Flot Line Chart
            const plot = $.plot(
                $container,
                [
                    {
                        data: flotData,
                        label: label,
                        color: color,
                    },
                ],
                {
                    series: {
                        lines: {
                            show: true,
                            lineWidth: 2,
                            fill: true,
                            fillColor: {
                                colors: [
                                    {
                                        opacity: 0.05,
                                    },
                                    {
                                        opacity: 0.01,
                                    },
                                ],
                            },
                        },
                        points: {
                            show: true,
                            radius: 3,
                            fill: true,
                            fillColor: "#fff",
                            stroke: color,
                            strokeWidth: 2,
                        },
                        shadowSize: 0,
                    },
                    legend: {
                        show: true,
                        noColumns: 1,
                        position: "nw",
                    },
                    grid: {
                        hoverable: true,
                        clickable: true,
                        borderColor: "#ddd",
                        borderWidth: 0,
                        labelMargin: 5,
                        backgroundColor: "transparent",
                    },
                    yaxis: {
                        min: 0,
                        max: Math.max(...values) + 2,
                        tickColor: "rgba(0,0,0,0.05)",
                        font: {
                            size: 10,
                            color: "#999",
                        },
                    },
                    xaxis: {
                        ticks: labels.map((val, idx) => [idx + 1, val]),
                        tickColor: "rgba(0,0,0,0)",
                        font: {
                            size: 10,
                            color: "#999",
                        },
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: "'%s': %y",
                        shifts: {
                            x: -30,
                            y: -50,
                        },
                    },
                }
            );

            // Simpan instance chart untuk referensi nanti
            if (!window.lineCharts) window.lineCharts = {};
            window.lineCharts[elementId] = plot;

            // Tambahkan tooltip
            $container.bind("plothover", function (event, pos, item) {
                if (item) {
                    const value = item.datapoint[1];
                    const seriesLabel = item.series.label;
                    const xIndex = item.dataIndex;
                    const xLabel = labels[xIndex];

                    $("#tooltip").remove();
                    showTooltip(
                        pos.pageX,
                        pos.pageY,
                        seriesLabel + " (" + xLabel + "): " + value
                    );
                } else {
                    $("#tooltip").remove();
                }
            });

            return plot;
        } catch (error) {
            console.error("Error rendering line chart:", error);
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
     * @param {Object} config - Konfigurasi chart
     * @param {string} elementId - ID elemen container
     */
    function init(config, elementId = "flotLine") {
        // Render awal
        renderChart(config, elementId);

        // Event resize
        $(window).on("resize", function () {
            renderChart(config, elementId);
        });

        // Handler ketika sidebar toggle (jika ada)
        $(document).on("click", ".sidebar-toggle, .nav-toggle", function () {
            setTimeout(function () {
                renderChart(config, elementId);
            }, 300);
        });

        // Handler ketika tab/halaman menjadi visible
        document.addEventListener("visibilitychange", function () {
            if (document.visibilityState === "visible") {
                renderChart(config, elementId);
            }
        });
    }

    // API publik
    return {
        init: init,
        render: renderChart,
        prepareData: prepareFlotData,
    };
})();

// Export untuk CommonJS/ES6 jika diperlukan
if (typeof module !== "undefined" && module.exports) {
    module.exports = LineChart;
}
