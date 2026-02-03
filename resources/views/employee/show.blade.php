@extends('layouts.adminlte')

@section('title', 'View Employee')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Employee Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
                    <li class="breadcrumb-item active">View Employee</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $employee->full_name }} ({{ $employee->employee_id }})</h3>
                        <div class="card-tools">
                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('employees.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="employeeTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="job-tab" data-toggle="tab" href="#job" role="tab">
                                    <i class="fas fa-briefcase"></i> Job Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="salary-tab" data-toggle="tab" href="#salary" role="tab">
                                    <i class="fas fa-money-bill-wave"></i> Salary
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" role="tab">
                                    <i class="fas fa-university"></i> Bank Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="kyc-tab" data-toggle="tab" href="#kyc" role="tab">
                                    <i class="fas fa-id-card"></i> KYC Documents
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="employeeTabsContent">
                            <!-- Profile Tab -->
                            <div class="tab-pane fade show active" id="profile" role="tabpanel">
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
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    @if($employee->is_active)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-danger">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <h5 class="mt-4">Emergency Contact</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="20%">Name</th>
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

                            <!-- Job Details Tab -->
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
                                        <th>Confirmation Date</th>
                                        <td>{{ $employee->confirmation_date ? \Carbon\Carbon::parse($employee->confirmation_date)->format('d M Y') : '-' }}</td>
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
                                            @elseif($employee->employment_status === 'inactive')
                                                <span class="badge badge-warning">Inactive</span>
                                            @else
                                                <span class="badge badge-danger">{{ ucfirst($employee->employment_status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Salary Tab -->
                            <div class="tab-pane fade" id="salary" role="tabpanel">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">CTC (Annual)</th>
                                        <td>₹{{ number_format($employee->ctc ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Monthly CTC</th>
                                        <td>₹{{ number_format(($employee->ctc ?? 0) / 12, 2) }}</td>
                                    </tr>
                                    @if($employee->salary_structure)
                                        <tr>
                                            <th>Salary Structure</th>
                                            <td>
                                                <pre>{{ json_encode($employee->salary_structure, JSON_PRETTY_PRINT) }}</pre>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>

                            <!-- Bank Details Tab -->
                            <div class="tab-pane fade" id="bank" role="tabpanel">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Bank Name</th>
                                        <td>{{ $employee->bank_name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Account Number</th>
                                        <td>{{ $employee->bank_account_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>IFSC Code</th>
                                        <td>{{ $employee->bank_ifsc ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Branch</th>
                                        <td>{{ $employee->bank_branch ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- KYC Documents Tab -->
                            <div class="tab-pane fade" id="kyc" role="tabpanel">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">PAN Number</th>
                                        <td>{{ $employee->pan_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Aadhar Number</th>
                                        <td>{{ $employee->aadhar_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Passport Number</th>
                                        <td>{{ $employee->passport_number ?? '-' }}</td>
                                    </tr>
                                </table>
                                <h5 class="mt-4">Onboarding Documents (Uploaded)</h5>
                                @if($employee->documents->isNotEmpty())
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>Document Type</th>
                                                <th>Original Name</th>
                                                <th>Uploaded</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employee->documents as $doc)
                                                <tr>
                                                    <td>{{ $doc->type_label }}</td>
                                                    <td>{{ $doc->original_name ?? '-' }}</td>
                                                    <td>{{ $doc->created_at->format('d M Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('employees.documents.download', [$employee, $doc]) }}" class="btn btn-sm btn-primary" target="_blank" rel="noopener">
                                                            <i class="fas fa-download"></i> Download
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-muted mb-0">No onboarding documents uploaded yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Tab switching
    $('#employeeTabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
</script>
@endpush

