<div class="card card-hover">
    
    <div class="p-3">
        <div class="d-flex justify-content-between mb-1">
           <!-- <h4 class="">MY ACTIVITY</h4> -->
           
        </div>
        <div class="table-responsive">
            <table class="table customize-table mb-0 v-middle">
                <tbody>
                    @foreach ($activity as $record)
                        <tr>
                            <td>
                                <div>{{ $record->activity }}</div>
                                <div class="text-muted">
                                    {{ \Carbon\Carbon::parse($record->created_at)->format('D
                                                                                                                                                                                                                                                                                                                                                                                                                                                                n/j/y
                                                                                                                                                                                                                                                                                                                                                                                                                                                                g:ia') ??
                                        'null' }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a class="nav-link text-dark" href="{{ route('myprofile.activity') }}">
            <strong>View All</strong>
            <i data-feather="chevron-right" class="feather-sm"></i>
        </a>
    </div>
</div>