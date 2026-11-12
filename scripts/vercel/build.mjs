import fs from 'node:fs';
import { createRequire } from 'node:module';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const SCRIPT_DIR = path.dirname(fileURLToPath(import.meta.url));
const ROOT = path.resolve(SCRIPT_DIR, '..', '..');
const OUTPUT_DIR = path.join(ROOT, '.vercel', 'output');
const FUNCTION_DIR = path.join(OUTPUT_DIR, 'functions', 'api', 'php.func');
const STATIC_DIR = path.join(OUTPUT_DIR, 'static');

const require = createRequire(import.meta.url);
const LIBPHP_DIR = path.dirname(require.resolve('@libphp/amazon-linux-2023-v84/package.json'));
const PHP_BIN_DIR = path.join(LIBPHP_DIR, 'native', 'php');
const PHP_LIB_DIR = path.join(LIBPHP_DIR, 'native', 'lib');

const MAX_DURATION_SECONDS = 30;

// Keep Lambda focused on backend PHP + DB schema so static media stays on CDN and well below 250 MB.
const FUNCTION_USER_DIRS = ['api', 'database'];

// Copy only public-facing static assets; never publish api/, database/, scripts/, or package manifests.
const STATIC_SKIP = new Set([
    '.git',
    '.gitignore',
    '.vercel',
    '.vscode',
    'README.md',
    'api',
    'database',
    'node_modules',
    'package-lock.json',
    'package.json',
    'scripts',
    'vercel.json',
]);

const PHP_INI_OVERRIDES = [
    '',
    '; scripts/vercel/build.mjs overrides',
    'display_errors = 0',
    'log_errors = 1',
    'error_reporting = E_ALL & ~E_DEPRECATED',
    'upload_max_filesize = 8M',
    'post_max_size = 10M',
    '',
].join('\n');

const ROUTES = [
    // Redirects first so relative navigation/asset links resolve cleanly under trailing slashes.
    { src: '^/dashboard$', headers: { Location: '/dashboard/' }, status: 307 },
    { src: '^/integrated$', headers: { Location: '/integrated/' }, status: 307 },
    { src: '^/kelola-konten(?:\\.html)?$', headers: { Location: '/dashboard/kelola-konten.html' }, status: 307 },
    { src: '^/api/setup_db\\.php$', headers: { Location: '/api/konten.php?action=setup' }, status: 307 },

    // Route all dynamic PHP endpoints through the single Node ESM PHP launcher (/api/php).
    { src: '^/aktivitas/(tugas_php_[a-zA-Z0-9_]+\\.php)$', dest: '/api/php?__vercel_php_target=/api/$1' },
    { src: '^/api/([a-zA-Z0-9_]+\\.php)$', dest: '/api/php?__vercel_php_target=/api/$1' },

    // Clean URL rewrites before filesystem check so Vercel serves matching HTML files statically.
    { src: '^/dashboard/$', dest: '/dashboard/index.html' },
    { src: '^/integrated/$', dest: '/integrated/index.html' },
    {
        src: '^/dashboard/(kelola-konten|section-hero|acara|section-penutup|galeri|our-story|pengantin|rsvp|tamu|login)$',
        dest: '/dashboard/$1.html',
    },

    { handle: 'filesystem' },
];

main();

function main() {
    if (process.platform !== 'linux') {
        throw new Error('Vercel PHP Build Output requires Linux binaries (run inside Vercel build container or WSL).');
    }

    fs.rmSync(OUTPUT_DIR, { recursive: true, force: true });

    buildPhpFunction();
    copyStaticAssets();
    writeJson(path.join(OUTPUT_DIR, 'config.json'), {
        version: 3,
        routes: ROUTES,
    });

    console.log(
        `Built Vercel output: PHP function ${formatMb(dirSize(FUNCTION_DIR))}, static ${formatMb(dirSize(STATIC_DIR))}.`,
    );
}

function buildPhpFunction() {
    const userDest = path.join(FUNCTION_DIR, 'user');
    fs.mkdirSync(userDest, { recursive: true });
    for (const dir of FUNCTION_USER_DIRS) {
        const src = path.join(ROOT, dir);
        if (fs.existsSync(src)) {
            fs.cpSync(src, path.join(userDest, dir), { recursive: true });
        }
    }

    const phpDest = path.join(FUNCTION_DIR, 'php');
    fs.mkdirSync(phpDest, { recursive: true });
    fs.copyFileSync(path.join(PHP_BIN_DIR, 'php'), path.join(phpDest, 'php'));
    fs.chmodSync(path.join(phpDest, 'php'), 0o755);

    const baseIni = fs.readFileSync(path.join(PHP_BIN_DIR, 'php.ini'), 'utf8');
    fs.writeFileSync(path.join(phpDest, 'php.ini'), baseIni + PHP_INI_OVERRIDES);

    fs.cpSync(path.join(PHP_BIN_DIR, 'modules'), path.join(phpDest, 'modules'), { recursive: true });
    fs.cpSync(PHP_LIB_DIR, path.join(FUNCTION_DIR, 'lib'), { recursive: true, verbatimSymlinks: true });

    fs.copyFileSync(path.join(SCRIPT_DIR, 'launcher.mjs'), path.join(FUNCTION_DIR, 'index.mjs'));

    const nodeMajor = process.versions.node.split('.')[0];
    writeJson(path.join(FUNCTION_DIR, '.vc-config.json'), {
        runtime: `nodejs${nodeMajor}.x`,
        handler: 'index.mjs',
        launcherType: 'Nodejs',
        shouldAddHelpers: false,
        maxDuration: MAX_DURATION_SECONDS,
    });
}

function copyStaticAssets() {
    fs.mkdirSync(STATIC_DIR, { recursive: true });
    for (const entry of fs.readdirSync(ROOT)) {
        if (STATIC_SKIP.has(entry)) {
            continue;
        }
        fs.cpSync(path.join(ROOT, entry), path.join(STATIC_DIR, entry), {
            recursive: true,
            verbatimSymlinks: true,
        });
    }
}

function writeJson(filePath, data) {
    fs.mkdirSync(path.dirname(filePath), { recursive: true });
    fs.writeFileSync(filePath, `${JSON.stringify(data, null, 2)}\n`);
}

function dirSize(dirPath) {
    let total = 0;
    for (const entry of fs.readdirSync(dirPath, { withFileTypes: true })) {
        const fullPath = path.join(dirPath, entry.name);
        if (entry.isSymbolicLink()) {
            continue;
        }
        total += entry.isDirectory() ? dirSize(fullPath) : fs.lstatSync(fullPath).size;
    }
    return total;
}

function formatMb(bytes) {
    return `${(bytes / 1024 / 1024).toFixed(1)} MB`;
}

