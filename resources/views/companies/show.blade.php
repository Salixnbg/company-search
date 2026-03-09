<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company->name }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f7fb;
        }

        .detail-wrapper {
            max-width: 900px;
            margin: 0 auto;
        }

        .detail-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .company-title {
            color: #1f3c88;
            font-weight: 700;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
        }

        .info-value {
            color: #212529;
        }
    </style>
</head>

<body>

<div class="container py-5">
    <div class="detail-wrapper">

        <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary mb-4">
            Back to search
        </a>

        @php
            $formattedEnterpriseNumber = $company->enterprise_number
                ? substr($company->enterprise_number, 0, 4) . '.' .
                  substr($company->enterprise_number, 4, 3) . '.' .
                  substr($company->enterprise_number, 7, 3)
                : 'N/A';

            $cleanVatNumber = str_replace('BE', '', $company->vat_number ?? '');

            $formattedVatNumber = $cleanVatNumber
                ? 'BE ' .
                  substr($cleanVatNumber, 0, 4) . '.' .
                  substr($cleanVatNumber, 4, 3) . '.' .
                  substr($cleanVatNumber, 7, 3)
                : 'N/A';
        @endphp

        <div class="card detail-card">
            <div class="card-body p-4 p-md-5">

                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h1 class="company-title mb-2">{{ $company->name }}</h1>

                        @if($company->status)
                            <span class="badge bg-secondary">{{ $company->status }}</span>
                        @endif
                    </div>
                </div>

                <div class="row g-4">

                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light h-100">
                            <p class="mb-2">
                                <span class="info-label">Enterprise number:</span><br>
                                <span class="info-value">{{ $formattedEnterpriseNumber }}</span>
                            </p>

                            <p class="mb-2">
                                <span class="info-label">VAT number:</span><br>
                                <span class="info-value">{{ $formattedVatNumber }}</span>
                            </p>

                            <p class="mb-0">
                                <span class="info-label">Legal form:</span><br>
                                <span class="info-value">{{ $company->legal_form ?: 'N/A' }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light h-100">
                            <p class="mb-2">
                                <span class="info-label">Street:</span><br>
                                <span class="info-value">{{ $company->street ?: 'N/A' }}</span>
                            </p>

                            <p class="mb-2">
                                <span class="info-label">Postal code:</span><br>
                                <span class="info-value">{{ $company->postal_code ?: 'N/A' }}</span>
                            </p>

                            <p class="mb-0">
                                <span class="info-label">City:</span><br>
                                <span class="info-value">{{ $company->city ?: 'N/A' }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="border rounded p-3 bg-light">
                            <p class="mb-0">
                                <span class="info-label">Start date:</span><br>
                                <span class="info-value">{{ $company->start_date ?: 'N/A' }}</span>
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

</body>
</html>