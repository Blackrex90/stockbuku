# Upgrade Chart.js to v4

This branch upgrades Chart.js usage across the repository from v2 -> v4 and converts demo scripts to the Chart.js v4 API.

Changes included:
- Replace CDN references to Chart.js v2 with Chart.js v4 via jsDelivr
- Add chartjs-adapter-date-fns for time scale support
- Convert assets/demo/*.js to Chart.js v4 API
- Convert inline Pendapatan chart to v4 and move to assets/js/pendapatan-chart-v4.js

Testing steps
1. Pull the branch: git fetch && git checkout upgrade/chartjs-v4
2. Open the app and visit pages that include charts (index, pendapatan, etc). Confirm charts render and there are no console errors.
3. If you use other time adapters (moment/luxon), adjust adapter includes accordingly.

Notes
- If anything should remain on Chart.js v2 for compatibility reasons, we can selectively exclude files.
