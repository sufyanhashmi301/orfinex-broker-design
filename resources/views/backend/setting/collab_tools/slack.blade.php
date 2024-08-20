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
                            {{ __('Web Hook Url') }}
                        </label>
                        <div class="relative">
                            <input type="text" name="" class="form-control" placeholder="" />
                        </div>
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">
                            {{ __('Chanel Name (Optional)') }}
                        </label>
                        <div class="relative">
                            <input type="text" name="" class="form-control" placeholder="" />
                        </div>
                    </div>
                </div>

                <div class="mt-10">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        Save Changes
                    </button>
                </div>
            
            </form>
        </div>
    </div>
@endsection
