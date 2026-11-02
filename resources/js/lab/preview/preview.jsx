import { createRoot } from 'react-dom/client'
import { applyStudioTheme, readStudioTheme } from '../../studio-theme'
import { PreviewApp } from './PreviewApp'

applyStudioTheme()
window.studioTheme = {
    apply: applyStudioTheme,
    read: readStudioTheme,
}

const rootEl = document.getElementById('lab-preview-root')

if (rootEl) {
    /** @type {import('react-dom/client').Root|null} */
    let root = window.__studioLabPreviewRoot ?? null
    if (! root) {
        root = createRoot(rootEl)
        window.__studioLabPreviewRoot = root
    }
    root.render(<PreviewApp />)
}
