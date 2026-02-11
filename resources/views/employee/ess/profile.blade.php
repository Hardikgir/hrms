@extends('layouts.ess')

@section('title', __('messages.my_profile'))
@section('page_title', __('messages.my_profile'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.profile') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $employee->full_name }} ({{ $employee->employee_id }})</h3>
                    <div class="card-tools">
                        <a href="{{ route('ess.profile.edit') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> {{ __('messages.edit_profile') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="profileTabs" role="tablist">
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
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab">
                                {{ __('messages.emergency_contact') }}
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="profileTabsContent">
                        <!-- Personal Details -->
                        <div class="tab-pane fade show active" id="personal" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">{{ __('messages.employee_id') }}</th>
                                            <td>{{ $employee->employee_id ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.full_name') }}</th>
                                            <td>{{ $employee->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.email') }}</th>
                                            <td>{{ $employee->email ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.phone') }}</th>
                                            <td>{{ $employee->phone ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.date_of_birth') }}</th>
                                            <td>{{ $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.gender') }}</th>
                                            <td>{{ $employee->gender ? __('messages.' . strtolower($employee->gender)) : '-' }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">{{ __('messages.address') }}</th>
                                            <td>{{ $employee->address ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.city') }}</th>
                                            <td>{{ $employee->city ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.state') }}</th>
                                            <td>{{ $employee->state ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.country') }}</th>
                                            <td>{{ $employee->country ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.postal_code') }}</th>
                                            <td>{{ $employee->postal_code ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Job Details -->
                        <div class="tab-pane fade" id="job" role="tabpanel">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">{{ __('messages.department') }}</th>
                                    <td>{{ $employee->department->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.designation') }}</th>
                                    <td>{{ $employee->designation->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.location') }}</th>
                                    <td>{{ $employee->location->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.manager') }}</th>
                                    <td>{{ $employee->manager ? $employee->manager->full_name : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.joining_date') }}</th>
                                    <td>{{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('d M Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.employment_type') }}</th>
                                    <td>{{ $employee->employment_type ? __('messages.' . strtolower($employee->employment_type)) : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.employment_status') }}</th>
                                    <td>
                                        @if($employee->employment_status === 'active')
                                            <span class="badge badge-success">{{ __('messages.active') }}</span>
                                        @else
                                            <span
                                                class="badge badge-danger">{{ __('messages.' . strtolower($employee->employment_status)) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="tab-pane fade" id="contact" role="tabpanel">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">{{ __('messages.name') }}</th>
                                    <td>{{ $employee->emergency_contact_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.phone') }}</th>
                                    <td>{{ $employee->emergency_contact_phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.relation') }}</th>
                                    <td>{{ $employee->emergency_contact_relation ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#profileTabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    </script>
@endpush