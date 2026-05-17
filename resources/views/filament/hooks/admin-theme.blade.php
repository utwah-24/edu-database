<style>
    :root {
        --admin-shell: #f0f2f6;
        --admin-topbar: #ffffff;
        --admin-topbar-border: #e5e7eb;
        --admin-card: #ffffff;
        --admin-card-border: #e8ebf0;
        --admin-card-shadow: 0 8px 32px -12px rgba(15, 23, 42, 0.1);
        --admin-stat-border: #eef0f4;
        --admin-stat-shadow: 0 2px 16px -6px rgba(15, 23, 42, 0.08);
        --admin-subheading: #6b7280;
        --admin-sidebar-from: #1e2a3a;
        --admin-sidebar-to: #152535;
        --admin-sidebar-border: rgba(255, 255, 255, 0.07);
        --admin-sidebar-text: rgba(255, 255, 255, 0.9);
        --admin-sidebar-muted: rgba(255, 255, 255, 0.62);
        --admin-sidebar-hover: rgba(232, 107, 74, 0.16);
        --admin-sidebar-active: rgba(232, 107, 74, 0.3);
        --admin-sidebar-active-text: #ffc9b5;
        --admin-accent: #e86b4a;
    }

    .dark {
        --admin-shell: #0f1419;
        --admin-topbar: #161b22;
        --admin-topbar-border: #2d333b;
        --admin-card: #1c2128;
        --admin-card-border: #30363d;
        --admin-card-shadow: 0 8px 32px -12px rgba(0, 0, 0, 0.45);
        --admin-stat-border: #30363d;
        --admin-stat-shadow: 0 2px 16px -6px rgba(0, 0, 0, 0.35);
        --admin-subheading: #9ca3af;
        --admin-sidebar-from: #12171f;
        --admin-sidebar-to: #0d1117;
        --admin-sidebar-border: rgba(255, 255, 255, 0.06);
        --admin-sidebar-text: rgba(255, 255, 255, 0.92);
        --admin-sidebar-muted: rgba(255, 255, 255, 0.55);
        --admin-sidebar-hover: rgba(232, 107, 74, 0.22);
        --admin-sidebar-active: rgba(232, 107, 74, 0.38);
        --admin-sidebar-active-text: #ffd4c4;
    }

    /* Page shell */
    .fi-body {
        background: var(--admin-shell) !important;
    }

    .fi-main {
        background: transparent !important;
    }

    .fi-main-ctn {
        background: transparent !important;
    }

    /* Sidebar — branded dark in both modes */
    .fi-sidebar {
        background: linear-gradient(180deg, var(--admin-sidebar-from) 0%, var(--admin-sidebar-to) 100%) !important;
        border-right: 1px solid var(--admin-sidebar-border) !important;
    }

    .fi-sidebar-header {
        background: transparent !important;
        border-bottom: 1px solid var(--admin-sidebar-border) !important;
    }

    .fi-sidebar-header-logo-ctn,
    .fi-logo {
        color: #fff !important;
    }

    .fi-sidebar-item-label,
    .fi-sidebar-group-label {
        color: var(--admin-sidebar-text) !important;
    }

    .fi-sidebar-item-icon {
        color: var(--admin-sidebar-muted) !important;
    }

    .fi-sidebar-item-button:hover {
        background: var(--admin-sidebar-hover) !important;
    }

    .fi-sidebar-item-active .fi-sidebar-item-button {
        background: var(--admin-sidebar-active) !important;
    }

    .fi-sidebar-item-active .fi-sidebar-item-label,
    .fi-sidebar-item-active .fi-sidebar-item-icon {
        color: var(--admin-sidebar-active-text) !important;
    }

    .fi-sidebar-group-button {
        color: var(--admin-sidebar-muted) !important;
    }

    /* Top bar */
    .fi-topbar {
        background: var(--admin-topbar) !important;
        border-bottom: 1px solid var(--admin-topbar-border) !important;
        box-shadow: none !important;
    }

    .fi-topbar .fi-icon-btn,
    .fi-topbar nav {
        color: inherit;
    }

    /* Widget cards */
    .fi-wi-widget {
        border-radius: 1.25rem !important;
        border: 1px solid var(--admin-card-border) !important;
        background: var(--admin-card) !important;
        box-shadow: var(--admin-card-shadow) !important;
        overflow: hidden;
    }

    .fi-wi-widget .fi-wi-widget-heading {
        font-weight: 600 !important;
        letter-spacing: -0.02em;
    }

    .fi-wi-widget .fi-wi-widget-description {
        opacity: 0.85;
    }

    .fi-wi-stats-overview-stat {
        border-radius: 1rem !important;
        border: 1px solid var(--admin-stat-border) !important;
        background: var(--admin-card) !important;
        box-shadow: var(--admin-stat-shadow) !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .fi-wi-stats-overview-stat:hover {
        transform: translateY(-1px);
    }

    .fi-wi-stats-overview-stat-value {
        font-weight: 700 !important;
        letter-spacing: -0.03em;
        font-size: 1.5rem !important;
    }

    .fi-wi-stats-overview-stat-label {
        font-weight: 500 !important;
    }

    /* Dashboard header — replaced by welcome widget */
    .fi-dashboard-page > .fi-header {
        display: none !important;
    }

    .admin-welcome-widget.fi-wi {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }

    .admin-welcome-widget .fi-wi-content {
        padding: 0 !important;
    }

    .admin-welcome-banner {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: linear-gradient(135deg, #1e2a3a 0%, #243447 42%, #2d3f54 100%);
        box-shadow: var(--admin-card-shadow);
    }

    .admin-welcome-banner__glow {
        position: absolute;
        top: -40%;
        right: -8%;
        width: 22rem;
        height: 22rem;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(232, 107, 74, 0.45) 0%, transparent 68%);
        pointer-events: none;
    }

    .admin-welcome-banner__pattern {
        position: absolute;
        inset: 0;
        opacity: 0.35;
        background-image:
            radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.06) 0%, transparent 45%),
            linear-gradient(120deg, transparent 0%, rgba(255, 255, 255, 0.03) 50%, transparent 100%);
        pointer-events: none;
    }

    .admin-welcome-banner__inner {
        position: relative;
        z-index: 1;
        display: grid;
        gap: 1.5rem;
        padding: 1.5rem 1.5rem 1rem;
    }

    @media (min-width: 1024px) {
        .admin-welcome-banner__inner {
            grid-template-columns: 1.15fr 1fr;
            align-items: center;
            padding: 1.75rem 1.75rem 1rem;
            gap: 2rem;
        }
    }

    .admin-welcome-banner__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin: 0;
        padding: 0.3rem 0.65rem;
        border-radius: 9999px;
        background: rgba(232, 107, 74, 0.2);
        color: #ffd4c4;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .admin-welcome-banner__title {
        margin: 0.85rem 0 0;
        font-size: clamp(1.5rem, 2.5vw, 2rem);
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1.15;
        color: #fff;
    }

    .admin-welcome-banner__lead {
        margin: 0.65rem 0 0;
        max-width: 34rem;
        font-size: 0.9rem;
        line-height: 1.55;
        color: rgba(255, 255, 255, 0.78);
    }

    .admin-welcome-banner__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1.15rem;
    }

    .admin-welcome-banner__action {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.8rem;
        border-radius: 0.55rem;
        border: 1px solid rgba(255, 255, 255, 0.14);
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.15s ease, border-color 0.15s ease, transform 0.15s ease;
    }

    .admin-welcome-banner__action:hover {
        background: rgba(232, 107, 74, 0.28);
        border-color: rgba(232, 107, 74, 0.55);
        transform: translateY(-1px);
    }

    .admin-welcome-banner__metrics {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.65rem;
    }

    .admin-welcome-banner__metric {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
        padding: 0.85rem 0.9rem;
        border-radius: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.06);
        backdrop-filter: blur(6px);
    }

    .admin-welcome-banner__metric--accent {
        border-color: rgba(232, 107, 74, 0.45);
        background: rgba(232, 107, 74, 0.14);
    }

    .admin-welcome-banner__metric-value {
        font-size: 1.35rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1.1;
        color: #fff;
    }

    .admin-welcome-banner__metric-label {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: rgba(255, 255, 255, 0.72);
    }

    .admin-welcome-banner__metric-hint {
        font-size: 0.68rem;
        color: rgba(255, 255, 255, 0.55);
    }

    .admin-welcome-banner__footer {
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.55rem 0.85rem;
        padding: 0.75rem 1.5rem 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(0, 0, 0, 0.12);
    }

    @media (min-width: 1024px) {
        .admin-welcome-banner__footer {
            padding-left: 1.75rem;
            padding-right: 1.75rem;
        }
    }

    .admin-welcome-banner__footer-item {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.68);
    }

    .admin-welcome-banner__footer-dot {
        width: 0.25rem;
        height: 0.25rem;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.35);
    }

    /* Dashboard widget grid — full-width table row */
    .fi-dashboard-page .fi-wi {
        width: 100%;
    }

    .fi-dashboard-page .fi-wi-widget[class*="events-breakdown"],
    .fi-dashboard-page .fi-wi-widget:has(.fi-ta) {
        grid-column: 1 / -1 !important;
        width: 100% !important;
        max-width: none !important;
    }

    /* Table widget */
    .fi-dashboard-page .fi-wi-widget:has(.fi-ta) {
        border-radius: 0.875rem !important;
        border: 1px solid var(--admin-card-border) !important;
        background: var(--admin-card) !important;
        box-shadow: var(--admin-card-shadow) !important;
        overflow: hidden;
    }

    .fi-dashboard-page .fi-wi-widget:has(.fi-ta) .fi-wi-header {
        padding: 1.1rem 1.25rem 0.5rem !important;
    }

    .fi-dashboard-page .fi-wi-widget:has(.fi-ta) .fi-wi-content {
        padding: 0 0 0.25rem !important;
    }

    .fi-dashboard-page .fi-wi-widget .fi-ta-ctn {
        border-radius: 0 !important;
        border: none !important;
        border-top: 1px solid var(--admin-stat-border) !important;
        overflow: hidden;
    }

    .fi-dashboard-page .fi-wi-widget .fi-ta-header-cell {
        font-weight: 600 !important;
        font-size: 0.75rem !important;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .fi-dashboard-page .fi-wi-widget .fi-ta-table {
        width: 100% !important;
    }

    /* Chart widgets — professional card treatment */
    .fi-dashboard-page .fi-wi-chart {
        border-radius: 0.875rem !important;
        border: 1px solid var(--admin-card-border) !important;
        background: var(--admin-card) !important;
        box-shadow: var(--admin-card-shadow) !important;
        overflow: hidden;
    }

    .fi-dashboard-page .fi-wi-chart .fi-wi-header {
        padding: 1.1rem 1.25rem 0.35rem !important;
        border-bottom: none !important;
    }

    .fi-dashboard-page .fi-wi-chart .fi-wi-heading {
        font-size: 0.95rem !important;
        font-weight: 700 !important;
        letter-spacing: -0.02em;
    }

    .fi-dashboard-page .fi-wi-chart .fi-wi-description {
        color: var(--admin-subheading) !important;
        font-size: 0.78rem !important;
        line-height: 1.45 !important;
        margin-top: 0.2rem !important;
    }

    .fi-dashboard-page .fi-wi-chart .fi-wi-content {
        padding: 0.5rem 1rem 1.15rem !important;
    }

    .fi-dashboard-page .fi-wi-chart .fi-wi-chart-canvas-ctn {
        min-height: 260px;
        padding: 0.25rem 0.35rem 0.5rem;
    }

    .fi-dashboard-page .fi-wi-chart canvas {
        max-height: 100%;
    }

    /* Spacing between widget grid rows */
    .fi-dashboard-page .fi-wi {
        gap: 1.25rem;
    }

    @media (min-width: 1280px) {
        .fi-dashboard-page .fi-wi {
            gap: 1.5rem;
        }
    }

    /* Login page — keep readable in both modes */
    .fi-simple-layout {
        background: var(--admin-shell) !important;
    }
</style>
