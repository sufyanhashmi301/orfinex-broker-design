<style>

    @import url('https://fonts.googleapis.com/css2?family={{ setting('font_family', 'misc_colors') }}:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');

    html, .sidebar-menu > li, .card-title, .btn {
        font-family: {{ setting('font_family', 'misc_colors') }}, sans-serif;
    }
    .light body, body, .bg-body {
        background-color: rgba({{ implode(' ', getColorFromSettings('body_bg_color')) }} / var(--tw-bg-opacity));
    }
    .kyc-alert .sidebar-wrapper, .submenu-sidebar, .site-footer, .card, #staff-list__container {
        background-color: rgba({{ implode(' ', getColorFromSettings('base_color')) }} / var(--tw-bg-opacity));
    }
    .dark body, .dark .dark\:bg-body, .dark .kyc-alert, .dark .sidebar-wrapper, .dark .submenu-sidebar, .dark #staff-list__container, .dark .dropdown-menu, .dark .form-control, .dark .select2-selection, .dark .note-dropdown-menu, .dark .site-footer {
        background-color: rgba({{ implode(' ', getColorFromSettings('body_bg_color_dark')) }} / var(--tw-bg-opacity));
    }
    .app-header, .logo-segment {
        background-color: rgba({{ implode(' ', getColorFromSettings('header_bg')) }} / var(--tw-bg-opacity));
    }
    .dark .app-header, .dark .logo-segment {
        background-color: rgba({{ implode(' ', getColorFromSettings('header_bg_dark')) }} / var(--tw-bg-opacity));
    }
    .app-header .header-text-color, .mobileUserInfo .header-text-color {
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('header_color')) }} / var(--tw-text-opacity));
    }
    .dark .app-header .header-text-color, .dark .mobileUserInfo .header-text-color {
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('header_color_dark')) }} / var(--tw-text-opacity));
    }
    .sidebar-menus, .stickySetting_menu {
        background-color: rgba({{ implode(' ', getColorFromSettings('sidebar_bg')) }} / var(--tw-bg-opacity));
    }
    .dark .sidebar-menus, .dark .stickySetting_menu {
        background-color: rgba({{ implode(' ', getColorFromSettings('sidebar_bg_dark')) }} / var(--tw-bg-opacity));
    }
    .sidebar-menus .navItem, .sidebar-menu > li .icon-arrow {
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('sidebar_color')) }} / var(--tw-text-opacity));
    }
    .sidebar-menu .sidebar-submenu > li > a, .sidebar-menu .sidebar-menu-title, .stickySetting_menu a {
        --tw-text-opacity: .80;
        color: rgba({{ implode(' ', getColorFromSettings('sidebar_color')) }} / var(--tw-text-opacity));
    }
    .dark .sidebar-menus .navItem, .dark .sidebar-menu > li .icon-arrow {
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('sidebar_color_dark')) }} / var(--tw-text-opacity));
    }
    .dark .sidebar-menu .sidebar-submenu > li > a, .dark .sidebar-menu .sidebar-menu-title, .dark .stickySetting_menu a {
        --tw-text-opacity: .80;
        color: rgba({{ implode(' ', getColorFromSettings('sidebar_color_dark')) }} / var(--tw-text-opacity));
    }
    .sidebar-menu .sidebar-submenu > li > a:hover, .sidebar-menu .sidebar-submenu > li > a.active {
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('sidebar_color')) }} / var(--tw-text-opacity));
    }
    .dark .sidebar-menu .sidebar-submenu > li > a:hover, .dark .sidebar-menu .sidebar-submenu > li > a.active {
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('sidebar_color_dark')) }} / var(--tw-text-opacity));
    }
    .sidebar-menu .sidebar-submenu > li > a::before {
        --tw-border-opacity: .80;
        border-color: rgb({{ implode(' ', getColorFromSettings('sidebar_color')) }} / var(--tw-border-opacity));
        --tw-ring-color: rgb({{ implode(' ', getColorFromSettings('sidebar_color')) }} / var(--tw-ring-opacity));
        content: var(--tw-content);
        --tw-ring-opacity: 0.2;
    }
    .sidebar-menu .sidebar-submenu > li > a.active::before {
        background-color: rgb({{ implode(' ', getColorFromSettings('sidebar_color')) }} / var(--tw-bg-opacity));
        content: var(--tw-content);
        --tw-bg-opacity: 1;
        background-color: rgb({{ implode(' ', getColorFromSettings('sidebar_color')) }} / var(--tw-bg-opacity));
    }
    .dark .sidebar-menu .sidebar-submenu > li > a::before {
        --tw-border-opacity: .80;
        border-color: rgb({{ implode(' ', getColorFromSettings('sidebar_color_dark')) }} / var(--tw-border-opacity));
        --tw-ring-color: rgb({{ implode(' ', getColorFromSettings('sidebar_color_dark')) }} / var(--tw-ring-opacity));
        content: var(--tw-content);
        --tw-ring-opacity: 0.2;
    }
    .dark .sidebar-menu .sidebar-submenu > li > a.active::before {
        background-color: rgb({{ implode(' ', getColorFromSettings('sidebar_color_dark')) }} / var(--tw-bg-opacity));
        content: var(--tw-content);
        --tw-bg-opacity: 1;
        background-color: rgb({{ implode(' ', getColorFromSettings('sidebar_color_dark')) }} / var(--tw-bg-opacity));
    }
    .dark .form-control {
        --tw-text-opacity: 1;
        color: rgba(255 255 255 / var(--tw-text-opacity));
    }
    .btn-primary {
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
        --tw-ring-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-ring-opacity));
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('primary_btn_color')) }} / var(--tw-text-opacity));
    }
    .btn-base {
        --tw-bg-opacity: 1;
        background-color: rgb({{ implode(' ', getColorFromSettings('secondary_btn_bg')) }} / var(--tw-bg-opacity));
        --tw-text-opacity: 1;
        color: rgb({{ implode(' ', getColorFromSettings('secondary_btn_color')) }} / var(--tw-text-opacity));
        --tw-ring-opacity: 1;
        --tw-ring-color: rgb({{ implode(' ', getColorFromSettings('secondary_btn_bg')) }} / var(--tw-ring-opacity));
    }
    .dark .btn-base {
        --tw-bg-opacity: 1;
        background-color: rgb({{ implode(' ', getColorFromSettings('secondary_btn_bg_dark')) }} / var(--tw-bg-opacity));
        --tw-text-opacity: 1;
        color: rgb({{ implode(' ', getColorFromSettings('secondary_btn_color_dark')) }} / var(--tw-text-opacity));
        --tw-ring-opacity: 1;
        --tw-ring-color: rgb({{ implode(' ', getColorFromSettings('secondary_btn_bg_dark')) }} / var(--tw-ring-opacity));
    }
    .btn-base:hover {
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        --tw-ring-opacity: 0.8;
        --tw-ring-offset-width: 1px;
    }
    .dark .btn-primary {
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg_dark')) }} / var(--tw-bg-opacity));
        --tw-ring-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg_dark')) }} / var(--tw-ring-opacity));
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('primary_btn_color_dark')) }} / var(--tw-text-opacity));
    }
    .progress-steps .single-step.current .progress_bar, .after\:bg-primary:after, .before\:bg-primary:before {
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-bg-opacity));
    }
    .text-primary {
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-text-opacity));
    }
    .sidebar-menu .navItem.active, .sidebar-menu>li.active>a {
        background-color: rgba({{ implode(' ', getColorFromSettings('active_menu_bg')) }} / var(--tw-bg-opacity));
        border-left-color: rgb({{ implode(' ', getColorFromSettings('active_menu_color')) }});
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('active_menu_color')) }} / var(--tw-text-opacity));
    }
    .sidebar-menu > li.active .icon-arrow {
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('active_menu_color')) }} / var(--tw-text-opacity));
    }
    .dark .sidebar-menu .navItem.active, .dark .sidebar-menu>li.active>a {
        background-color: rgba({{ implode(' ', getColorFromSettings('active_menu_bg_dark')) }} / var(--tw-bg-opacity));
        border-left-color: rgb({{ implode(' ', getColorFromSettings('active_menu_color_dark')) }});
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('active_menu_color_dark')) }} / var(--tw-text-opacity));
    }
    .dark .sidebar-menu > li.active .icon-arrow {
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('active_menu_color_dark')) }} / var(--tw-text-opacity));
    }
    .grid-view-btn.active, .list-view-btn.active {
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
        border-color:  rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }});
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('primary_btn_color')) }} / var(--tw-text-opacity));
    }
    .outline-buttons .btn.active {
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
    }
    .custom-tabs .btn-outline-primary:hover {
        --tw-bg-opacity: 0.35;
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
    }
    .custom-tabs .btn.active {
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
        border-color:  rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }});
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('primary_btn_color')) }} / var(--tw-text-opacity));
    }
    #page-loader .dot {
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-bg-opacity));
    }
    .bg-primary {
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-bg-opacity));
    }
    .bg-secondary {
        background-color: rgba({{ implode(' ', getColorFromSettings('secondary_color')) }} / var(--tw-bg-opacity));
    }
    .bg-success, .btn-success, .switch-field input:checked + label {
        background-color: rgba({{ implode(' ', getColorFromSettings('success_color')) }} / var(--tw-bg-opacity));
    }
    .btn-success {
        --tw-ring-opacity: 1;
        --tw-ring-color: rgb({{ implode(' ', getColorFromSettings('success_color')) }} / var(--tw-ring-opacity));
    }
    .bg-warning, .btn-warning{
        background-color: rgba({{ implode(' ', getColorFromSettings('warning_color')) }} / var(--tw-bg-opacity));
    }
    .btn-warning {
        --tw-ring-opacity: 1;
        --tw-ring-color: rgb({{ implode(' ', getColorFromSettings('warning_color')) }} / var(--tw-ring-opacity));
    }
    .bg-danger, .btn-danger, .switch-field input:checked + label:last-of-type {
        background-color: rgba({{ implode(' ', getColorFromSettings('danger_color')) }} / var(--tw-bg-opacity));
    }
    .btn-danger {
        --tw-ring-opacity: 1;
        --tw-ring-color: rgb({{ implode(' ', getColorFromSettings('danger_color')) }} / var(--tw-ring-opacity));
    }
    .text-primary{
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-text-opacity));
    }
    .text-secondary{
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('secondary_color')) }} / var(--tw-text-opacity));
    }
    .text-success{
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('success_color')) }} / var(--tw-text-opacity));
    }
    .text-warning{
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('warning_color')) }} / var(--tw-text-opacity));
    }
    .text-danger, .error{
        --tw-text-opacity: 1;
        color: rgba({{ implode(' ', getColorFromSettings('danger_color')) }} / var(--tw-text-opacity));
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
    .\!border-danger {
        border-color:  rgba({{ implode(' ', getColorFromSettings('danger_color')) }});
    }
    .badge-primary, .tag.label-info{
        background-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-bg-opacity));
        color: rgba({{ implode(' ', getColorFromSettings('primary_btn_color')) }} / var(--tw-text-opacity));
    }
    .badge-secondary{
        background-color: rgba(205, 205, 205, 0.29);
        color: #828289;
    }
    .dark .badge-secondary{
        background-color: rgba({{ implode(' ', getColorFromSettings('secondary_color_dark')) }} / var(--tw-bg-opacity));
    }
    .badge-success{
        background-color: rgba(0, 236, 66, 0.29);
        color: #008133;
    }
    .badge-warning{
        background-color: rgba(254, 208, 0, 0.52);
        color: #5F4D00;
    }
    .badge-danger{
        background-color: rgba(193, 65, 65, 0.29);
        color: #C14141;
    }
    .dark .dark\:fill-white {
        fill: #fff;
    }
    .dark .dark\:bg-secondary, .dark .dark\:bg-dark, .dark .sidebar-submenu .navItem.active, .dark .card, .dark .notify .bg-white {
        background-color: rgba({{ implode(' ', getColorFromSettings('base_color_dark')) }} / var(--tw-bg-opacity));
    }
    .dark .sidebar-submenu .navItem.active {
        color: white;
    }
    .dark .form-control[readonly], .dark .form-control[disbaled] {
        --tw-bg-opacity: .1;
        background-color: rgba(255 255 255 / var(--tw-bg-opacity));
    }
    .dark .select2-container--default .select2-selection--single, .dark .select2-container--default .select2-selection--multiple, .dark .wrap-custom-file {
        border-color: rgb(51 65 85 / var(--tw-border-opacity));
    }
    .dark .dataTable td, .dark .note-btn, .dark .note-frame {
        color: rgb(203 213 225 / var(--tw-text-opacity));
    }
    .dark .note-editor.note-airframe, .dark .note-editor.note-frame, .dark .note-toolbar, .dark .note-btn, .dark .note-dropdown-menu {
        border-color: rgb(51 65 85 / var(--tw-border-opacity));
    }
    .dark .note-toolbar {
        background-color: rgba({{ implode(' ', getColorFromSettings('body_bg_color_dark')) }} / var(--tw-bg-opacity));
        color: rgb(203 213 225 / var(--tw-text-opacity));
    }
    .dark .note-btn.active, .dark .note-btn.focus, .dark .note-btn:active, .dark .note-btn:focus, .dark .note-btn:hover {
        background-color: rgb(51 65 85 / var(--tw-bg-opacity));
    }
    .dark .site-footer{
        box-shadow: inset 0 15px 4px -15px #334155;
    }
    .tickets-list__container li.current {
        background-color: white;
    }
    .dark .tickets-list__container li.current {
        background-color: rgba({{ implode(' ', getColorFromSettings('base_color_dark')) }} / var(--tw-bg-opacity));
    }
    .fill-primary {
        fill: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-bg-opacity));
    }

    #tabs-tab .nav-link.active {
        border-bottom-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-text-opacity));
        color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-text-opacity));
    }
</style>
