/**
 * Compiled preview used when Vite never calls listen().
 * Bundles the guest project with esbuild-wasm on the main thread and serves
 * it from the Lab proxy so the iframe and the private preview tab get a URL.
 * Tailwind is compiled in the page by the browser runtime, so the guest
 * oxide/lightningcss workers cannot pin the cover on "warming up".
 */

import { getEsbuild } from '../runtime/src/helpers/esbuild-engine.ts'

export const COMPILED_PREVIEW_PORT = 4173

const ENTRY_CANDIDATES = ['/src/main.jsx', '/src/main.tsx', '/src/main.js', '/src/main.ts']
const CSS_CANDIDATES = ['/src/index.css', '/src/styles.css', '/src/app.css']
const FILE_EXT = ['', '.js', '.mjs', '.cjs', '.jsx', '.ts', '.tsx', '.json']

/** @type {{ html: string, js: Uint8Array }|null} */
let snapshot = null
/** @type {string|null} */
let registeredFor = null

export function resetCompiledPreview() {
    snapshot = null
    registeredFor = null
}

/**
 * @param {any} pod
 * @param {string} path
 */
async function exists(pod, path) {
    if (typeof pod?.fs?.exists === 'function') {
        try {
            return Boolean(await pod.fs.exists(path))
        } catch {
            return false
        }
    }
    try {
        await pod.fs.readFile(path)
        return true
    } catch {
        return false
    }
}

/**
 * @param {any} pod
 * @param {string} path
 */
async function readBytes(pod, path) {
    const raw = await pod.fs.readFile(path)
    if (typeof raw === 'string') return new TextEncoder().encode(raw)
    if (raw instanceof Uint8Array) return raw
    if (raw?.buffer) return new Uint8Array(raw.buffer, raw.byteOffset || 0, raw.byteLength || raw.length)
    return new TextEncoder().encode(String(raw ?? ''))
}

/**
 * @param {any} pod
 * @param {string} path
 */
async function readText(pod, path) {
    return new TextDecoder().decode(await readBytes(pod, path))
}

/**
 * @param {string} spec
 */
function stripQuery(spec) {
    const cut = spec.split('?')[0].split('#')[0]
    return cut
}

/**
 * @param {string} importer
 * @param {string} spec
 */
function joinRelative(importer, spec) {
    const dir = importer ? importer.replace(/\/[^/]*$/, '') : ''
    const joined = spec.startsWith('/') ? spec : `${dir}/${spec}`
    const parts = []
    for (const part of joined.split('/')) {
        if (! part || part === '.') continue
        if (part === '..') parts.pop()
        else parts.push(part)
    }
    return `/${parts.join('/')}`
}

/**
 * @param {any} value
 * @returns {string|null}
 */
function pickExport(value) {
    if (! value) return null
    if (typeof value === 'string') return value
    if (typeof value !== 'object') return null
    for (const key of ['browser', 'default', 'import', 'module']) {
        if (value[key]) {
            const next = pickExport(value[key])
            if (next) return next
        }
    }
    return null
}

/**
 * @param {any} pod
 * @param {string} path
 */
async function resolveFile(pod, path) {
    const base = path.replace(/\/+$/, '') || '/'
    for (const ext of FILE_EXT) {
        const candidate = `${base}${ext}`
        if (await exists(pod, candidate)) return candidate
    }
    for (const ext of ['.js', '.mjs', '.jsx', '.ts', '.tsx']) {
        const candidate = `${base}/index${ext}`
        if (await exists(pod, candidate)) return candidate
    }
    return null
}

/**
 * @param {any} pod
 * @param {string} spec
 * @param {string} importer
 */
