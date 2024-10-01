<link rel="stylesheet" href="{{ url('public/admin/schedule/demo.css') }}">
<div id="newdemodata">
    @include('schedule.schedule_iframe')
</div>
<!-- Modal -->
<div class="modal fade" id="event" tabindex="-1" aria-labelledby="scroll-long-inner-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable2 modal-dialog modal-xl">
        <form action="{{ url('store/event/') }}" method="POST" id="addEvent">
            <input type="hidden" name="event_technician_id" id="event_technician_id" value="">
            <input type="hidden" name="scheduleType" id="scheduleType" value="event">
            @csrf
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center" style="padding-bottom: 0px;">
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h4 class="modal-title" id="myLargeModalLabel" style="margin-left: 28px;">
                            New Event
                        </h4>
                    </div>
                    <button type="submit" class="btn btn-primary">SAVE EVENT</button>

                </div>
                <hr color="#6a737c">
                <div class="modal-body createCustomerData pb-5">
                    @include('schedule.event_iframe')

                </div>

            </div>
        </form>
    </div>
</div>
<!-- Modal -->

@include('schedule.demoScript')
