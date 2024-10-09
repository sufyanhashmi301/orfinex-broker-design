<style>
    .light body, .bg-body {
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('body_bg_color')) }} / var(--tw-bg-opacity));
    }
    .app-header, .sidebar-wrapper, .logo-segment, .submenu-sidebar, .site-footer, .card {
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('secondary_color')) }} / var(--tw-bg-opacity));
    }
    .dark body, .dark .dark\:bg-body, .dark .app-header, .dark .sidebar-wrapper, .dark .logo-segment, .dark .submenu-sidebar, .dark .dropdown-menu, .dark .form-control, .dark .select2-selection, .dark .note-dropdown-menu, .dark .site-footer {
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('body_bg_color_dark')) }} / var(--tw-bg-opacity));
    }
    .btn-primary {
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
        --tw-ring-opacity: 1;
        --tw-ring-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-ring-opacity));
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('primary_btn_color')) }} / var(--tw-text-opacity));
    }
    .bg-primary, .progress-steps .single-step.current .progress_bar, .after\:bg-primary:after, .before\:bg-primary:before {
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-bg-opacity));
    }
    .text-primary {
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-text-opacity));
    }
    .sidebar-menu .navItem.active, .dark .sidebar-menu .navItem.active, .sidebar-menu>li.active>a {
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('active_menu_bg')) }} / var(--tw-bg-opacity));
        border-left-color: rgb({{ implode(' ', getColorFromSettings('active_menu_color')) }});
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('active_menu_color')) }} / var(--tw-text-opacity));
    }
    .grid-view-btn.active, .list-view-btn.active {
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
        border-color:  rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }});
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('primary_btn_color')) }} / var(--tw-text-opacity));
    }
    .outline-buttons .btn.active {
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
    }
    .custom-tabs .btn-outline-primary:hover {
        --tw-bg-opacity: 0.35;
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
    }
    .custom-tabs .btn.active {
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
        border-color:  rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }});
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('primary_btn_color')) }} / var(--tw-text-opacity));
    }
    #page-loader .dot {
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-bg-opacity));
    }
    .border-primary {
        border-color:  rgba({{ implode(' ', getColorFromSettings('primary_color')) }});
    }
    .border-success {
        border-color:  rgba({{ implode(' ', getColorFromSettings('success_color')) }});
    }
    .border-warning {
        border-color:  rgba({{ implode(' ', getColorFromSettings('warning_color')) }});
    }
    .border-danger {
        border-color:  rgba({{ implode(' ', getColorFromSettings('danger_color')) }});
    }
    .badge.badge-primary{
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-bg-opacity));
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('primary_btn_color')) }} / var(--tw-text-opacity));
    }
    .badge.badge-success{
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('success_color')) }} / var(--tw-bg-opacity));
    }
    .badge.badge-warning{
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('warning_color')) }} / var(--tw-bg-opacity));
    }
    .badge.badge-danger{
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('danger_color')) }} / var(--tw-bg-opacity));
    }
    .dark .dark\:fill-white {
        fill: #fff;
    }
    .dark .dark\:bg-secondary, .dark .dark\:bg-dark, .dark .sidebar-submenu .navItem.active, .dark .card {
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('secondary_color_dark')) }} / var(--tw-bg-opacity));
    }
    .dark .sidebar-submenu .navItem.active {
        color: white;
    }
    .dark .select2-container--default .select2-selection--single, .dark .select2-container--default .select2-selection--multiple, .dark .wrap-custom-file {
        --tw-border-opacity: 1;
        border-color: rgb(51 65 85 / var(--tw-border-opacity));
    }
    .dark .dataTable td, .dark .note-btn, .dark .note-frame {
        --tw-text-opacity: 1;
        color: rgb(203 213 225 / var(--tw-text-opacity));
    }
    .dark .note-editor.note-airframe, .dark .note-editor.note-frame, .dark .note-toolbar, .dark .note-btn, .dark .note-dropdown-menu {
        --tw-border-opacity: 1;
        border-color: rgb(51 65 85 / var(--tw-border-opacity));
    }
    .dark .note-toolbar {
        --tw-bg-opacity: 1;
        background-color: rgba({{ implode(' ', getColorFromSettings('body_bg_color_dark')) }} / var(--tw-bg-opacity));
        --tw-text-opacity: 1;
        color: rgb(203 213 225 / var(--tw-text-opacity));
    }
    .dark .note-btn.active, .dark .note-btn.focus, .dark .note-btn:active, .dark .note-btn:focus, .dark .note-btn:hover {
        --tw-bg-opacity: 1;
        background-color: rgb(51 65 85 / var(--tw-bg-opacity));
    }
    .dark .site-footer{
        box-shadow: inset 0 15px 4px -15px #334155;
    }
</style>
