@php $selector = $selector ?? '#dataTable'; @endphp
<style>
/* Minimal, scoped DataTables sort icons for this page only (targets {{$selector}}) */
{{$selector}} thead th {
    position: relative;
    padding-right: 18px; /* space for icon */
}

{{$selector}} thead th.sorting,
{{$selector}} thead th.sorting_asc,
{{$selector}} thead th.sorting_desc {
    cursor: pointer;
}

/* Hover highlight: subtle border around header cell */
{{$selector}} thead th:hover::before {
    content: "";
    position: absolute;
    inset: 0;
    border: 1px solid currentColor;
    opacity: .35;
    pointer-events: none;
    border-radius: 4px;
}

/* default both-directions indicator */
{{$selector}} thead th.sorting::after,
{{$selector}} thead th.sorting_asc::after,
{{$selector}} thead th.sorting_desc::after {
    content: "↕";
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 12px;
    line-height: 1;
    opacity: .55;
    color: currentColor;
}

/* active states */
{{$selector}} thead th.sorting_asc::after { content: "↑"; opacity: 1; }
{{$selector}} thead th.sorting_desc::after { content: "↓"; opacity: 1; }

/* Dark mode tweak if your layout toggles .dark on <html> or <body> */
.dark {{$selector}} thead th.sorting::after,
.dark {{$selector}} thead th.sorting_asc::after,
.dark {{$selector}} thead th.sorting_desc::after {
    color: inherit;
}
</style>


