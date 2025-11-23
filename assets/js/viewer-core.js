/**
 * Eckohaus Volumetric Viewer
 * MIT Licence applies to software code only.
 */

document.addEventListener("DOMContentLoaded", () => {
    if (!EckohausVolData || !EckohausVolData.url) return;

    const container = document.getElementById("eckohaus-vol-container");
    container.innerHTML = "<p>Loading volumetric data…</p>";

    fetch(EckohausVolData.url)
        .then(response => response.json())
        .then(data => {
            container.innerHTML = "<p>Data loaded. 3D renderer integration coming next.</p>";
            console.log("Volumetric data:", data);
        })
        .catch(err => {
            container.innerHTML = "<p>Error loading volumetric dataset.</p>";
            console.error(err);
        });
});
