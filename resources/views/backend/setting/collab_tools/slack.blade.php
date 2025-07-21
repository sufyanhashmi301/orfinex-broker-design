@extends('backend.setting.collab_tools.index')
@section('title')
    {{ __('Slack Settings') }}
@endsection
@section('collab-content')
    <div class="card">
        <div class="card-body p-6">
            <form action="" method="post" enctype="multipart/form-data">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="input-area">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Paste your Slack webhook URL here to enable message delivery to your workspace">
                                {{ __('Web Hook Url') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <div class="relative">
                            <input type="text" name="" class="form-control" placeholder="" />
                        </div>
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Optionally specify the Slack channel (e.g., #alerts) where messages will be sent. Leave empty to use the default set in the webhook">
                                {{ __('Chanel Name (Optional)') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <div class="relative">
                            <input type="text" name="" class="form-control" placeholder="" />
                        </div>
                    </div>
                </div>

                <div class="mt-10">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Save Changes') }}
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
