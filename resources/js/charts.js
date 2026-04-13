import Chart from "chart.js/auto";
import "chartjs-adapter-luxon";

const CHARTS = new Map();

window.__renderCharts = (specs) => renderCharts(specs);

function deepMerge(base, extra) {
    if (!extra || typeof extra !== "object") return base;
    if (!base || typeof base !== "object") return extra;

    if (Array.isArray(base) || Array.isArray(extra)) return extra;

    const out = { ...base };
    for (const [k, v] of Object.entries(extra)) {
        if (
            v &&
            typeof v === "object" &&
            !Array.isArray(v) &&
            out[k] &&
            typeof out[k] === "object" &&
            !Array.isArray(out[k])
        ) {
            out[k] = deepMerge(out[k], v);
        } else {
            out[k] = v;
        }
    }
    return out;
}

function keyForCanvas(canvas) {
    return (
        canvas.id ||
        `canvas@${canvas.getBoundingClientRect().x},${canvas.getBoundingClientRect().y}`
    );
}

function getCanvasById(canvasId) {
    const el = document.getElementById(canvasId);
    if (!el) return null;
    if (!(el instanceof HTMLCanvasElement)) return null;
    return el;
}

function defaultOptions() {
    return {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: "index", intersect: false },
        plugins: {
            legend: { display: true },
            title: { display: false },
            tooltip: { enabled: true },
        },
        elements: {
            line: { tension: 0, borderWidth: 2 },
            point: { radius: 2, hoverRadius: 5 },
        },
    };
}

function applyTimeScale(options, time) {
    const parser = time?.parser || "yyyy-MM-dd HH:mm:ss";

    return deepMerge(options, {
        scales: {
            x: {
                type: "time",
                time: {
                    parser,
                    tooltipFormat: parser,
                    displayFormats: {
                        minute: "HH:mm",
                        hour: "HH:mm",
                        day: "yyyy-MM-dd",
                    },
                },
                min: time?.xMin,
                max: time?.xMax,
                grid: { display: false },
                ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 10 },
            },
        },
    });
}

export function renderChart(canvas, spec) {
    const key = keyForCanvas(canvas);

    const existing = CHARTS.get(key);
    if (existing) {
        existing.destroy();
        CHARTS.delete(key);
    }

    let options = deepMerge(defaultOptions(), spec.options || {});
    if (spec.time) {
        options = applyTimeScale(options, spec.time);
    }

    const chart = new Chart(canvas, {
        type: spec.type,
        data: spec.data,
        options,
    });

    CHARTS.set(key, chart);
    return chart;
}

function renderChartById(canvasId, spec) {
    const canvas = getCanvasById(canvasId);
    if (!canvas) return null;
    return renderChart(canvas, spec);
}

export function renderCharts(specs) {
    if (!Array.isArray(specs)) return [];
    const out = [];
    for (const spec of specs) {
        if (!spec || !spec.canvasId) continue;
        const chart = renderChartById(spec.canvasId, spec);
        if (chart) out.push(chart);
    }
    return out;
}

export function autoRenderFromWindow(opts = {}) {
    const globalVarName = opts.globalVarName || "__charts";

    document.addEventListener("DOMContentLoaded", () => {
        const payload = window[globalVarName];
        if (!payload || typeof payload !== "object") return;

        const specs = payload.charts;
        if (!Array.isArray(specs)) return;

        renderCharts(specs);
    });
}

export function initCharts() {
    autoRenderFromWindow({ globalVarName: "__charts" });
}