async function resolveSpecifier(pod, spec, importer) {
    const clean = stripQuery(spec)
    if (! clean || clean.startsWith('node:') || clean.startsWith('data:')) return null
    if (clean.startsWith('.') || clean.startsWith('/')) {
        return resolveFile(pod, joinRelative(importer || '', clean))
    }
    const parts = clean.split('/')
    const pkgName = clean.startsWith('@') ? parts.slice(0, 2).join('/') : parts[0]
    if (! pkgName) return null
    const sub = clean.slice(pkgName.length)
    const key = ! sub ? '.' : (sub.startsWith('.') ? sub : `.${sub.startsWith('/') ? sub : `/${sub}`}`)
    let pkg
    try {
        pkg = JSON.parse(await readText(pod, `/node_modules/${pkgName}/package.json`))
    } catch {
        return null
    }
    let target = null
    if (typeof pkg.exports === 'string' && ! sub) {
        target = pkg.exports
    } else if (pkg.exports && typeof pkg.exports === 'object') {
        target = pickExport(pkg.exports[key] || pkg.exports[`${key}.js`])
    }
    if (! target && ! sub) target = pkg.module || pkg.main || 'index.js'
    if (! target && sub) target = sub.replace(/^\//, '')
    if (! target) return null
    const full = `/node_modules/${pkgName}/${String(target).replace(/^\.\//, '')}`
    return resolveFile(pod, full)
}

/**
 * @param {any} pod
 */
async function findEntry(pod) {
    for (const path of ENTRY_CANDIDATES) {
        if (await exists(pod, path)) return path
    }
    return null
}

/**
 * @param {any} pod
 * @param {string|null} entry
 */
async function findCss(pod, entry) {
    for (const path of CSS_CANDIDATES) {
        if (await exists(pod, path)) return path
    }
    if (! entry) return null
    try {
        const text = await readText(pod, entry)
        const match = text.match(/import\s+['"](\.\/[^'"]+\.css)['"]/)
        if (! match?.[1]) return null
        const dir = entry.slice(0, entry.lastIndexOf('/')) || '/src'
        const resolved = `${dir}/${match[1].replace(/^\.\//, '')}`.replace(/\/+/g, '/')
        if (await exists(pod, resolved)) return resolved
    } catch {
        /* ignore */
    }
    return null
}

/**
 * @param {string} path
 */
function loaderFor(path) {
    if (path.endsWith('.jsx')) return 'jsx'
    if (path.endsWith('.tsx')) return 'tsx'
    if (path.endsWith('.ts')) return 'ts'
    if (path.endsWith('.json')) return 'json'
    return 'js'
}

/**
 * @param {string} source
 * @param {boolean} projectFile
 */
function rewriteSource(source, projectFile) {
    if (! projectFile) return source
    return source
        .replace(/import\.meta\.env\.DEV/g, 'true')
        .replace(/import\.meta\.env\.PROD/g, 'false')
        .replace(/import\.meta\.env\.MODE/g, '"development"')
        .replace(/import\.meta\.env/g, '({DEV:true,PROD:false,MODE:"development"})')
}

/**
 * @param {any} pod
 */
function vfsPlugin(pod) {
    return {
        name: 'lab-vfs',
        setup(build) {
            build.onResolve({ filter: /.*/ }, async (args) => {
                const spec = stripQuery(args.path || '')
                if (spec.endsWith('.css') || spec.includes('.css?')) {
                    return { path: spec, namespace: 'css-stub' }
                }
                if (spec.startsWith('node:') || spec === 'fs' || spec === 'path' || spec === 'url') {
                    return { path: spec, namespace: 'node-stub' }
                }
                const resolved = await resolveSpecifier(pod, spec, args.importer || '')
                if (! resolved) {
                    return { errors: [{ text: `Cannot resolve ${args.path}` }] }
                }
                return { path: resolved, namespace: 'vfs' }
            })
            build.onLoad({ filter: /.*/, namespace: 'css-stub' }, () => ({
                contents: '',
                loader: 'js',
            }))
            build.onLoad({ filter: /.*/, namespace: 'node-stub' }, () => ({
                contents: 'export default {}',
                loader: 'js',
            }))
            build.onLoad({ filter: /.*/, namespace: 'vfs' }, async (args) => {
                const text = await readText(pod, args.path)
                const projectFile = ! args.path.includes('/node_modules/')
                return {
                    contents: rewriteSource(text, projectFile),
                    loader: loaderFor(args.path),
                }
            })
        },
    }
}

/**
 * @param {string} css
 */
function tailwindStyle(css) {
    let source = String(css || '').trim()
    if (! /@import\s+['"]tailwindcss['"]/.test(source) && ! /@tailwind\s+/.test(source)) {
        source = `@import "tailwindcss";\n${source}`
    }
    return source.replace(/<\/style/gi, '<\\/style')
}

/**
 * Absolute virtual URL so the script still hits this server after the
 * service worker strips /__virtual__/{id}/{port} from the document location.
 *
 * @param {string} html
 * @param {string} css
 * @param {string} instanceId
 */
function buildDocument(html, css, instanceId) {
    const raw = String(html || '<!doctype html><html><head></head><body><div id="root"></div></body></html>')
    const stripped = raw
        .replace(/<script\b[^>]*>[\s\S]*?<\/script>/gi, '')
        .replace(/<link\b[^>]*rel=["']stylesheet["'][^>]*>/gi, '')
    const script = `/__virtual__/${instanceId}/${COMPILED_PREVIEW_PORT}/assets/app.js`
    const inject = [
        '<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>',
        `<style type="text/tailwindcss">${tailwindStyle(css)}</style>`,
        `<script type="module" src="${script}"></script>`,
    ].join('\n')
    if (stripped.includes('</head>')) return stripped.replace('</head>', `${inject}\n</head>`)
    return `${inject}\n${stripped}`
}

/**
 * @param {string} url
 */
function requestPath(url) {
    const raw = String(url || '/')
    let path = raw.split('?')[0].split('#')[0]
    try {
        if (/^https?:/i.test(path)) path = new URL(path).pathname
    } catch {
        /* keep the raw path */
    }
    const marked = path.match(/\/__(?:virtual|preview)__\/[^/]+\/\d+(\/.*)?$/)
    if (marked) path = marked[1] || '/'
    if (! path.startsWith('/')) path = `/${path}`
    return path
}

/**
 * @param {string} path
 */
function contentType(path) {
    if (path.endsWith('.js') || path.endsWith('.mjs')) return 'text/javascript; charset=utf-8'
    if (path.endsWith('.css')) return 'text/css; charset=utf-8'
    if (path.endsWith('.svg')) return 'image/svg+xml'
    if (path.endsWith('.json')) return 'application/json; charset=utf-8'
    if (path.endsWith('.png')) return 'image/png'
    if (path.endsWith('.jpg') || path.endsWith('.jpeg')) return 'image/jpeg'
    if (path.endsWith('.webp')) return 'image/webp'
    if (path.endsWith('.gif')) return 'image/gif'
    if (path.endsWith('.woff2')) return 'font/woff2'
    if (path.endsWith('.html')) return 'text/html; charset=utf-8'
    return 'application/octet-stream'
}

/**
 * @param {string} method
 * @param {number} statusCode
 * @param {string} type
 * @param {Uint8Array} body
 */
function reply(method, statusCode, type, body) {
    return {
        statusCode,
        statusMessage: statusCode === 200 ? 'OK' : 'Error',
        headers: {
            'Content-Type': type,
            'Cache-Control': 'no-store',
        },
        body: method === 'HEAD' ? new Uint8Array(0) : body,
    }
}

/**
 * @param {any} pod
 */
async function compileSnapshot(pod) {
    const entry = await findEntry(pod)
    if (! entry) throw new Error('No src/main.jsx entry to preview.')
    let html = '<!doctype html><html><head></head><body><div id="root"></div></body></html>'
    if (await exists(pod, '/index.html')) html = await readText(pod, '/index.html')
    const cssPath = await findCss(pod, entry)
    const css = cssPath ? await readText(pod, cssPath) : ''
    const esbuild = await getEsbuild()
    const result = await esbuild.build({
        absWorkingDir: '/',
        bundle: true,
        format: 'esm',
        platform: 'browser',
        target: ['es2020'],
        jsx: 'automatic',
        write: false,
        logLevel: 'silent',
        define: {
            'process.env.NODE_ENV': '"development"',
        },
        stdin: {
            contents: `import ${JSON.stringify(entry)};\n`,
            sourcefile: '/__lab_entry__.js',
            loader: 'js',
        },
        plugins: [vfsPlugin(pod)],
    })
    const file = result.outputFiles?.[0]
    if (! file) throw new Error('Compiled preview produced no script.')
    const js = file.contents instanceof Uint8Array
        ? file.contents
        : new TextEncoder().encode(String(file.text || ''))
    return {
        html: buildDocument(html, css, pod.instanceId),
        js,
    }
}

/**
 * @param {any} pod
 * @returns {Promise<{ ok: boolean, port?: number, error?: string }>}
 */
export async function serveCompiledPreview(pod) {
    if (! pod?.proxy?.register || ! pod.instanceId) {
        return { ok: false, error: 'Lab runtime is not ready to serve a preview.' }
    }
    try {
        snapshot = await compileSnapshot(pod)
    } catch (err) {
        return { ok: false, error: err?.message || String(err) }
    }

    if (registeredFor !== pod.instanceId) {
        pod.proxy.register(pod.instanceId, {
            listening: true,
            address: () => ({ port: COMPILED_PREVIEW_PORT, address: '0.0.0.0', family: 'IPv4' }),
            dispatchRequest: async (method, url) => {
                const current = snapshot
                const verb = String(method || 'GET').toUpperCase()
                if (! current) {
                    return reply(verb, 503, 'text/plain; charset=utf-8', new TextEncoder().encode('Preview is compiling.'))
                }
                const path = requestPath(url)
                if (path === '/' || path === '/index.html') {
                    return reply(verb, 200, 'text/html; charset=utf-8', new TextEncoder().encode(current.html))
                }
                if (path === '/assets/app.js') {
                    return reply(verb, 200, 'text/javascript; charset=utf-8', current.js)
                }
                const candidates = [path, `/public${path}`, `/src${path}`]
                for (const candidate of candidates) {
                    if (! await exists(pod, candidate)) continue
                    try {
                        const bytes = await readBytes(pod, candidate)
                        return reply(verb, 200, contentType(candidate), bytes)
                    } catch {
                        /* try the next location */
                    }
                }
                return reply(verb, 404, 'text/plain; charset=utf-8', new TextEncoder().encode('Not found'))
            },
        }, COMPILED_PREVIEW_PORT)
        registeredFor = pod.instanceId
    }

    return { ok: true, port: COMPILED_PREVIEW_PORT }
}
