<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Company</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="mb-4">Create Company</h1>

    <div class="mb-3">
        <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.companies.store') }}" method="POST" class="card p-4">
        @csrf

        <div class="mb-3">
            <label class="form-label">Enterprise Number</label>
            <input type="text" name="enterprise_number" class="form-control" value="{{ old('enterprise_number') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">VAT Number</label>
            <input type="text" name="vat_number" class="form-control" value="{{ old('vat_number') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <input type="text" name="status" class="form-control" value="{{ old('status') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Legal Form</label>
            <input type="text" name="legal_form" class="form-control" value="{{ old('legal_form') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Street</label>
            <input type="text" name="street" class="form-control" value="{{ old('street') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Postal Code</label>
            <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" value="{{ old('city') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
        </div>

        <button class="btn btn-primary">Create</button>
    </form>
</div>

</body>
</html>