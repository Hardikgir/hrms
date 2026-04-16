<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary no-print-shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-file-alt mr-1"></i> {{ __('LEAVE REQUEST FORM') }}
                </h3>
                <div class="card-tools d-print-none">
                    <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                        <i class="fas fa-print"></i> Print Form
                    </button>
                </div>
            </div>
            
            <div class="card-body p-4" id="printable-form">
                <!-- Header / Logo Section -->
                <div class="d-flex justify-content-between mb-4 header-logo-row">
                    <div style="width: 150px;">
                        <!-- Placeholder for Logo -->
                        <div class="bg-light d-flex align-items-center justify-content-center border" style="height: 60px;">
                            <span class="text-muted small">LOGO</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <h4 class="font-weight-bold mb-0">LEAVE REQUEST FORM</h4>
                    </div>
                    <div style="width: 150px;" class="text-right small text-muted pt-2">
                        Page 1 of 1
                    </div>
                </div>

                <!-- Employee Information Table -->
                <table class="table table-bordered table-sm mb-4 emp-info-table">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 20%;">Employee Name:</th>
                            <td style="width: 30%;">{{ $employee_name }}</td>
                            <th class="bg-light" style="width: 20%;">ID No:</th>
                            <td style="width: 30%;">{{ $id_no }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Nationality:</th>
                            <td>{{ $nationality }}</td>
                            <th class="bg-light">Position Title:</th>
                            <td>{{ $position_title }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Department:</th>
                            <td>{{ $department_name }}</td>
                            <th class="bg-light">Date of Hire:</th>
                            <td>{{ $date_of_hire }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">End of Contract:</th>
                            <td colspan="3">{{ $end_of_contract }}</td>
                        </tr>
                    </tbody>
                </table>

                <form wire:submit.prevent="submit">
                    <!-- SECTION-1: LEAVE APPLICATION -->
                    <div class="section-title mb-2"><strong>SECTION-1: LEAVE APPLICATION</strong></div>
                    
                    <table class="table table-bordered table-sm mb-4 leave-type-table">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 40%;">LEAVE TYPE</th>
                                <th class="text-center">FROM</th>
                                <th class="text-center">TO</th>
                                <th class="text-center">DAYS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->leaveTypes as $type)
                            <tr>
                                <td>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="type_{{ $type->id }}" value="{{ $type->id }}" wire:model.live="leave_type_id">
                                        <label for="type_{{ $type->id }}" class="custom-control-label font-weight-normal">{{ $type->name }}</label>
                                    </div>
                                </td>
                                @if($leave_type_id == $type->id)
                                <td><input type="date" class="form-control form-control-sm border-0 bg-transparent text-center" wire:model.live="start_date"></td>
                                <td><input type="date" class="form-control form-control-sm border-0 bg-transparent text-center" wire:model.live="end_date"></td>
                                <td class="text-center font-weight-bold">{{ $this->total_days }}</td>
                                @else
                                <td></td><td></td><td></td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row d-print-none">
                        <div class="col-12">
                            @error('leave_type_id') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                            @error('start_date') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                            @error('end_date') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Summary Table -->
                    <table class="table table-bordered table-sm mb-4 summary-table">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 40%;">Accrued leave entitlement as of today:</th>
                                <td colspan="3" class="font-weight-bold">{{ $accrued_entitlement }} Days</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Total leave days requested:</th>
                                <td colspan="3" class="font-weight-bold text-primary">{{ $this->total_days }} Days</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Last leave taken:</th>
                                <td style="width: 20%;"><strong>Type:</strong> {{ $last_leave_type ?: 'N/A' }}</td>
                                <td style="width: 20%;"><strong>From:</strong> {{ $last_leave_from ?: 'N/A' }}</td>
                                <td style="width: 20%;"><strong>To:</strong> {{ $last_leave_to ?: 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Reason -->
                    <div class="form-group mb-4">
                        <label class="font-weight-normal">Briefly reason if others or special request:</label>
                        <textarea class="form-control form-control-sm" rows="2" wire:model="reason" placeholder="Enter reason..."></textarea>
                        @error('reason') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <!-- SECTION-2: VISA REQUEST -->
                    <div class="section-title mb-2"><strong>SECTION-2: VISA REQUEST (if applicable)</strong></div>
                    
                    <div class="row mt-2 mb-3">
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="hospital_paid" wire:model="is_hospital_paid">
                                <label for="hospital_paid" class="custom-control-label font-weight-normal">Hospital Paid</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="employee_paid" wire:model="is_employee_paid">
                                <label for="employee_paid" class="custom-control-label font-weight-normal">Employee Paid</label>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered table-sm mb-4 visa-table">
                        <thead class="bg-light text-center">
                            <tr>
                                <th>Names (Family member joining)</th>
                                <th style="width: 20%;">Relationship</th>
                                <th style="width: 15%;">Exit & Re-entry</th>
                                <th style="width: 15%;">Final Exit</th>
                                <th class="d-print-none" style="width: 50px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($family_visa_details as $index => $row)
                            <tr>
                                <td><input type="text" class="form-control form-control-sm border-0 bg-transparent" wire:model="family_visa_details.{{ $index }}.name" placeholder="Name"></td>
                                <td><input type="text" class="form-control form-control-sm border-0 bg-transparent text-center" wire:model="family_visa_details.{{ $index }}.relationship" placeholder="Relationship"></td>
                                <td class="text-center">
                                    <input type="checkbox" wire:model="family_visa_details.{{ $index }}.exit_reentry">
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" wire:model="family_visa_details.{{ $index }}.final_exit">
                                </td>
                                <td class="text-center d-print-none">
                                    @if(count($family_visa_details) > 1)
                                        <button type="button" class="btn btn-xs btn-danger" wire:click="removeFamilyMember({{ $index }})"><i class="fas fa-times"></i></button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="d-print-none">
                            <tr>
                                <td colspan="5" class="text-right p-1">
                                    <button type="button" class="btn btn-xs btn-outline-info" wire:click="addFamilyMember"><i class="fas fa-plus mr-1"></i>Add Row</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- Contact Details -->
                    <div class="contact-section p-3 mb-4 border bg-light-gray">
                        <div class="row">
                            <div class="col-md-12 mb-2"><strong>Contact address & phone nos. while on leave:</strong></div>
                            <div class="col-md-12 mb-3">
                                <textarea class="form-control form-control-sm bg-transparent" rows="2" wire:model="contact_address" placeholder="International/Local Address..."></textarea>
                                @error('contact_address') <p class="text-danger small mb-0">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend"><span class="input-group-text bg-transparent border-0 font-weight-bold">Phone:</span></div>
                                    <input type="text" class="form-control border-top-0 border-left-0 border-right-0 bg-transparent shadow-none" wire:model="contact_phone">
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend"><span class="input-group-text bg-transparent border-0 font-weight-bold">Mobile:</span></div>
                                    <input type="text" class="form-control border-top-0 border-left-0 border-right-0 bg-transparent shadow-none @error('contact_mobile') is-invalid @enderror" wire:model="contact_mobile">
                                </div>
                                @error('contact_mobile') <p class="text-danger small mb-0">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend"><span class="input-group-text bg-transparent border-0 font-weight-bold">Email:</span></div>
                                    <input type="email" class="form-control border-top-0 border-left-0 border-right-0 bg-transparent shadow-none @error('contact_email') is-invalid @enderror" wire:model="contact_email">
                                </div>
                                @error('contact_email') <p class="text-danger small mb-0">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Attachment Section (Optional) -->
                    <div class="form-group d-print-none mb-5 border-top pt-3">
                        <label class="font-weight-bold">Upload Scanned Copy / Photograph of form <span class="text-muted small font-weight-normal">(Optional)</span></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="attachment" wire:model="attachment">
                            <label class="custom-file-label" for="attachment">{{ $attachment ? $attachment->getClientOriginalName() : 'Choose file...' }}</label>
                        </div>
                        <div wire:loading wire:target="attachment" class="text-primary small mt-1">Uploading...</div>
                        @error('attachment') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Signatures Section -->
                    <div class="row signature-row mt-5 pt-4">
                        <div class="col-3 text-center">
                            <div class="sig-box border-top mx-1 pt-2 mt-4">
                                <p class="mb-0 font-weight-bold text-uppercase small">Requestor</p>
                                <p class="mb-0 x-small text-muted">Signature & Date</p>
                            </div>
                        </div>
                        <div class="col-3 text-center">
                            <div class="sig-box border-top mx-1 pt-2 mt-4">
                                <p class="mb-0 font-weight-bold text-uppercase small">Dept. Head</p>
                                <p class="mb-0 x-small text-muted">Signature & Date</p>
                            </div>
                        </div>
                        <div class="col-3 text-center">
                            <div class="sig-box border-top mx-1 pt-2 mt-4">
                                <p class="mb-0 font-weight-bold text-uppercase small">HR Admin</p>
                                <p class="mb-0 x-small text-muted">Signature & Date</p>
                            </div>
                        </div>
                        <div class="col-3 text-center">
                            <div class="sig-box border-top mx-1 pt-2 mt-4">
                                <p class="mb-0 font-weight-bold text-uppercase small">CEO</p>
                                <p class="mb-0 x-small text-muted">Signature & Date</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer / Submit Area -->
                    <div class="mt-5 d-print-none text-right border-top pt-3">
                        @if ($errors->has('submit'))
                            <div class="alert alert-danger d-inline-block mr-2 py-1 px-2 mb-0 small">{{ $errors->first('submit') }}</div>
                        @endif
                        
                        <a href="{{ auth()->user()->employee ? route('ess.leaves') : route('leaves.index') }}" class="btn btn-default mr-2" wire:navigate>
                            {{ __('Cancel') }}
                        </a>

                        <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled">
                            <span wire:loading wire:target="submit" class="spinner-border spinner-border-sm mr-1"></span>
                            {{ __('Submit Request') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .section-title {
        background-color: #343a40;
        color: white;
        padding: 4px 10px;
        font-size: 0.9rem;
    }
    .bg-light-gray {
        background-color: #f8f9fa;
    }
    .x-small {
        font-size: 0.7rem;
    }
    .sig-box {
        border-top: 1px solid #000 !important;
    }
    
    @media print {
        @page {
            size: A4;
            margin: 1cm;
        }
        .main-sidebar, .main-header, .main-footer, .content-header, .d-print-none, .btn, .card-header .card-tools {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
            background: white !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-body {
            padding: 0 scale-down !important;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #000 !important;
        }
        .bg-light, .bg-light-gray {
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
        }
        .section-title {
            background-color: #000 !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
        }
        input[type="text"], input[type="date"], textarea {
            border: none !important;
            padding: 0 !important;
            font-weight: bold;
        }
        /* Fix checkboxes/radios for printing */
        input[type="checkbox"], input[type="radio"] {
            -webkit-appearance: checkbox;
            appearance: checkbox;
        }
        .signature-row {
            margin-top: 80px !important;
        }
        .header-logo-row {
            margin-bottom: 20px !important;
        }
    }
    
    /* UI Refinements */
    .emp-info-table th { width: 150px; }
    .form-control:focus { box-shadow: none; border-color: #ced4da; }
    .custom-control-label { cursor: pointer; }
</style>