@extends('backend.setting.website.index')
@section('title')
    {{ __('Site Header & Footer') }}
@endsection
@section('website-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4
            class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            Site Header & Footer
        </h4>
    </div>

    <div class="innerMenu card p-6 mb-5 mt-3">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open w-full">
            <li class="nav-item">
                <a href="{{ route('admin.settings.dynamic-header-footer.index', ['area' => 'user']) }}"
                    class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->get('area') != 'admin' ? 'active' : '' }} ">
                    User Area
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.dynamic-header-footer.index', ['area' => 'admin']) }}"
                    class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->get('area') == 'admin' ? 'active' : '' }} ">
                    Admin Area
                </a>
            </li>
        </ul>

    </div>


    <div class="card">
        <div class="card-body p-6">

            <form action="{{ route('admin.settings.dynamic-header-footer.update') }}" id="headerFooterForm" method="POST">
				@csrf
                @if (request()->get('area') == 'admin')
                    <div class="site-input-area relative">
                        <label for="" class="form-label">Header Code</label>
                        <textarea name="site_admin_header_code" class="form-control" id="" cols="30" rows="10" placeholder="// Code...">{{ setting('site_admin_header_code', 'defaults') }}</textarea>
                    </div>
                    <div class="site-input-area relative mt-3">
                        <label for="" class="form-label">Footer Code</label>
                        <textarea name="site_admin_footer_code" class="form-control" id="" cols="30" rows="10" placeholder="// Code...">{{ setting('site_admin_footer_code', 'defaults') }}</textarea>
                    </div>
                @else
                    <div class="site-input-area relative">
                        <label for="" class="form-label">Header Code</label>
                        <textarea name="site_user_header_code" class="form-control" id="" cols="30" rows="10" placeholder="// Code...">{{ setting('site_user_header_code', 'defaults') }}</textarea>
                    </div>
                    <div class="site-input-area relative mt-3">
                        <label for="" class="form-label">Footer Code</label>
                        <textarea name="site_user_footer_code" class="form-control" id="" cols="30" rows="10" placeholder="// Code...">{{ setting('site_user_footer_code', 'defaults') }}</textarea>
                    </div>
                @endif

                <div class="text-right">
                    <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('script')
	<script>
		$(document).ready(function () {
				$('#headerFooterForm').on('submit', function (e) {
					// Encode the content of each textarea
					$('textarea').each(function () {
						const encodedContent = $('<div/>').text($(this).val()).html();
						$(this).val(encodedContent);
					});
				});
			});
	</script>
@endsection
