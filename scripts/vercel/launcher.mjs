import { spawn } from 'node:child_process';
import http from 'node:http';
import net from 'node:net';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const TASK_ROOT = path.dirname(fileURLToPath(import.meta.url));
const USER_DIR = path.join(TASK_ROOT, 'user');
const PHP_DIR = path.join(TASK_ROOT, 'php');
const LIB_DIR = path.join(TASK_ROOT, 'lib');
const HOST = '127.0.0.1';
const STARTUP_TIMEOUT_MS = 10_000;
const ALLOWED_PHP_TARGET = /^\/api\/[a-zA-Z0-9_]+\.php$/;

// Hop-by-hop headers must not cross the Node <-> php-server boundary.
const HOP_BY_HOP = new Set([
    'connection',
    'expect',
    'keep-alive',
    'proxy-connection',
    'te',
    'trailer',
    'transfer-encoding',
    'upgrade',
]);

let phpServerPromise = null;

/**
 * Vercel Nodejs ESM entrypoint that bootstraps PHP's built-in server on loopback
 * and proxies whitelisted /api/*.php requests. Avoids vercel-php's broken
 * `launcher.launcher` handler string on Vercel's /opt/rust/nodejs.js bootstrap.
 */
export default async function handler(req, res) {
    const forwardPath = resolveForwardPath(req.url);
    if (!forwardPath) {
        sendJsonError(res, 404, 'Endpoint PHP tidak ditemukan.');
        return;
    }

    let port;
    try {
        port = await ensurePhpServer();
    } catch (error) {
        console.error('Failed to start PHP server:', error);
        sendJsonError(res, 500, 'Gagal menjalankan runtime PHP.');
        return;
    }

    try {
        const body = await readBody(req);
        await forward(req, res, port, forwardPath, body);
    } catch (error) {
        console.error('Failed to proxy PHP request:', error);
        if (res.headersSent) {
            res.destroy(error);
        } else {
            sendJsonError(res, 502, 'Gagal memproses request PHP.');
        }
    }
}

function resolveForwardPath(rawUrl) {
    const parsed = new URL(rawUrl || '/', `http://${HOST}`);
    const rewrittenTarget = parsed.searchParams.get('__vercel_php_target');
    parsed.searchParams.delete('__vercel_php_target');

    const target = rewrittenTarget || parsed.pathname;
    if (!ALLOWED_PHP_TARGET.test(target)) {
        return null;
    }

    const qs = parsed.searchParams.toString();
    return qs ? `${target}?${qs}` : target;
}

function ensurePhpServer() {
    if (!phpServerPromise) {
        const starting = startPhpServer(() => {
            if (phpServerPromise === starting) {
                phpServerPromise = null;
            }
        });
        phpServerPromise = starting;
        starting.catch(() => {
            if (phpServerPromise === starting) {
                phpServerPromise = null;
            }
        });
    }
    return phpServerPromise;
}

async function startPhpServer(onExit) {
    const port = await findFreePort();
    const php = spawn(
        path.join(PHP_DIR, 'php'),
        [
            '-c', path.join(PHP_DIR, 'php.ini'),
            // Override php.ini's compiled extension_dir with the bundled Lambda path.
            '-d', `extension_dir=${path.join(PHP_DIR, 'modules')}`,
            '-S', `${HOST}:${port}`,
            '-t', USER_DIR,
        ],
        {
            cwd: PHP_DIR,
            env: {
                ...process.env,
                PATH: `${PHP_DIR}:${process.env.PATH ?? ''}`,
                LD_LIBRARY_PATH: `${LIB_DIR}:/usr/lib64:/lib64:${process.env.LD_LIBRARY_PATH ?? ''}`,
                PHP_CLI_SERVER_WORKERS: process.env.PHP_CLI_SERVER_WORKERS ?? '4',
            },
            stdio: ['ignore', 'pipe', 'pipe'],
        },
    );

    php.stdout.on('data', (chunk) => console.log(chunk.toString().trimEnd()));
    php.stderr.on('data', (chunk) => console.error(chunk.toString().trimEnd()));

    let dead = false;
    const exited = new Promise((_, reject) => {
        php.once('error', (error) => {
            dead = true;
            reject(error);
        });
        php.once('exit', (code, signal) => {
            dead = true;
            onExit();
            const message = `PHP server exited (code=${code}, signal=${signal}).`;
            console.error(message);
            reject(new Error(message));
        });
    });
    exited.catch(() => {});

    try {
        await Promise.race([waitForPort(port, () => dead), exited]);
    } catch (error) {
        php.kill();
        throw error;
    }
    return port;
}

function findFreePort() {
    return new Promise((resolve, reject) => {
        const server = net.createServer();
        server.unref();
        server.once('error', reject);
        server.listen(0, HOST, () => {
            const { port } = server.address();
            server.close(() => resolve(port));
        });
    });
}

function waitForPort(port, isDead) {
    const deadline = Date.now() + STARTUP_TIMEOUT_MS;
    return new Promise((resolve, reject) => {
        const attempt = () => {
            const socket = net.connect(port, HOST);
            socket.once('connect', () => {
                socket.destroy();
                resolve();
            });
            socket.once('error', () => {
                socket.destroy();
                if (isDead()) {
                    return;
                }
                if (Date.now() > deadline) {
                    reject(new Error(`PHP server did not open port ${port} within ${STARTUP_TIMEOUT_MS}ms.`));
                    return;
                }
                setTimeout(attempt, 10);
            });
        };
        attempt();
    });
}

async function readBody(req) {
    const chunks = [];
    for await (const chunk of req) {
        chunks.push(chunk);
    }
    return Buffer.concat(chunks);
}

function forward(req, res, port, forwardPath, body) {
    const headers = {};
    for (const [name, value] of Object.entries(req.headers)) {
        if (!HOP_BY_HOP.has(name.toLowerCase())) {
            headers[name] = value;
        }
    }
    headers['content-length'] = String(body.length);

    return new Promise((resolve, reject) => {
        const upstream = http.request(
            { host: HOST, port, method: req.method, path: forwardPath, headers },
            (phpRes) => {
                res.statusCode = phpRes.statusCode ?? 502;
                for (const [name, value] of Object.entries(phpRes.headers)) {
                    if (value !== undefined && name.toLowerCase() !== 'host' && !HOP_BY_HOP.has(name.toLowerCase())) {
                        res.setHeader(name, value);
                    }
                }
                phpRes.once('error', reject);
                res.once('close', resolve);
                res.once('finish', resolve);
                phpRes.pipe(res);
            },
        );
        upstream.once('error', reject);
        upstream.end(body);
    });
}

function sendJsonError(res, status, message) {
    res.statusCode = status;
    res.setHeader('content-type', 'application/json; charset=utf-8');
    res.end(JSON.stringify({ success: false, message }));
}

