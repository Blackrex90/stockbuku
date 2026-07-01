---
title: chore: upgrade Chart.js to v4 and convert demo scripts
---

This pull request upgrades Chart.js usage in the repository from v2 -> v4 and converts demo scripts to the Chart.js v4 API.

Summary
- Replaced CDN references to Chart.js v2 with Chart.js v4 via jsDelivr
- Added chartjs-adapter-date-fns for time scale support
- Converted assets/demo/chart-*-demo.js to Chart.js v4 API with element guards
- Converted inline Pendapatan chart to Chart.js v4 and moved it to assets/js/pendapatan-chart-v4.js
- Added UPGRADE-CHARTJS.md with migration notes and testing steps

Files changed (high level)
- assets/demo/chart-area-demo.js
- assets/demo/chart-bar-demo.js
- assets/demo/chart-pie-demo.js
- assets/js/pendapatan-chart-v4.js
- index.php, pengirim.php, pembeli.php, penerbit.php, detail_pembeli.php, detail_pengirim.php, privacy.php (CDN updates)
- UPGRADE-CHARTJS.md

Testing checklist
- [ ] Pull branch and open the site locally
- [ ] Visit index.php, pendapatan.php, and pages that include charts
- [ ] Confirm charts render and there are no Chart.js console errors
- [ ] Confirm tooltips, responsive behavior, and time axis (if present) work as expected

Notes
- Adapter added: chartjs-adapter-date-fns. If you prefer moment or luxon, switch adapter accordingly.
- If any page still expects Chart.js v2, let me know and I can keep that page on legacy script while migrating others.
