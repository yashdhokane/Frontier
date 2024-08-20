@extends('home')

@section('content')

<div class="row mt-4">
    <div class="col-lg-9 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body border-top">
                <h4 class="card-title">Fill Details</h4>
                <form action="#" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-3">
                                <label for="first_name" class="control-label bold mb5 col-form-label required-field">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" required />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-3">
                                <label for="last_name" class="control-label bold mb5 col-form-label required-field">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="" required />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-3">
                                <label for="display_name" class="control-label bold mb5 col-form-label required-field">Display Name</label>
                                <input type="text" class="form-control" id="display_name" name="display_name" placeholder="" required />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-3">
                                <label for="email" class="control-label bold mb5 col-form-label required-field">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="" required />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-3">
                                <label for="phone" class="control-label bold mb5 col-form-label required-field">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="" required />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-3">
                                <label for="address" class="control-label bold mb5 col-form-label required-field">Address</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="" required />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-3">
                                <label for="country" class="control-label bold mb5 col-form-label required-field">Country</label>
                                <select class="form-control" id="country" name="country" required>
                                    <option value="CA" selected>Canada</option>
                                    <option value="US">United States</option>
                                    <option value="UK">United Kingdom</option>
                                    <!-- Add more countries as needed -->
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-3">
                                <label for="state" class="control-label bold mb5 col-form-label required-field">State/Province</label>
                                <input type="text" class="form-control" id="state" name="state" placeholder="" required />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-3">
                                <label for="zip" class="control-label bold mb5 col-form-label required-field">ZIP/Postal Code</label>
                                <input type="text" class="form-control" id="zip" name="zip" placeholder="" required />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-3">
                                <label class="control-label bold mb5 col-form-label required-field">Occupation</label>
                                <div>
                                    <input type="radio" id="developer" name="occupation" value="developer" required />
                                    <label for="developer">Developer</label>
                                </div>
                                <div>
                                    <input type="radio" id="designer" name="occupation" value="designer" required />
                                    <label for="designer">Designer</label>
                                </div>
                                <div>
                                    <input type="radio" id="manager" name="occupation" value="manager" required />
                                    <label for="manager">Manager</label>
                                </div>
                                <div>
                                    <input type="radio" id="other" name="occupation" value="other" required />
                                    <label for="other">Other</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-8">
                            <div class="mb-3">
                                <label for="self_description" class="control-label bold mb5 col-form-label required-field">Self Description</label>
                                <textarea class="form-control" id="self_description" name="self_description" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="control-label bold mb5 col-form-label">Interests</label>
                                <div>
                                    <input type="checkbox" id="interest1" name="interests[]" value="Technology" />
                                    <label for="interest1">Technology</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="interest2" name="interests[]" value="Sports" />
                                    <label for="interest2">Sports</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="interest3" name="interests[]" value="Music" />
                                    <label for="interest3">Music</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="interest4" name="interests[]" value="Travel" />
                                    <label for="interest4">Travel</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define the form data
        const formData = @json($formData); // Use the passed PHP data to JS

        // Define the time delay between typing each character (in milliseconds)
        const typingDelay = 50;
        const fieldDelay = 400;

        // Utility function to simulate typing
        function typeInField(field, value, callback) {
            let i = 0;
            function typeCharacter() {
                if (i < value.length) {
                    field.value += value.charAt(i);
                    i++;
                    setTimeout(typeCharacter, typingDelay);
                } else if (callback) {
                    setTimeout(callback, fieldDelay);
                }
            }
            typeCharacter();
        }

        // Type in each form field
        function fillForm() {
            const fields = [
                { field: document.getElementById('first_name'), value: formData.first_name },
                { field: document.getElementById('last_name'), value: formData.last_name },
                { field: document.getElementById('display_name'), value: formData.display_name },
                { field: document.getElementById('email'), value: formData.email },
                { field: document.getElementById('phone'), value: formData.phone },
                { field: document.getElementById('address'), value: formData.address },
                { field: document.getElementById('state'), value: formData.state },
                { field: document.getElementById('zip'), value: formData.zip },
                { field: document.getElementById('country'), value: formData.country, select: true },
                { field: document.querySelector(`input[name="occupation"][value="${formData.occupation}"]`), value: formData.occupation, radio: true },
                { field: document.getElementById('self_description'), value: formData.self_description },
                { field: document.getElementById('interest1'), value: formData.interests.includes('Technology'), checkbox: true },
                { field: document.getElementById('interest2'), value: formData.interests.includes('Sports'), checkbox: true },
                { field: document.getElementById('interest3'), value: formData.interests.includes('Music'), checkbox: true },
                { field: document.getElementById('interest4'), value: formData.interests.includes('Travel'), checkbox: true }
            ];

            function processField(index) {
                if (index < fields.length) {
                    const { field, value, select, radio, checkbox } = fields[index];

                    if (select) {
                        setTimeout(() => {
                            field.value = value;
                            field.dispatchEvent(new Event('change'));

                            // Check if this is the country field and change to 'US' after a delay
                            if (field.id === 'country') {
                                setTimeout(() => {
                                    field.value = 'US';
                                    field.dispatchEvent(new Event('change'));
                                    processField(index + 1);
                                }, 1000); // Delay before changing to 'US'
                            } else {
                                processField(index + 1);
                            }
                        }, fieldDelay);
                    } else if (radio) {
                        setTimeout(() => {
                            field.checked = true;
                            processField(index + 1);
                        }, fieldDelay);
                    } else if (checkbox) {
                        setTimeout(() => {
                            if (value) {
                                field.checked = true;
                            }
                            processField(index + 1);
                        }, fieldDelay);
                    } else {
                        typeInField(field, value, () => processField(index + 1));
                    }
                }
            }

            processField(0); // Start processing the first field
        }

        // Start filling the form automatically
        fillForm();
    });
</script>


@endsection
