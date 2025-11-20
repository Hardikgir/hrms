@extends('layouts.ess')

@section('title', 'My Profile')
@section('page_title', 'My Profile')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $employee->full_name }} ({{ $employee->employee_id }})</h3>
                <div class="card-tools">
                    <a href="{{ route('ess.profile.edit') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="profileTabs" role="tablist">
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
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab">
                            Emergency Contact
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
                                        <th width="40%">Employee ID</th>
                                        <td>{{ $employee->employee_id ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Full Name</th>
                                        <td>{{ $employee->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $employee->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $employee->phone ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date of Birth</th>
                                        <td>{{ $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Gender</th>
                                        <td>{{ ucfirst($employee->gender ?? '-') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Address</th>
                                        <td>{{ $employee->address ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>City</th>
                                        <td>{{ $employee->city ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>State</th>
                                        <td>{{ $employee->state ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Country</th>
                                        <td>{{ $employee->country ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Postal Code</th>
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
                                <th width="30%">Department</th>
                                <td>{{ $employee->department->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Designation</th>
                                <td>{{ $employee->designation->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td>{{ $employee->location->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Manager</th>
                                <td>{{ $employee->manager ? $employee->manager->full_name : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Joining Date</th>
                                <td>{{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('d M Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Employment Type</th>
                                <td>{{ ucfirst(str_replace('_', ' ', $employee->employment_type ?? '-')) }}</td>
                            </tr>
                            <tr>
                                <th>Employment Status</th>
                                <td>
                                    @if($employee->employment_status === 'active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">{{ ucfirst($employee->employment_status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="tab-pane fade" id="contact" role="tabpanel">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Name</th>
                                <td>{{ $employee->emergency_contact_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $employee->emergency_contact_phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Relation</th>
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

