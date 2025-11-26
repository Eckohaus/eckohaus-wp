/**
 * Plotly Renderer — normalised for Eckohaus JSON format
 */

const EckohausPlotlyRenderer = {

    render(data) {
        const container = document.getElementById("eckohaus-vol-container");

        // Normalise: convert [{x,y,z}...] → separate arrays for Plotly
        let xs = [];
        let ys = [];
        let zs = [];

        if (Array.isArray(data.values)) {
            data.values.forEach(p => {
                xs.push(p.x);
                ys.push(p.y);
                zs.push(p.z);
            });
        }

        const volume = {
            type: "scatter3d",
            mode: "markers",
            x: xs,
            y: ys,
            z: zs,
            marker: {
                size: 3,
                color: zs,
                colorscale: "Viridis"
            }
        };

        Plotly.newPlot(container, [volume], {
            scene: {
                aspectmode: "cube"
            }
        });
    }
};
