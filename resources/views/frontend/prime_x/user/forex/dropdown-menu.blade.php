<div class="dropdown relative">
    <button class="text-xl text-center block w-full " type="button" data-bs-toggle="dropdown" aria-expanded="false">
      <span class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
        <iconify-icon icon="heroicons-outline:dots-horizontal"></iconify-icon>
      </span>
    </button>
    <ul class=" dropdown-menu w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
        <div class="dropdown-header flex justify-around border-b p-3">
            @if($account->account_type == 'demo' && $account->status == \App\Enums\ForexAccountStatus::Ongoing)
                <a href="{{route('user.deposit.amount')}}" class="text-center dropdown-deposit-demo-account"
                   data-bs-toggle="modal"
                   data-bs-target="#depositDemo" data-login="{{$account->login}}">
                    <div class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mx-auto">
                        <iconify-icon class="text-slate-800 dark:text-white text-lg" icon="octicon:download-16"></iconify-icon>
                    </div>
                    {{ __('Deposit') }}
                </a>
            @endif

            @if($account->account_type == 'real' && $account->status == \App\Enums\ForexAccountStatus::Ongoing)
                    <a href="{{route('user.deposit.amount')}}" class="text-center">
                        <div class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mx-auto">
                            <iconify-icon class="text-slate-800 dark:text-white text-lg" icon="octicon:download-16"></iconify-icon>
                        </div>
                        {{ __('Deposit') }}
                    </a>
                <a href="{{route('user.withdraw.view')}}" class="text-center">
                    <div class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mx-auto">
                        <iconify-icon class="text-slate-800 dark:text-white text-lg" icon="octicon:upload-16"></iconify-icon>
                    </div>
                    {{ __('Withdraw') }}
                </a>
            @endif
            <a class="text-center" href=""
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#tradeModal">
                <div class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mx-auto">
                    <iconify-icon class="text-slate-800 dark:text-white text-lg" icon="tabler:chart-candle"></iconify-icon>
                </div>
                {{ __('Trade') }}
            </a>
        </div>
        <li>
          <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-account-info"
          href=""
          type="button"
          data-bs-toggle="modal"
          data-bs-target="#accountDetails"
          data-login="{{$account->login}}"
          data-account_name="{{$account->account_name}}"
          data-server="{{$account->server}}"
          data-schema_title="{{$account->schema->title}}"
          data-account_type="{{$account->account_type}}"
          data-leverage="{{$account->leverage}}"
          data-balance="{{get_mt5_account_balance($account->login)}}"
          data-free-margin="{{$account->free_margin}}"
          data-equity="{{get_mt5_account_equity($account->login)}}">Account Details</a>
      </li>
      <li>
          <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-update-leverage"
          href=""
          type="button"
          data-bs-toggle="modal"
          data-bs-target="#changeLeverage"
          data-login="{{$account->login}}" data-id="{{$account->id}}"
          data-action="{{route('user.forex.get.leverage')}}">Change leverage</a>
      </li>
      <li>
          <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-update-name"
          href=""
          type="button"
          data-bs-toggle="modal"
          data-bs-target="#accountRename" data-login="{{$account->login}}"
          data-account_name="{{$account->account_name}}">Rename account</a>
      </li>
      <li>
          <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-update-password"
          href=""
          type="button"
          data-bs-toggle="modal"
          data-bs-target="#changeAccountPass" data-login="{{$account->login}}"
          data-main_password="{{$account->main_password}}"
          data-invest_password="{{$account->invest_password}}">Change trading password</a>
      </li>
      <li>
          <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-update-password"
          href=""
          type="button"
          data-bs-toggle="modal"
          data-bs-target="#changeInvestorPass" data-login="{{$account->login}}">Change investor password</a>
      </li>
      <li>
          @if($account->status == \App\Enums\ForexAccountStatus::Archive)
              <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white archive-login" href=""
              type="button"
              data-bs-toggle="modal"
              data-login="{{data_get($account,'login')}}"
              data-bs-target="#unarchiveAccount">Unarchive account</a>
          @else
              <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white archive-login"
              href=""
              type="button"
              data-bs-toggle="modal"
              data-login="{{data_get($account,'login')}}"
              data-bs-target="#archiveAccount">Archive account</a>
          @endif
      </li>
    </ul>
  </div>
