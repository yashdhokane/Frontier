<h5 class="card-title uppercase">Notes </h5>
<div class="profiletimeline mt-2">
    @foreach ($notename as $notename)
    <div class="sl-item mb-4">
        <div class="sl-left">
            @php
            $username = DB::table('users')
            ->where('id', $notename->added_by)
            ->first();
            @endphp
            @if ($username && $username->user_image)
            <img src="{{ asset('public/images/Uploads/users/' . $username->id . '/' . $username->user_image) }}"
                class="rounded-circle" alt="user" />
            @else
            <img src="{{ asset('public/images/login_img_bydefault.png') }}" alt="user" class=" rounded-circle" />
            @endif

        </div>

        <div class="sl-right">
            <div>
                <a href="javascript:void(0)" class="link ucfirst ft17">
                    {{ $username->name ?? null }}</a>
                <span class="sl-date">
                    {{ \Carbon\Carbon::parse($notename->created_at)->diffForHumans() }}
                </span>
                <div class="row">
                    <div class="col-lg-12 col-md-12 ft15">
                        {{ $notename->note }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="row mt-2">
    <div class="col-lg-6 col-xlg-6">
        <form id="commentForm" action="{{ route('techniciancomment.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="tag_id" class="control-label bold col-form-label uppercase">Add New
                    Comment</label>
                <input type="hidden" name="id" value="{{ $commonUser->id }}">
                <textarea class="form-control" id="comment" name="note" rows="3"></textarea>
            </div>
            <div class="mb-3 d-flex align-items-center">
                <button type="submit" id="submitButton" class="btn btn-primary ms-2">Submit</button>
            </div>
        </form>
    </div>
</div>
