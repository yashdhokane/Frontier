<div class="col-md-12 ">

    <h5 class="card-title">ACTIVITY FEED</h5>
    <div class="table-responsive">
        <table class="table customize-table mb-0 v-middle">
            <thead>
                <tr>
                    <!-- <th style="width:20%">User</th> -->
                    <th>Activity</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activity as $record)
                <tr>
                    <td>{{ $record->activity}}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($record->created_at)->format('D
                        n/j/y g:ia') ??
                        'null' }}
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>



</div>