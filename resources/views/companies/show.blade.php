<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $company->name }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <a href="{{ route('companies.index') }}" class="btn btn-secondary mb-4">
        Back
    </a>

    <div class="card">
        <div class="card-body">

            <h1 class="mb-4">{{ $company->name }}</h1>

            <p><strong>Enterprise number:</strong> {{ $company->enterprise_number }}</p>
            <p><strong>VAT number:</strong> {{ $company->vat_number ?: 'N/A' }}</p>
            <p><strong>Status:</strong> {{ $company->status ?: 'N/A' }}</p>
            <p><strong>Legal form:</strong> {{ $company->legal_form ?: 'N/A' }}</p>
            <p><strong>Street:</strong> {{ $company->street ?: 'N/A' }}</p>
            <p><strong>Postal code:</strong> {{ $company->postal_code ?: 'N/A' }}</p>
            <p><strong>City:</strong> {{ $company->city ?: 'N/A' }}</p>
            <p><strong>Start date:</strong> {{ $company->start_date ?: 'N/A' }}</p>

        </div>
    </div>

</div>

</body>
</html>