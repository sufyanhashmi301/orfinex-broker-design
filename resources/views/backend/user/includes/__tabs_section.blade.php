<div class="site-tab-bars card p-3 mb-5">
  <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 gap-3 menu-open" id="pills-tab"
      role="tablist">
      @canany(['customer-basic-manage', 'customer-change-password'])
          <li class="nav-item" role="presentation">
              <a href=""
                  data-tab="profile"
                  class="nav-link tab-toggle block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                  id="pills-informations-tab" data-bs-toggle="pill" data-bs-target="#pills-informations"
                  type="button" role="tab" aria-controls="pills-informations" aria-selected="true">
                  {{ __('Profile') }}
              </a>
          </li>
      @endcanany
      @can('investment-list')
          <li class="nav-item" role="presentation">
              <a href=""
                  data-tab="accounts"
                  class="nav-link tab-toggle block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                  id="accounts-tab" data-bs-toggle="pill" data-bs-target="#accounts" type="button"
                  role="tab" aria-controls="accounts" aria-selected="true">
                  {{ __('Accounts') }}
              </a>
          </li>
      @endcan

      @can('transaction-list')
          {{-- Payments --}}
          <li class="nav-item" role="presentation">
              <a href=""
                  data-tab="payments"
                  class="nav-link tab-toggle block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                  id="payments-tab" data-bs-toggle="pill" data-bs-target="#payments" type="button"
                  role="tab" aria-controls="pills-transfer" aria-selected="true">
                  {{ __('Payments') }}
              </a>
          </li>
          {{-- Withdraws --}}
          <li class="nav-item" role="presentation">
              <a href=""
                  data-tab="withdraws"
                  class="nav-link tab-toggle block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                  id="withdraws-tab" data-bs-toggle="pill" data-bs-target="#withdraws" type="button"
                  role="tab" aria-controls="pills-transfer" aria-selected="true">
                  {{ __('Withdraws') }}
              </a>
          </li>
      @endcan

      @canany(['support-ticket-list', 'support-ticket-action'])
          <li class="nav-item" role="presentation">
              <a href=""
                  data-tab="tickets"
                  class="nav-link tab-toggle block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                  id="pills-ticket-tab" data-bs-toggle="pill" data-bs-target="#pills-ticket" type="button"
                  role="tab" aria-controls="pills-transfer" aria-selected="true">
                  {{ __('Tickets') }}
              </a>
          </li>
          <li class="nav-item" role="presentation">
              <a href="?tabs=security"
                  class="nav-link tab-toggle block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                  id="pills-security-tab" data-bs-toggle="pill" data-bs-target="#pills-security"
                  type="button" role="tab" aria-controls="pills-security" aria-selected="true">
                  {{ __('Security') }}
              </a>
          </li>
      @endcanany
  </ul>
</div>

<div class="tab-content" id="pills-tabContent">

  {{-- Tabs --}}
  @canany(['customer-basic-manage', 'customer-change-password'])
      @include('backend.user.tabs.__profile')
  @endcanany
  @include('backend.user.tabs.__accounts')
  @can('transaction-list')
    @include('backend.user.tabs.__payments')
    @include('backend.user.tabs.__withdraws')
  @endcan
  @canany(['support-ticket-list', 'support-ticket-action'])
      @include('backend.user.tabs.__ticket')
  @endcan


  @include('backend.user.tabs.__security')

</div>

@push('single-script')
    <script>
        $(".tab-toggle").on("click", function (event) {
            event.preventDefault(); // Prevent default anchor behavior

            let newUrl = window.location.origin + window.location.pathname + '?tab=' + $(this).attr('data-tab');
            history.pushState(null, "", newUrl); 

            // Get all the hrefs with page= in it
            for(let i=0; i < $('.paginate-link').length; i++) {
                let link = $('.paginate-link').eq(i).attr('href')
                if(link && link.includes('page=')) {
                    let urlParams = new URLSearchParams(link.split('?')[1]);
                    let page = urlParams.get('page');
                    $('.paginate-link').eq(i).attr('href', newUrl + '&page=' + page)
                }
            }
        });

        $(document).ready(function () {
            // Get the tab parameter from the URL
            let urlParams = new URLSearchParams(window.location.search);
            let activeTab = urlParams.get("tab");

            // If there's a tab parameter, activate the corresponding tab
            if (activeTab) {
                $('.tab-toggle[data-tab="' + activeTab + '"]').addClass('active')
                $('.tab-pane[data-tab="' + activeTab + '"]').addClass('show').addClass('active')
            }
        });
    </script>
@endpush