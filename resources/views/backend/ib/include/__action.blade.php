
<!-- Update your __action Blade file -->
<!-- Update your __action Blade file -->
@if ($user->ibQuestionAnswers)
    <button type="button" class="btn btn-danger detail-btn" data-toggle="modal" data-target="#viewDataModal" data-user-id="{{ $user->id }}">
        <i class="fas fa-eye"></i>
    </button>

@else
    <button type="button" class="btn btn-danger detail-btn" data-toggle="tooltip" data-placement="top" title="No IB Data Available">
        <i class="fas fa-eye-slash"></i>
    </button>
@endif



@if($user->ib_status !=='approved')
<button class="btn btn-primary approve-btn" data-toggle="tooltip" data-placement="top" title="Edit">
    <i class="fas fa-edit"></i>
</button>


<button type="button" class="btn btn-danger reject-btn" data-toggle="tooltip" data-placement="top" title="Reject">
    <i class="fas fa-times"></i>
</button>
@endif

