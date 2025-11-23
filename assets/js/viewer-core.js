/**
 * Eckohaus Volumetric Viewer — Core Dispatch
 * MIT Licence applies to software code only.
 */

document.addEventListener("DOMContentLoaded", () => {
    const cfg = window.EckohausVolConfig;
    if (!cfg || !cfg.url) return;

    fetch(cfg.url)
        .then(res => res.json())
        .then(data => {
            if (cfg.renderer === "three") {
                EckohausThreeRenderer.render(data);
            } else {
                EckohausPlotlyRenderer.render(data);
            }
        })
        .catch(err => {
            console.error("Viewer error:", err);
            document.getElementById("eckohaus-vol-container").innerHTML =
                "<p>Error loading volumetric dataset.</p>";
        });
});
