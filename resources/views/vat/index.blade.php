<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VAT Check</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fb;
        }

        .wrapper {
            max-width: 800px;
            margin: 0 auto;
        }

        .card-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="wrapper">
        <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary mb-4">
            Back to company search
        </a>

        <div class="card card-custom">
            <div class="card-body p-4">
                <h1 class="h3 mb-3">VAT Number Check</h1>
                <p class="text-muted">
                    Check a Belgian VAT number through VIES
                </p>

                <form method="POST" action="{{ route('vat.check') }}">
                    @csrf

                    <div class="input-group mb-3">
                        <input
                            type="text"
                            name="vat_number"
                            class="form-control"
                            placeholder="Example: BE0123456789"
                            value="{{ old('vat_number', $inputVat ?? '') }}"
                        >
                        <button class="btn btn-primary">Check</button>
                    </div>
                </form>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first('vat_number') }}
                    </div>
                @endif

                @if (!empty($error))
                    <div class="alert alert-warning">
                        {{ $error }}
                    </div>
                @endif

                @if (!empty($result))
                    <div class="border rounded p-3 bg-light">
                        <p><strong>Country code:</strong> {{ $result['countryCode'] }}</p>
                        <p><strong>VAT number:</strong> {{ $result['vatNumber'] }}</p>
                        <p>
                            <strong>Valid:</strong>
                            @if($result['valid'])
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-danger">No</span>
                            @endif
                        </p>
                        <p><strong>Name:</strong> {{ $result['name'] ?: 'Not available' }}</p>
                        <p><strong>Address:</strong><br>{{ $result['address'] ?: 'Not available' }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

</body>
</html>