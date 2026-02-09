@extends('layouts.adminlte')

@section('title', 'Edit Employee')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Employee</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('employees.update', $employee) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Employee: {{ $employee->full_name }}</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="employeeTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab">
                                        Personal Details
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="job-tab" data-toggle="tab" href="#job" role="tab">
                                        Job Details
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" role="tab">
                                        Bank & KYC
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content mt-3" id="employeeTabsContent">
                                <!-- Personal Details Tab -->
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="employee_id">Employee ID <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('employee_id') is-invalid @enderror" 
                                                       id="employee_id" name="employee_id" value="{{ old('employee_id', $employee->employee_id) }}" required>
                                                @error('employee_id')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                       id="email" name="email" value="{{ old('email', $employee->email) }}" required>
                                                @error('email')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="first_name">First Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                                       id="first_name" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required>
                                                @error('first_name')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                                       id="last_name" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required>
                                                @error('last_name')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                       id="phone" name="phone" value="{{ old('phone', $employee->phone) }}">
                                                @error('phone')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="date_of_birth">Date of Birth</label>
                                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                                       id="date_of_birth" name="date_of_birth" 
                                                       value="{{ old('date_of_birth', $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->format('Y-m-d') : '') }}">
                                                @error('date_of_birth')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="gender">Gender</label>
                                                <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                                    <option value="">Select Gender</option>
                                                    <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                                    <option value="other" {{ old('gender', $employee->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error('gender')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" name="address" rows="2">{{ old('address', $employee->address) }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="city">City</label>
                                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                                       id="city" name="city" value="{{ old('city', $employee->city) }}">
                                                @error('city')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="state">State</label>
                                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                                       id="state" name="state" value="{{ old('state', $employee->state) }}">
                                                @error('state')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="postal_code">Postal Code</label>
                                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                                       id="postal_code" name="postal_code" value="{{ old('postal_code', $employee->postal_code) }}">
                                                @error('postal_code')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Job Details Tab -->
                                <div class="tab-pane fade" id="job" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="department_id">Department <span class="text-danger">*</span></label>
                                                <select class="form-control @error('department_id') is-invalid @enderror" 
                                                        id="department_id" name="department_id" required>
                                                    <option value="">Select Department</option>
                                                    @foreach($departments as $dept)
                                                        <option value="{{ $dept->id }}" 
                                                                {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>
                                                            {{ $dept->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('department_id')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="designation_id">Designation <span class="text-danger">*</span></label>
                                                <select class="form-control @error('designation_id') is-invalid @enderror" 
                                                        id="designation_id" name="designation_id" required>
                                                    <option value="">Select Designation</option>
                                                    @foreach($designations as $desig)
                                                        <option value="{{ $desig->id }}" 
                                                                {{ old('designation_id', $employee->designation_id) == $desig->id ? 'selected' : '' }}>
                                                            {{ $desig->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('designation_id')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="location_id">Location</label>
                                                <select class="form-control @error('location_id') is-invalid @enderror" 
                                                        id="location_id" name="location_id">
                                                    <option value="">Select Location</option>
                                                    @foreach($locations as $loc)
                                                        <option value="{{ $loc->id }}" 
                                                                {{ old('location_id', $employee->location_id) == $loc->id ? 'selected' : '' }}>
                                                            {{ $loc->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('location_id')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="manager_id">Manager</label>
                                                <select class="form-control @error('manager_id') is-invalid @enderror" 
                                                        id="manager_id" name="manager_id">
                                                    <option value="">Select Manager</option>
                                                    @foreach($managers as $mgr)
                                                        <option value="{{ $mgr->id }}" 
                                                                {{ old('manager_id', $employee->manager_id) == $mgr->id ? 'selected' : '' }}>
                                                            {{ $mgr->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('manager_id')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="joining_date">Joining Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control @error('joining_date') is-invalid @enderror" 
                                                       id="joining_date" name="joining_date" 
                                                       value="{{ old('joining_date', $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('Y-m-d') : '') }}" required>
                                                @error('joining_date')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="employment_type">Employment Type <span class="text-danger">*</span></label>
                                                <select class="form-control @error('employment_type') is-invalid @enderror" 
                                                        id="employment_type" name="employment_type" required>
                                                    <option value="">Select Type</option>
                                                    @foreach($employmentTypes as $type)
                                                        <option value="{{ $type->slug }}" 
                                                                {{ old('employment_type', $employee->employment_type) == $type->slug ? 'selected' : '' }}>
                                                            {{ $type->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('employment_type')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="employment_status">Employment Status <span class="text-danger">*</span></label>
                                                <select class="form-control @error('employment_status') is-invalid @enderror" 
                                                        id="employment_status" name="employment_status" required>
                                                    <option value="">Select Status</option>
                                                    @foreach($employmentStatuses as $status)
                                                        <option value="{{ $status->slug }}" 
                                                                {{ old('employment_status', $employee->employment_status) == $status->slug ? 'selected' : '' }}>
                                                            {{ $status->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('employment_status')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="ctc">CTC (Annual)</label>
                                                <input type="number" step="0.01" class="form-control @error('ctc') is-invalid @enderror" 
                                                       id="ctc" name="ctc" value="{{ old('ctc', $employee->ctc) }}">
                                                @error('ctc')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bank & KYC Tab -->
                                <div class="tab-pane fade" id="bank" role="tabpanel">
                                    <h5>Bank Details</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="bank_name">Bank Name</label>
                                                <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                                                       id="bank_name" name="bank_name" value="{{ old('bank_name', $employee->bank_name) }}">
                                                @error('bank_name')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="bank_account_number">Account Number</label>
                                                <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" 
                                                       id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $employee->bank_account_number) }}">
                                                @error('bank_account_number')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="bank_ifsc">IFSC Code</label>
                                                <input type="text" class="form-control @error('bank_ifsc') is-invalid @enderror" 
                                                       id="bank_ifsc" name="bank_ifsc" value="{{ old('bank_ifsc', $employee->bank_ifsc) }}">
                                                @error('bank_ifsc')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="bank_branch">Branch</label>
                                                <input type="text" class="form-control @error('bank_branch') is-invalid @enderror" 
                                                       id="bank_branch" name="bank_branch" value="{{ old('bank_branch', $employee->bank_branch) }}">
                                                @error('bank_branch')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5>KYC Documents</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="pan_number">PAN Number</label>
                                                <input type="text" class="form-control @error('pan_number') is-invalid @enderror" 
                                                       id="pan_number" name="pan_number" value="{{ old('pan_number', $employee->pan_number) }}">
                                                @error('pan_number')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="aadhar_number">Aadhar Number</label>
                                                <input type="text" class="form-control @error('aadhar_number') is-invalid @enderror" 
                                                       id="aadhar_number" name="aadhar_number" value="{{ old('aadhar_number', $employee->aadhar_number) }}">
                                                @error('aadhar_number')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Employee
                            </button>
                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Tab switching
        $('#employeeTabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
@endpush

