@extends('layouts.adminlte')

@section('title', __('messages.create_employee'))

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('messages.create_employee') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">{{ __('messages.employees') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.create') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('employees.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.employee_information') }}</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="employeeTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab">
                                        {{ __('messages.personal_details') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="job-tab" data-toggle="tab" href="#job" role="tab">
                                        {{ __('messages.job_details') }}
                                    </a>
                                </li>

                            </ul>
                            <div class="tab-content mt-3" id="employeeTabsContent">
                                <!-- Personal Details Tab -->
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="employee_id">{{ __('messages.employee_id') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('employee_id') is-invalid @enderror" 
                                                       id="employee_id" name="employee_id" value="{{ old('employee_id') }}" required>
                                                @error('employee_id')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">{{ __('messages.email') }} <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                       id="email" name="email" value="{{ old('email') }}" required>
                                                @error('email')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="first_name">{{ __('messages.first_name') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                                       id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                                @error('first_name')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="last_name">{{ __('messages.last_name') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                                       id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                                @error('last_name')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone">{{ __('messages.phone') }}</label>
                                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                       id="phone" name="phone" value="{{ old('phone') }}">
                                                @error('phone')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="date_of_birth">{{ __('messages.date_of_birth') }}</label>
                                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                                       id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                                @error('date_of_birth')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="gender">{{ __('messages.gender') }}</label>
                                                <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                                    <option value="">{{ __('messages.select_gender') }}</option>
                                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>{{ __('messages.other') }}</option>
                                                </select>
                                                @error('gender')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">{{ __('messages.address') }}</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="city">{{ __('messages.city') }}</label>
                                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                                       id="city" name="city" value="{{ old('city') }}">
                                                @error('city')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="state">{{ __('messages.state') }}</label>
                                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                                       id="state" name="state" value="{{ old('state') }}">
                                                @error('state')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="postal_code">{{ __('messages.postal_code') }}</label>
                                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                                       id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
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
                                                <label for="department_id">{{ __('messages.department') }} <span class="text-danger">*</span></label>
                                                <select class="form-control @error('department_id') is-invalid @enderror" 
                                                        id="department_id" name="department_id" required>
                                                    <option value="">{{ __('messages.select_department') }}</option>
                                                    @foreach($departments as $dept)
                                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
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
                                                <label for="designation_id">{{ __('messages.designation') }} <span class="text-danger">*</span></label>
                                                <select class="form-control @error('designation_id') is-invalid @enderror" 
                                                        id="designation_id" name="designation_id" required>
                                                    <option value="">{{ __('messages.select_designation') }}</option>
                                                    @foreach($designations as $desig)
                                                        <option value="{{ $desig->id }}" {{ old('designation_id') == $desig->id ? 'selected' : '' }}>
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
                                                <label for="location_id">{{ __('messages.location') }}</label>
                                                <select class="form-control @error('location_id') is-invalid @enderror" 
                                                        id="location_id" name="location_id">
                                                    <option value="">{{ __('messages.select_location') }}</option>
                                                    @foreach($locations as $loc)
                                                        <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
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
                                                <label for="manager_id">{{ __('messages.manager') }}</label>
                                                <select class="form-control @error('manager_id') is-invalid @enderror" 
                                                        id="manager_id" name="manager_id">
                                                    <option value="">{{ __('messages.select_manager') }}</option>
                                                    @foreach($managers as $mgr)
                                                        <option value="{{ $mgr->id }}" {{ old('manager_id') == $mgr->id ? 'selected' : '' }}>
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
                                                <label for="joining_date">{{ __('messages.joining_date') }} <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control @error('joining_date') is-invalid @enderror" 
                                                       id="joining_date" name="joining_date" value="{{ old('joining_date') }}" required>
                                                @error('joining_date')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="employment_type">{{ __('messages.employment_type') }} <span class="text-danger">*</span></label>
                                                <select class="form-control @error('employment_type') is-invalid @enderror" 
                                                        id="employment_type" name="employment_type" required>
                                                    <option value="">{{ __('messages.select_type') }}</option>
                                                    @foreach($employmentTypes as $type)
                                                        <option value="{{ $type->slug }}" 
                                                                {{ old('employment_type') == $type->slug ? 'selected' : '' }}>
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
                                                <label for="ctc">{{ __('messages.ctc') }}</label>
                                                <input type="number" step="0.01" class="form-control @error('ctc') is-invalid @enderror" 
                                                       id="ctc" name="ctc" value="{{ old('ctc') }}">
                                                @error('ctc')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="create_user_account" 
                                                   name="create_user_account" value="1" {{ old('create_user_account', true) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="create_user_account">
                                                {{ __('messages.create_user_account') }}
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle"></i> {{ __('messages.create_user_account_help') }}
                                        </small>
                                    </div>
                                    <div class="form-group" id="password-field">
                                        <label for="password">{{ __('messages.password') }} <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password" 
                                               placeholder="{{ __('messages.password_help') }}"
                                               value="{{ old('password') }}">
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="form-text text-muted">
                                            {{ __('messages.password_help') }}
                                        </small>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.create_employee') }}
                            </button>
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> {{ __('messages.cancel') }}
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

        // Show/hide password field when create user account checkbox changes
        $('#create_user_account').on('change', function() {
            if ($(this).is(':checked')) {
                $('#password-field').show();
                $('#password').prop('required', true);
            } else {
                $('#password-field').hide();
                $('#password').prop('required', false);
            }
        });
        
        // Initialize on page load
        if ($('#create_user_account').is(':checked')) {
            $('#password-field').show();
            $('#password').prop('required', true);
        }
    });
</script>
@endpush

