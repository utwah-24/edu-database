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

    /* Dashboard header */
    .fi-dashboard-page .fi-header-heading {
        font-size: 1.875rem !important;
        font-weight: 700 !important;
        letter-spacing: -0.03em;
        line-height: 1.2 !important;
    }

    .fi-dashboard-page .fi-header-subheading {
        color: var(--admin-subheading) !important;
        max-width: 40rem;
        line-height: 1.5 !important;
        margin-top: 0.35rem !important;
    }

    /* Table widget */
    .fi-wi-widget .fi-ta-ctn {
        border-radius: 0.75rem !important;
        border: 1px solid var(--admin-stat-border) !important;
        overflow: hidden;
    }

    .fi-wi-widget .fi-ta-header-cell {
        font-weight: 600 !important;
        font-size: 0.75rem !important;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    /* Chart canvas area padding */
    .fi-wi-chart canvas {
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
