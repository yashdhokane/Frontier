<div class="card card-hover">
    <div class="card-header bg-warning d-flex justify-content-between">
        <h4 class="mb-0 text-white">Quick Links</h4>
        @if ($layout->added_by == auth()->user()->id)
            <button class="btn btn-light mx-2 clearSection"
                data-element-id="{{ $cardPosition->element_id }}">X</button>
        @endif
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><i
                    class="ri-file-list-line feather-sm me-2"></i> <a
                    href="{{ route('download') }}">Download App </a></li>
            <li class="list-group-item"><i
                    class="ri-file-list-line feather-sm me-2"></i> <a
                    href="#.">View
                    Website </a></li>
            <li class="list-group-item"><i
                    class="ri-file-list-line feather-sm me-2"></i> <a
                    href="{{ route('contact') }}">Contact Support </a></li>
            <li class="list-group-item"><i
                    class="ri-file-list-line feather-sm me-2"></i> <a
                    href="{{ route('about') }}">About Dispat Channel</a></li>
            <li class="list-group-item"><i
                    class="ri-file-list-line feather-sm me-2"></i> <a
                    href="{{ route('reviews') }}">Reviews</a></li>
            <li class="list-group-item"><i
                    class="ri-file-list-line feather-sm me-2"></i> <a
                    href="{{ route('privacy') }}">Privacy Policy </a></li>
            <li class="list-group-item"><i
                    class="ri-file-list-line feather-sm me-2"></i> <a
                    href="{{ route('documentation') }}">Documentation </a></li>
        </ul>
    </div>
</div>