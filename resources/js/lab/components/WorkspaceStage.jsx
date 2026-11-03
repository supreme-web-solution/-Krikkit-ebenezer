import { useEffect, useRef } from 'react'
import { resolveGuestListenPort } from '../lib/labRuntime'
import { AppSettingsPanel } from './AppSettingsPanel'
import { ConsoleTerminal, disposeConsoleSession } from './ConsoleTerminal'
import { DatastoreDesk } from './DatastoreDesk'
import { DomInspectPanel } from './DomInspectPanel'
import { PreviewFrame } from './PreviewFrame'

function PanelPlaceholder({ title, body }) {
    return (
        <div className="flex h-full flex-col items-center justify-center gap-2 px-6 text-center">
            <p className="text-xs font-medium uppercase tracking-[0.18em] text-accent-content">
                {title}
            </p>
            <p className="max-w-sm text-sm text-studio-muted">{body}</p>
        </div>
    )
}

/**
 * Non-files workspace surfaces (console / preview / placeholders).
 * Files live in LabApp so they can flex-morph into the right rail.
 */
export function WorkspaceStage({
    panel = 'preview',
    activeTabId = null,
    consoleIds = [],
    vfs = null,
    vfsContents = null,
    projectUuid = null,
    consoleSessionLog = [],
    consoleFocusNonce = 0,
    previewLive = false,
    projectTitle = 'Preview',
    inspecting = false,
    onInspectSelect = null,
    onInspectStop = null,
    inspectedElement = null,
    onInspectClear = null,
    inspectApplyRef = null,
    onAskLab = null,
    onStartInspect = null,
    onPinPreviewEdit = null,
    autoSave = false,
    onToggleAutoSave = null,
    onConnectDatastore = null,
    onChangeDatastore = null,
    isAiStreaming = false,
    aiWriting = false,
    previewReloadPending = false,
    aiBusy = false,
    turnStatusLabel = '',
    thinkingLive = false,
}) {
    const consoleIdsRef = useRef(consoleIds)
    const vfsRef = useRef(vfs)
    vfsRef.current = vfs

    // The dev server used to start only after the Console terminal attached.
    // Preview is the default tab, and Console is display:none, so that attach
    // often never happens. The files are written and the cover stays on
    // "Dev server warming up" because nothing is listening.
    useEffect(() => {
        if (! projectUuid) return undefined
        let stopped = false
        let hungSince = 0

        const tick = () => {
            if (stopped || isAiStreaming) return
            if (resolveGuestListenPort()) {
                hungSince = 0
                return
            }
            const files = vfsRef.current?.getContents?.() || {}
            const hasPackage = Object.keys(files).some((path) => (
                path.replace(/^\/+/, '') === 'package.json' && String(files[path] || '').trim()
            ))
            if (! hasPackage) return
            void import('../lib/labAutostart').then(async (mod) => {
                if (stopped || resolveGuestListenPort()) return
                const result = await mod.runLabAutostart({
                    projectKey: projectUuid,
                    getFiles: () => vfsRef.current?.getContents?.() || {},
                })
                if (stopped || resolveGuestListenPort()) return
                if (result?.reason !== 'already-running') {
                    hungSince = 0
                    return
                }
                const now = Date.now()
                if (! hungSince) hungSince = now
                else if (now - hungSince > 20_000) {
                    hungSince = 0
                    mod.resetLabAutostart?.()
                    void mod.runLabAutostart({
                        projectKey: projectUuid,
                        getFiles: () => vfsRef.current?.getContents?.() || {},
                    })
                }
            })
        }

        tick()
        const id = window.setInterval(tick, 4000)
        return () => {
            stopped = true
            window.clearInterval(id)
        }
    }, [projectUuid, isAiStreaming])

    useEffect(() => {
        const prev = consoleIdsRef.current
        consoleIdsRef.current = consoleIds
        for (const id of prev) {
            if (! consoleIds.includes(id)) disposeConsoleSession(id)
        }
    }, [consoleIds])

    return (
        <div className="relative h-full min-h-0">
            {consoleIds.map((id, index) => {
                const active = panel === 'console' && activeTabId === id
                const primary = index === 0
                return (
                    <div
                        key={id}
                        className={active
                            ? 'h-full min-h-0'
                            : 'pointer-events-none absolute inset-0 -z-10 h-full min-h-0 overflow-hidden opacity-0'}
                        aria-hidden={! active}
                    >
                        <ConsoleTerminal
                            sessionId={id}
                            active={active}
                            bootInBackground={! active}
                            autostart={primary}
                            syncVfs={primary}
                            vfs={vfs}
                            sessionLog={primary ? consoleSessionLog : []}
                            focusNonce={consoleFocusNonce}
                            projectUuid={projectUuid}
                            isAiStreaming={isAiStreaming}
                        />
                    </div>
                )
            })}

            {/* Keep Preview mounted (hidden) so listen events are not missed while Console is focused. */}
            <div
                className={panel === 'preview' ? 'flex h-full min-h-0' : 'hidden'}
                aria-hidden={panel !== 'preview'}
            >
                <div className="relative min-h-0 min-w-0 flex-1">
                    <PreviewFrame
                        live={previewLive}
                        projectTitle={projectTitle}
                        projectUuid={projectUuid}
                        vfsContents={vfsContents}
                        isAiStreaming={isAiStreaming}
                        aiWriting={aiWriting}
                        previewReloadPending={previewReloadPending}
                        aiBusy={aiBusy}
                        turnStatusLabel={turnStatusLabel}
                        thinkingLive={thinkingLive}
                        inspecting={inspecting}
                        pickedElement={inspectedElement}
                        onInspectSelect={onInspectSelect}
                        onInspectStop={onInspectStop}
                        inspectApplyRef={inspectApplyRef}
                        onAskLab={onAskLab}
                        onStartInspect={onStartInspect}
                        onPinPreviewEdit={onPinPreviewEdit}
                        visible={panel === 'preview'}
                    />
                </div>
                {inspectedElement ? (
                    <DomInspectPanel
                        element={inspectedElement}
                        onClose={onInspectClear}
                        onApply={(patch) => {
                            inspectApplyRef?.current?.(patch)
                            if (inspectedElement) {
                                onPinPreviewEdit?.({
                                    element: inspectedElement,
                                    action: 'applied',
                                    applied: patch,
                                })
                            }
                        }}
                    />
                ) : null}
            </div>

            {panel === 'app-settings' && (
                <AppSettingsPanel
                    autoSave={autoSave}
                    onToggleAutoSave={onToggleAutoSave}
                />
            )}

            {panel === 'tables' && (
                <DatastoreDesk onConnect={onConnectDatastore} onChangeProject={onChangeDatastore} />
            )}

            {panel !== 'console' && panel !== 'files' && panel !== 'preview' && panel !== 'app-settings' && panel !== 'tables' && (
                panel === 'editor' ? (
                    <PanelPlaceholder
                        title="Editor"
                        body="Open a file from Files to draft here."
                    />
                ) : (
                    <PanelPlaceholder
                        title={panel}
                        body="Panel UI coming next."
                    />
                )
            )}
        </div>
    )
}
