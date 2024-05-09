<div class="row">

    <div class="col-md-12">
        <h5 class="card-title uppercase">Setting</h5>

        <div class="row mt-2">

            <form id="settingsForm" action="{{ route('smstechnician') }}" method="POST">
                @csrf
                <div class="col-6">
                    <div class="d-flex align-items-center justify-content-between py-3">
                        <div class="d-flex align-items-center">
                            <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                <i class="ti ti-mail text-dark d-block fs-7" width="22" height="22"></i>
                            </div>
                            <div>
                                <h5 class="fs-4 fw-semibold">Email Notification</h5>
                                <p class="mb-0">Turn on email notification to get updates through email</p>
                            </div>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input type="hidden" value="{{ $multiadmin->id }}" name="id">
                            <input type="hidden" name="switch_email"
                                value="{{ $setting->email_notifications ?? null }}">
                            <input class="form-check-input" type="checkbox" role="switch" id="emailSwitch"
                                name="email_switch" @if(!is_null($setting) && $setting->email_notifications == 1)
                            checked
                            @elseif(is_null($setting) || is_null($setting->email_notifications)) checked @endif
                            onchange="updateFormValues('emailSwitch', 'switch_email', this.checked)">


                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between py-3 border-top mb-3">
                        <div class="d-flex align-items-center">
                            <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                <i class="ti ti-mail text-dark d-block fs-7" width="22" height="22"></i>
                            </div>
                            <div>
                                <h5 class="fs-4 fw-semibold">SMS Notification</h5>
                                <p class="mb-0">Turn on SMS notification to get updates through SMS</p>
                            </div>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input type="hidden" name="switch_sms" value="{{ $setting->sms_notification ?? null }}">
                            <input class="form-check-input" type="checkbox" role="switch" id="smsSwitch"
                                name="sms_switch" @if(!is_null($setting) && $setting->sms_notification == 1) checked
                            @elseif(is_null($setting) || is_null($setting->sms_notification)) checked @endif
                            onchange="updateFormValues('smsSwitch', 'switch_sms', this.checked)">


                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateFormValues(checkboxId, hiddenInputName, checked) {
        // Update the value of the hidden input field
        document.getElementsByName(hiddenInputName)[0].value = checked ? 1 : 0;
    }
</script>
