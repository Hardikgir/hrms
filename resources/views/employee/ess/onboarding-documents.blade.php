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
                <form action="{{ route('ess.onboarding-documents.submit') }}" method="POST" enctype="multipart/form-data">
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
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
