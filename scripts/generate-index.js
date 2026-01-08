const fs = require('fs');
const path = require('path');

const buildDir = path.join(__dirname, '..', 'public', 'build');
const manifestPath = path.join(buildDir, 'manifest.json');
if (!fs.existsSync(manifestPath)) {
  console.error('manifest.json not found in public/build. Make sure `vite build` ran successfully.');
  process.exit(1);
}

const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));

// Try common entry keys
const possibleKeys = ['resources/js/app.js', '/resources/js/app.js', 'resources/css/app.css', '/resources/css/app.css'];
let entry = null;
for (const k of Object.keys(manifest)) {
  if (k.endsWith('resources/js/app.js') || k.endsWith('/resources/js/app.js') || manifest[k].isEntry) {
    entry = manifest[k];
    break;
  }
}

// fallback: first entry with .js file
if (!entry) {
  for (const v of Object.values(manifest)) {
    if (v.file && v.file.endsWith('.js')) { entry = v; break; }
  }
}

if (!entry) {
  console.error('Could not find a JS entry in manifest.json');
  process.exit(1);
}

const jsPath = path.posix.join('/build', entry.file);
const cssLinks = (entry.css || []).map(c => `<link rel="stylesheet" href="/build/${c}">`).join('\n    ');

const html = `<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Realestate</title>
    ${cssLinks}
  </head>
  <body>
    <div id="app"></div>
    <script type="module" src="${jsPath}"></script>
  </body>
</html>`;

const outPath = path.join(__dirname, '..', 'public', 'index.html');
fs.writeFileSync(outPath, html, 'utf8');
console.log('Wrote', outPath);
