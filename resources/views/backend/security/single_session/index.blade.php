@extends('backend.security.index')
@section('security-title')
    {{ __('Single Session') }}
@endsection
@section('title')
    {{ __('Single Session') }}
@endsection
@section('security-content')
    <div class="col-xl-8 col-lg-12 col-md-12 col-12">
        <div class="site-card">
            <div class="site-card-body">
                <form action="" method="post">
                    <div class="site-input-groups">
                        <label for="" class="d-block mb-4">
                            Prevent user from being logged in more than once ?
                            <span class="text-danger ml-1">*</span>
                        </label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="singleSession" id="singleSession1" value="yes" checked>
                            <label class="form-check-label" for="singleSession1">
                              Yes
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="singleSession" id="singleSession2" value="no">
                            <label class="form-check-label" for="singleSession2">
                              No
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection