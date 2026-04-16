@extends('layouts.ess')

@section('title', __('messages.leave_details'))
@section('page_title', __('messages.leave_details'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.leaves') }}">{{ __('messages.leaves') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.details') }}</li>
@endsection

@section('content')
    <div class="card card-outline card-info">
        <div class="card-header d-print-none">
            <h3 class="card-title">{{ __('messages.leave_request_details') }}</h3>
            <div class="card-tools">
                <button onclick="window.print()" class="btn btn-sm btn-info mr-2">
                    <i class="fas fa-print"></i> {{ __('messages.print_form') }}
                </button>
                <a href="{{ route('ess.leaves') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_leaves') }}
                </a>
            </div>
        </div>
        <div class="card-body p-0 leave-form-container">
            <style>
                @media print {
                    .main-footer, .main-sidebar, .card-header, .breadcrumb, .content-header, .btn-primary, .btn-secondary, .btn-info {
                        display: none !important;
                    }
                    .content-wrapper { margin-left: 0 !important; padding: 0 !important; }
                    .card { border: none !important; box-shadow: none !important; }
                    body { background: white !important; }
                }
                .leave-form-container { padding: 30px; line-height: 1.6; color: #333; }
                .form-header-box { border: 2px solid #000; padding: 10px; margin-bottom: 20px; text-align: center; }
                .section-title { background: #e9ecef; border: 1px solid #dee2e6; padding: 5px 15px; font-weight: bold; margin-bottom: 10px; text-transform: uppercase; }
                .form-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                .form-table th, .form-table td { border: 1px solid #dee2e6; padding: 10px; vertical-align: middle; }
                .form-table th { background-color: #f8f9fa; text-align: left; width: 20%; font-weight: 600; font-size: 0.9rem; }
                .form-table td { font-size: 0.95rem; }
                .signature-section { margin-top: 40px; display: flex; justify-content: space-between; gap: 20px; }
                .signature-box { flex: 1; border: 1px solid #dee2e6; padding: 15px; text-align: center; min-height: 120px; }
                .signature-line { border-top: 1px solid #333; margin-top: 60px; padding-top: 5px; font-size: 0.85rem; font-weight: bold; }
                .visa-table { width: 100%; border-collapse: collapse; text-align: center; }
                .visa-table th, .visa-table td { border: 1px solid #dee2e6; padding: 8px; }
                .visa-table th { background: #f8f9fa; font-size: 0.85rem; }
            </style>

            <div class="form-header-box">
                <h4 class="mb-0 font-weight-bold">LEAVE APPLICATION & VISA REQUEST FORM</h4>
            </div>

            {{-- Employee Details Row --}}
            <table class="form-table">
                <tr>
                    <th>NAME</th>
                    <td>{{ $leave->employee->full_name }}</td>
                    <th>ID NO</th>
                    <td>{{ $leave->employee->employee_id }}</td>
                </tr>
                <tr>
                    <th>NATIONALITY</th>
                    <td>{{ $leave->employee->nationality ?? 'N/A' }}</td>
                    <th>POSITION TITLE</th>
                    <td>{{ $leave->employee->designation->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>DEPARTMENT</th>
                    <td>{{ $leave->employee->department->name ?? 'N/A' }}</td>
                    <th>DATE OF HIRE</th>
                    <td>{{ $leave->employee->joining_date ? $leave->employee->joining_date->format('d/m/Y') : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>END OF CONTRACT</th>
                    <td colspan="3">{{ $leave->employee->contract_end_date ? $leave->employee->contract_end_date->format('d/m/Y') : 'Permanent' }}</td>
                </tr>
            </table>

            <div class="section-title">SECTION-1: LEAVE APPLICATION</div>
            
            <table class="form-table">
                <tr>
                    <th>LEAVE TYPE</th>
                    <td>{{ $leave->leaveType->name }}</td>
                    <th>TOTAL DAYS</th>
                    <td>{{ $leave->total_days }} Days</td>
                </tr>
                <tr>
                    <th>START DATE</th>
                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}</td>
                    <th>END DATE</th>
                    <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>BALANCE ENTITLEMENT</th>
                    <td>{{ $leave->accrued_entitlement ?? 'N/A' }} Days</td>
                    <th>LAST LEAVE TAKEN</th>
                    <td>
                        @if($leave->last_leave_type)
                            {{ $leave->last_leave_type }} 
                            <span class="text-muted small">({{ \Carbon\Carbon::parse($leave->last_leave_from)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($leave->last_leave_to)->format('d/m/Y') }})</span>
                        @else
                            None
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>REASON</th>
                    <td colspan="3">{{ $leave->reason }}</td>
                </tr>
            </table>

            <div class="section-title">SECTION-2: VISA REQUEST</div>
            
            <table class="form-table">
                <tr>
                    <th style="width: 40%">Visa Fee for Hospital Staff Spouse & Children</th>
                    <td>
                        <div class="d-flex gap-4">
                            <span class="mr-3"><i class="far fa-{{ ($leave->extraDetails->is_hospital_paid ?? false) ? 'check-square' : 'square' }}"></i> Hospital Paid</span>
                            <span><i class="far fa-{{ ($leave->extraDetails->is_employee_paid ?? false) ? 'check-square' : 'square' }}"></i> Employee Paid</span>
                        </div>
                    </td>
                </tr>
            </table>

            <p class="font-weight-bold mb-2 ml-2">Family Members Details:</p>
            <table class="visa-table mb-4">
                <thead>
                    <tr>
                        <th width="40%">NAME</th>
                        <th width="20%">RELATIONSHIP</th>
                        <th width="20%">EXIT RE-ENTRY</th>
                        <th width="20%">FINAL EXIT</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $members = $leave->extraDetails->family_visa_details ?? [];
                    @endphp
                    @forelse($members as $member)
                    @if(!empty($member['name']))
                    <tr>
                        <td class="text-left font-weight-bold">{{ $member['name'] }}</td>
                        <td>{{ $member['relationship'] }}</td>
                        <td><i class="far fa-{{ ($member['exit_reentry'] ?? false) ? 'check-square' : 'square' }}"></i></td>
                        <td><i class="far fa-{{ ($member['final_exit'] ?? false) ? 'check-square' : 'square' }}"></i></td>
                    </tr>
                    @endif
                    @empty
                    <tr><td colspan="4" class="text-muted italic">No family visa details provided.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="section-title">CONTACT INFORMATION (While on leave)</div>
            <table class="form-table">
                <tr>
                    <th>ADDRESS</th>
                    <td colspan="3">{{ $leave->extraDetails->contact_address ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>PHONE</th>
                    <td>{{ $leave->extraDetails->contact_phone ?? 'N/A' }}</td>
                    <th>MOBILE</th>
                    <td>{{ $leave->extraDetails->contact_mobile ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>EMAIL</th>
                    <td colspan="3">{{ $leave->extraDetails->contact_email ?? 'N/A' }}</td>
                </tr>
            </table>

            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line">REQUESTOR SIGNATURE</div>
                    <div class="small text-muted mt-1">{{ $leave->employee->full_name }}</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">DEPT. HEAD SIGNATURE</div>
                    <div class="small text-muted mt-1">Approval Date: _________</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">HR DEPT. SIGNATURE</div>
                    <div class="small text-muted mt-1">
                        @if($leave->hr_approved_at)
                            Approved: {{ $leave->hr_approved_at->format('d/m/Y') }}
                        @else
                            Pending Approval
                        @endif
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">CEO SIGNATURE</div>
                    <div class="small text-muted mt-1">
                        @if($leave->approved_at)
                            Approved: {{ $leave->approved_at->format('d/m/Y') }}
                        @else
                            Final Approval Pending
                        @endif
                    </div>
                </div>
            </div>

            @if($leave->status === 'pending' && !request()->routeIs('ess.leaves.*'))
                <div class="mt-4 d-print-none text-center">
                    <a href="{{ route('ess.leaves.edit', $leave) }}" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-edit"></i> Edit Leave Request
                    </a>
                </div>
            @endif
        </div>
        @if($leave->status === 'pending' && request()->routeIs('ess.leaves.*'))
        <div class="card-footer d-print-none text-center bg-white border-top-0 pb-4">
            <a href="{{ route('ess.leaves.edit', $leave) }}" class="btn btn-primary btn-lg px-5 shadow-sm">
                <i class="fas fa-edit mr-2"></i> {{ __('messages.edit_leave') }}
            </a>
        </div>
        @endif
    </div>
@endsection