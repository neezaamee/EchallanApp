@extends('layout.cms-layout')
@section('page-title', 'Create Medical Request - ')
@section('cms-main-content')
    <div class="container mt-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Create Medical Request</h5>
            </div>
            <div class="card-body bg-light">
                <form method="POST" action="{{ route('medical-requests.store') }}">
                    @csrf

                    @role('doctor')
                        <div class="alert alert-info">
                            <strong>Note:</strong> You are creating a request on behalf of a citizen. Enter CNIC to
                            search for existing records.
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label" for="cnic">CNIC (13 digits)</label>
                                <div class="input-group">
                                    <input type="text" id="cnic" name="cnic" class="form-control" maxlength="13"
                                        required placeholder="Enter CNIC and press tab or click search">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-check-cnic">Search</button>
                                </div>
                                <small class="text-muted" id="cnic-feedback"></small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="full_name">Full Name</label>
                                <input type="text" id="full_name" name="full_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="father_name">Father Name</label>
                                <input type="text" id="father_name" name="father_name" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="phone">Phone (03xxxxxxxxx)</label>
                                <input type="text" id="phone" name="phone" class="form-control" maxlength="11"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="gender">Gender</label>
                                <select id="gender" name="gender" class="form-select" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    @endrole

                    @role('citizen')
                        <!-- Province -->
                        <div class="mb-3">
                            <label class="form-label" for="province_id">{{ __('Province') }}</label>
                            <select id="province_id" name="province_id" class="form-select">
                                <option value="">Select Province</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- City -->
                        <div class="mb-3">
                            <label class="form-label" for="city_id">{{ __('City') }}</label>
                            <select id="city_id" name="city_id" class="form-select" disabled>
                                <option value="">Select City</option>
                            </select>
                        </div>

                        <!-- Medical Center -->
                        <div class="mb-3">
                            <label class="form-label" for="medical_center_id">{{ __('Medical Center') }}</label>
                            <select id="medical_center_id" name="medical_center_id" class="form-select" disabled>
                                <option value="">Select Medical Center</option>
                            </select>
                            @error('medical_center_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    @endrole

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Submit Request') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Doctor Flow: CNIC Lookup
            const cnicInput = document.getElementById('cnic');
            const btnCheckCnic = document.getElementById('btn-check-cnic');
            const fullNameInput = document.getElementById('full_name');
            const fatherNameInput = document.getElementById('father_name');
            const phoneInput = document.getElementById('phone');
            const genderSelect = document.getElementById('gender');
            const cnicFeedback = document.getElementById('cnic-feedback');

            if (cnicInput && btnCheckCnic) {
                const checkCnic = () => {
                    const cnic = cnicInput.value;
                    if (cnic.length === 13) {
                        cnicFeedback.textContent = 'Searching...';
                        fetch(`/api/citizens/check/${cnic}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.found) {
                                    cnicFeedback.textContent = 'Citizen found. Details populated.';
                                    cnicFeedback.className = 'text-success';

                                    fullNameInput.value = data.citizen.full_name || '';
                                    fatherNameInput.value = data.citizen.father_name || '';
                                    phoneInput.value = data.citizen.phone || '';
                                    genderSelect.value = data.citizen.gender || '';

                                    // Optional: Disable fields if you don't want them edited
                                    // fullNameInput.readOnly = true;
                                    // fatherNameInput.readOnly = true;
                                    // phoneInput.readOnly = true;
                                    // genderSelect.disabled = true; 
                                } else {
                                    cnicFeedback.textContent = 'Citizen not found. Please enter details.';
                                    cnicFeedback.className = 'text-info';

                                    // Clear fields if not found (or keep what user typed?)
                                    // Better to clear to avoid confusion if they typed something then changed CNIC
                                    fullNameInput.value = '';
                                    fatherNameInput.value = '';
                                    phoneInput.value = '';
                                    genderSelect.value = '';

                                    fullNameInput.readOnly = false;
                                    fatherNameInput.readOnly = false;
                                    phoneInput.readOnly = false;
                                    genderSelect.disabled = false;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                cnicFeedback.textContent = 'Error checking CNIC.';
                                cnicFeedback.className = 'text-danger';
                            });
                    } else {
                        cnicFeedback.textContent = 'Please enter a valid 13-digit CNIC.';
                        cnicFeedback.className = 'text-danger';
                    }
                };

                btnCheckCnic.addEventListener('click', checkCnic);
                cnicInput.addEventListener('blur', checkCnic);
            }

            // Citizen Flow: Location Dropdowns
            const provinceSelect = document.getElementById('province_id');
            const citySelect = document.getElementById('city_id');
            const centerSelect = document.getElementById('medical_center_id');

            if (provinceSelect) {
                provinceSelect.addEventListener('change', function() {
                    const provinceId = this.value;
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    citySelect.disabled = true;
                    centerSelect.innerHTML = '<option value="">Select Medical Center</option>';
                    centerSelect.disabled = true;

                    if (provinceId) {
                        fetch(`/api/provinces/${provinceId}/cities`)
                            .then(response => {
                                if (!response.ok) throw new Error('Network response was not ok');
                                return response.json();
                            })
                            .then(data => {
                                data.forEach(city => {
                                    const option = document.createElement('option');
                                    option.value = city.id;
                                    option.textContent = city.name;
                                    citySelect.appendChild(option);
                                });
                                citySelect.disabled = false;
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });
            }

            if (citySelect) {
                citySelect.addEventListener('change', function() {
                    const cityId = this.value;
                    centerSelect.innerHTML = '<option value="">Select Medical Center</option>';
                    centerSelect.disabled = true;

                    if (cityId) {
                        fetch(`/api/cities/${cityId}/medical-centers`)
                            .then(response => response.json())
                            .then(data => {
                                data.forEach(center => {
                                    const option = document.createElement('option');
                                    option.value = center.id;
                                    option.textContent = center.name;
                                    centerSelect.appendChild(option);
                                });
                                centerSelect.disabled = false;
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });
            }
        });
    </script>
@endsection
