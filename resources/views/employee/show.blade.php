@extends('layouts.adminlte')

@section('title', __('messages.view_employee'))

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.employee_details') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('employees.index') }}">{{ __('messages.employees') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.view_employee') }}</li>
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
                                    <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                                </a>
                                <a href="{{ route('employees.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="employeeTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile"
                                        role="tab">
                                        <i class="fas fa-user"></i> {{ __('messages.profile') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="job-tab" data-toggle="tab" href="#job" role="tab">
                                        <i class="fas fa-briefcase"></i> {{ __('messages.job_details') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="salary-tab" data-toggle="tab" href="#salary" role="tab">
                                        <i class="fas fa-money-bill-wave"></i> {{ __('messages.salary') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" role="tab">
                                        <i class="fas fa-university"></i> {{ __('messages.bank_details') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="kyc-tab" data-toggle="tab" href="#kyc" role="tab">
                                        <i class="fas fa-id-card"></i> {{ __('messages.bank_kyc') }}
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
                                                    <th width="40%">{{ __('messages.employee_id') }}</th>
                                                    <td>{{ $employee->employee_id ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('messages.name') }}</th>
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
                                                    <td>{{ ucfirst($employee->gender ?? '-') }}</td>
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
                                                <tr>
                                                    <th>{{ __('messages.status') }}</th>
                                                    <td>
                                                        @if($employee->is_active)
                                                            <span class="badge badge-success">{{ __('messages.active') }}</span>
                                                        @else
                                                            <span
                                                                class="badge badge-danger">{{ __('messages.inactive') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <h5 class="mt-4">{{ __('messages.emergency_contact') }}</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="20%">{{ __('messages.name') }}</th>
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

                                <!-- Job Details Tab -->
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
                                            <th>{{ __('messages.confirmation_date') }}</th>
                                            <td>{{ $employee->confirmation_date ? \Carbon\Carbon::parse($employee->confirmation_date)->format('d M Y') : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.employment_type') }}</th>
                                            <td>{{ ucfirst(str_replace('_', ' ', $employee->employment_type ?? '-')) }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.employment_status') }}</th>
                                            <td>
                                                @if($employee->employment_status === 'active')
                                                    <span class="badge badge-success">{{ __('messages.active') }}</span>
                                                @elseif($employee->employment_status === 'inactive')
                                                    <span class="badge badge-warning">{{ __('messages.inactive') }}</span>
                                                @else
                                                    <span
                                                        class="badge badge-danger">{{ ucfirst($employee->employment_status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Salary Tab -->
                                <div class="tab-pane fade" id="salary" role="tabpanel">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="30%">{{ __('messages.ctc') }}</th>
                                            <td>{{ __('messages.currency_symbol') }}{{ number_format($employee->ctc ?? 0, 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.monthly_ctc') }}</th>
                                            <td>₹{{ number_format(($employee->ctc ?? 0) / 12, 2) }}</td>
                                        </tr>
                                        @if($employee->salary_structure)
                                            <tr>
                                                <th>{{ __('messages.salary_structure') }}</th>
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
                                            <th width="30%">{{ __('messages.bank_name') }}</th>
                                            <td>{{ $employee->bank_name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.account_number') }}</th>
                                            <td>{{ $employee->bank_account_number ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.ifsc_code') }}</th>
                                            <td>{{ $employee->bank_ifsc ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.branch') }}</th>
                                            <td>{{ $employee->bank_branch ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- KYC Documents Tab -->
                                <div class="tab-pane fade" id="kyc" role="tabpanel">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="30%">{{ __('messages.pan_number') }}</th>
                                            <td>{{ $employee->pan_number ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.aadhar_number') }}</th>
                                            <td>{{ $employee->aadhar_number ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.passport_number') }}</th>
                                            <td>{{ $employee->passport_number ?? '-' }}</td>
                                        </tr>
                                    </table>
                                    <h5 class="mt-4">{{ __('messages.onboarding_documents') }}</h5>
                                    @if($employee->documents->isNotEmpty())
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('messages.document_type') }}</th>
                                                    <th>{{ __('messages.name') }}</th>
                                                    <th>{{ __('messages.uploaded') }}</th>
                                                    <th>{{ __('messages.actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($employee->documents as $doc)
                                                    <tr>
                                                        <td>{{ $doc->type_label }}</td>
                                                        <td>{{ $doc->original_name ?? '-' }}</td>
                                                        <td>{{ $doc->created_at->format('d M Y H:i') }}</td>
                                                        <td>
                                                            <a href="{{ route('employees.documents.download', [$employee, $doc]) }}"
                                                                class="btn btn-sm btn-primary" target="_blank" rel="noopener">
                                                                <i class="fas fa-download"></i> {{ __('messages.download') }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-muted mb-0">{{ __('messages.no_documents') }}</p>
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