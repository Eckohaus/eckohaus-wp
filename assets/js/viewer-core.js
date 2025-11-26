/**
 * Eckohaus Volumetric Viewer — Core Dispatch (Updated for XYZ point data)
 * MIT Licence applies to software code only.
 */

document.addEventListener("DOMContentLoaded", () => {
    const cfg = window.EckohausVolConfig;
    if (!cfg || !cfg.url) return;

    fetch(cfg.url)
        .then(res => res.json())
        .then(rawData => {

            // ---------------------------------------------
            // 1. Detect XYZ coordinate format
            // ---------------------------------------------
            let data = rawData;

            if (rawData.values && Array.isArray(rawData.values)) {
                // Convert {x, y, z} objects → arrays for rendering
                const xs = rawData.values.map(v => v.x ?? 0);
                const ys = rawData.values.map(v => v.y ?? 0);
                const zs = rawData.values.map(v => v.z ?? 0);

                data = {
                    type: "pointcloud",
                    x: xs,
                    y: ys,
                    z: zs,
                    size: rawData.size || [0, 0, 0],
                    info: rawData.info || "XYZ coordinate dataset"
                };
            }

            // ---------------------------------------------
            // 2. Dispatch to renderer
            // ---------------------------------------------
            if (cfg.renderer === "three" && window.EckohausThreeRenderer) {
                EckohausThreeRenderer.render(data);
            } else if (window.EckohausPlotlyRenderer) {
                EckohausPlotlyRenderer.render(data);
            } else {
                console.error("No renderer found.");
                document.getElementById("eckohaus-vol-container").innerHTML =
                    "<p>No available renderer.</p>";
            }
        })
        .catch(err => {
            console.error("Viewer error:", err);
            document.getElementById("eckohaus-vol-container").innerHTML =
                "<p>Error loading volumetric dataset.</p>";
        });
});
