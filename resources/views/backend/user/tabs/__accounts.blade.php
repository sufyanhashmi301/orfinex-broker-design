<div class="tab-pane fade" data-tab="accounts" id="accounts" role="tabpanel">
    @include('backend.accounts.includes.__accounts', ['view' => 'user_edit'])

    <style>
        @media (min-width: 1023px) {
            .dataTables_wrapper, .basicTable_wrapper {
                min-height: 567px !important
            }
        }
    </style>
</div>