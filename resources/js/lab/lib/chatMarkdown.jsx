/**
 * Lab chat markdown via Streamdown — code + math (KaTeX) + Supreme-themed mermaid.
 */

import { useEffect, useMemo, useState } from 'react'
import { Streamdown } from 'streamdown'
import { code } from '@streamdown/code'
import { math } from '@streamdown/math'
import { createMermaidPlugin } from '@streamdown/mermaid'
import { buildStudioMermaidConfig, isDocumentDark } from './studioMermaid'
import 'katex/dist/katex.min.css'
import 'streamdown/styles.css'

function MermaidError({ error, retry }) {
    return (
        <div className="rounded-xl border border-studio-line bg-studio-soft px-3 py-3">
            <p className="text-xs font-medium text-studio-fg">Couldn’t render diagram</p>
            <p className="mt-1 text-[11px] leading-relaxed text-studio-muted">
                {error || 'Invalid Mermaid syntax.'}
            </p>
            {typeof retry === 'function' && (
                <button
                    type="button"
                    onClick={retry}
                    className="mt-2 text-[11px] text-studio-muted transition-colors hover:text-studio-fg"
                >
                    Retry
                </button>
            )}
        </div>
    )
}

function useThemeTick() {
    const [tick, setTick] = useState(0)

    useEffect(() => {
        if (typeof document === 'undefined') return undefined
        const root = document.documentElement
        const bump = () => setTick((n) => n + 1)
        const observer = new MutationObserver(bump)
        observer.observe(root, { attributes: true, attributeFilter: ['class', 'style', 'data-theme'] })
        return () => observer.disconnect()
    }, [])

    return tick
}

export function ChatMarkdown({ content, isStreaming = false }) {
    const source = String(content || '')
    const themeTick = useThemeTick()
    const dark = useMemo(() => isDocumentDark(), [themeTick])

    const mermaidPlugin = useMemo(
        () => createMermaidPlugin({ config: buildStudioMermaidConfig() }),
        [themeTick],
    )

    const plugins = useMemo(
        () => ({ code, math, mermaid: mermaidPlugin }),
        [mermaidPlugin],
    )

    if (! source.trim()) {
        return null
    }

    return (
        <Streamdown
            key={dark ? 'lab-md-dark' : 'lab-md-light'}
            className="lab-chat-md"
            plugins={plugins}
            isAnimating={isStreaming}
            shikiTheme={['github-light', 'github-dark']}
            lineNumbers={false}
            mermaid={{
                config: buildStudioMermaidConfig(),
                errorComponent: MermaidError,
            }}
            controls={{
                mermaid: {
                    fullscreen: true,
                    download: true,
                    copy: true,
                    panZoom: false,
                },
            }}
        >
            {source}
        </Streamdown>
    )
}
