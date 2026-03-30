import "./bootstrap";
import Chart from "chart.js/auto";
import "chartjs-adapter-luxon";

function initLineChart(canvasId, title, chartDataset, options) {
    const el = document.getElementById(canvasId);
    if (!el) return;

    new Chart(el, {
        type: "line",
        data: {
            datasets: [chartDataset],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true },
                title: { display: !!title, text: title || "" },
            },
            interaction: { mode: "index", intersect: false },
            ...options,
        },
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const charts = window.__stationCharts;
    if (!charts) return;

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
        },
        scales: {
            x: {
                type: "time",
                time: {
                    parser: "yyyy-MM-dd HH:mm:ss",
                    tooltipFormat: "yyyy-MM-dd HH:mm:ss",
                    displayFormats: {
                        minute: "HH:mm",
                        hour: "HH:mm",
                    },
                },
                min: charts.xMin,
                max: charts.xMax,
                grid: { display: false },
                ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 8 },
            },
        },
        elements: {
            line: { tension: 0, borderWidth: 2 },
            point: { radius: 2, hoverRadius: 5 },
        },
    };

    initLineChart(
        "stationChartHourTemp",
        "Temperature Last 24 Hours",
        charts.hour.temp,
        {
            ...commonOptions,
            scales: {
                ...commonOptions.scales,
                y: {
                    ticks: {
                        callback: (value) =>
                            parseFloat(value).toFixed(1) + "°C",
                    },
                },
            },
        },
    );

    initLineChart(
        "stationChartHourAirPressure",
        "Air Pressure Last 24 Hours",
        charts.hour.airPressure,
        {
            ...commonOptions,
            scales: {
                ...commonOptions.scales,
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: (value) =>
                            parseFloat(value).toFixed(2) + " hPa",
                    },
                },
            },
        },
    );

    initLineChart(
        "stationChartHourWindSpeed",
        "Wind Speed Last 24 Hours",
        charts.hour.windSpeed,
        {
            ...commonOptions,
            scales: {
                ...commonOptions.scales,
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) =>
                            parseFloat(value).toFixed(1) + " m/s",
                    },
                },
            },
        },
    );

    initLineChart(
        "stationChartHourPercipation",
        "Precipitation Last 24 Hours",
        charts.hour.percipation,
        {
            ...commonOptions,
            scales: {
                ...commonOptions.scales,
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) =>
                            parseFloat(value).toFixed(2) + " mm",
                    },
                },
            },
        },
    );
});
