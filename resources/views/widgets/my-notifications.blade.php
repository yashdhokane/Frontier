<div class="card card-hover">
    
    <div class="p-3">
        <div class="mb-1 d-flex justify-content-between">
            <h4 class="">MY NOTIFICATIONS</h4>
            @if ($layout->added_by == auth()->user()->id)
                <button class="btn btn-light mx-2 clearSection"
                    data-element-id="{{ $cardPosition->element_id }}">X</button>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table customize-table mb-0 v-middle">

                <tbody>
                    @foreach ($userNotifications as $record)
                        <tr>
                            <td
                                @if ($record->is_read == 0) class="text-muted" @endif>
                                <div class="fw-normal">
                                    {{ $record->notice->notice_title ?? '' }}
                                </div>
                                <div class="text-muted">
                                    {{ \Carbon\Carbon::parse($record->notice->notice_date)->format('D
                                                                                                                                                                                                                                                                                                                                                                                        n/j/y g:ia') ??
                                        'null' }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a class="nav-link text-dark" href="{{ route('myprofile.activity') }}">
            <strong>View All </strong>
            <i data-feather="chevron-right" class="feather-sm"></i>
        </a>
    </div>
</div>