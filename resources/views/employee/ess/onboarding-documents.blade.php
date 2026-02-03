@extends('layouts.ess')

@section('title', 'Onboarding Documents')
@section('page_title', 'Onboarding Documents')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.tasks') }}">Tasks</a></li>
    <li class="breadcrumb-item active">Onboarding Documents</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Submit Onboarding Documents</h3>
                <div class="card-tools">
                    <a href="{{ route('ess.tasks') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Back to Tasks</a>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">Please upload the following documents to complete your onboarding. Accepted formats: PDF, JPG, PNG (max 5 MB each).</p>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Document</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requiredDocs as $index => $doc)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $doc['label'] }}</strong></td>
                                    <td>{{ $doc['description'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>
                <h5 class="mb-3">Upload a document</h5>
                <form action="{{ route('ess.onboarding-documents.submit') }}" method="POST" enctype="multipart/form-data" id="onboarding-doc-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="document_type">Document type</label>
                                <select name="document_type" id="document_type" class="form-control @error('document_type') is-invalid @enderror" required>
                                    <option value="">Select document</option>
                                    @foreach($requiredDocs as $doc)
                                        <option value="{{ $doc['key'] }}" {{ old('document_type') === $doc['key'] ? 'selected' : '' }}>{{ $doc['label'] }}</option>
                                    @endforeach
                                </select>
                                @error('document_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="document">File (PDF, JPG, PNG – max 5 MB)</label>
                                <input type="file" name="document" id="document" class="form-control-file @error('document') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png" required>
                                @error('document')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload</button>
                        </div>
                    </div>

                    {{-- Detail fields shown based on document type --}}
                    <div id="doc-details" class="row mt-3" style="display: none;">
                        <div class="col-md-12">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Enter document details</h5>
                                </div>
                                <div class="card-body">
                                    <div id="aadhar-fields" class="doc-type-fields" style="display: none;">
                                        <div class="form-group">
                                            <label for="aadhar_number">Aadhar Number <span class="text-danger">*</span></label>
                                            <input type="text" name="aadhar_number" id="aadhar_number" class="form-control @error('aadhar_number') is-invalid @enderror" maxlength="12" pattern="[0-9]{12}" placeholder="12-digit Aadhar number" value="{{ old('aadhar_number', $employee->aadhar_number ?? '') }}">
                                            <small class="form-text text-muted">Enter 12-digit Aadhar number (digits only).</small>
                                            @error('aadhar_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div id="pan-fields" class="doc-type-fields" style="display: none;">
                                        <div class="form-group">
                                            <label for="pan_number">PAN Number <span class="text-danger">*</span></label>
                                            <input type="text" name="pan_number" id="pan_number" class="form-control @error('pan_number') is-invalid @enderror" maxlength="10" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" placeholder="e.g. ABCDE1234F" value="{{ old('pan_number', $employee->pan_number ?? '') }}">
                                            <small class="form-text text-muted">Format: 5 letters, 4 digits, 1 letter (e.g. ABCDE1234F).</small>
                                            @error('pan_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div id="bank-fields" class="doc-type-fields" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bank_account_number">Bank Account Number <span class="text-danger">*</span></label>
                                                    <input type="text" name="bank_account_number" id="bank_account_number" class="form-control @error('bank_account_number') is-invalid @enderror" placeholder="Account number" value="{{ old('bank_account_number', $employee->bank_account_number ?? '') }}">
                                                    @error('bank_account_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bank_ifsc">IFSC Code <span class="text-danger">*</span></label>
                                                    <input type="text" name="bank_ifsc" id="bank_ifsc" class="form-control @error('bank_ifsc') is-invalid @enderror" maxlength="11" placeholder="e.g. HDFC0001234" value="{{ old('bank_ifsc', $employee->bank_ifsc ?? '') }}">
                                                    @error('bank_ifsc')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bank_name">Bank Name</label>
                                                    <input type="text" name="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" placeholder="e.g. HDFC Bank" value="{{ old('bank_name', $employee->bank_name ?? '') }}">
                                                    @error('bank_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bank_branch">Branch</label>
                                                    <input type="text" name="bank_branch" id="bank_branch" class="form-control @error('bank_branch') is-invalid @enderror" placeholder="Branch name" value="{{ old('bank_branch', $employee->bank_branch ?? '') }}">
                                                    @error('bank_branch')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var docType = document.getElementById('document_type');
    var docDetails = document.getElementById('doc-details');
    var aadharFields = document.getElementById('aadhar-fields');
    var panFields = document.getElementById('pan-fields');
    var bankFields = document.getElementById('bank-fields');
    var aadharInput = document.getElementById('aadhar_number');
    var panInput = document.getElementById('pan_number');
    var bankAccInput = document.getElementById('bank_account_number');
    var bankIfscInput = document.getElementById('bank_ifsc');

    function toggleDetails() {
        var type = docType.value;
        aadharFields.style.display = 'none';
        panFields.style.display = 'none';
        bankFields.style.display = 'none';
        if (aadharInput) { aadharInput.removeAttribute('required'); }
        if (panInput) { panInput.removeAttribute('required'); }
        if (bankAccInput) { bankAccInput.removeAttribute('required'); }
        if (bankIfscInput) { bankIfscInput.removeAttribute('required'); }

        if (type === 'aadhar') {
            docDetails.style.display = 'flex';
            aadharFields.style.display = 'block';
            if (aadharInput) { aadharInput.setAttribute('required', 'required'); }
        } else if (type === 'pan') {
            docDetails.style.display = 'flex';
            panFields.style.display = 'block';
            if (panInput) { panInput.setAttribute('required', 'required'); }
        } else if (type === 'bank_passbook') {
            docDetails.style.display = 'flex';
            bankFields.style.display = 'block';
            if (bankAccInput) { bankAccInput.setAttribute('required', 'required'); }
            if (bankIfscInput) { bankIfscInput.setAttribute('required', 'required'); }
        } else {
            docDetails.style.display = 'none';
        }
    }

    if (docType) {
        docType.addEventListener('change', toggleDetails);
        // Run on load so correct fields show when returning with validation errors or old input
        toggleDetails();
    }
})();
</script>
@endpush
