<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Companies</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f7fb;
        }

        input.form-control {
            color: rgba(0,0,0,0.65);
        }

        input.form-control::placeholder {
            color: rgba(0,0,0,0.35);
        }

        .top-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .mobile-company-card {
            display: none;
        }

        @media (max-width: 768px) {
            .container.py-5 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }

            .header-mobile {
                display: block !important;
            }

            .header-mobile h1 {
                margin-bottom: 1rem;
                font-size: 1.5rem;
            }

            .header-mobile .btn {
                width: 100%;
            }

            .top-actions > * {
                width: 100%;
            }

            .top-actions .btn,
            .top-actions form,
            .top-actions form button {
                width: 100%;
            }

            .search-row-mobile .btn {
                width: 100%;
            }

            .desktop-table {
                display: none;
            }

            .mobile-company-card {
                display: block;
            }

            .mobile-company-card .card {
                border: none;
                border-radius: 14px;
                box-shadow: 0 8px 24px rgba(0,0,0,0.06);
            }

            .mobile-company-card .actions {
                display: flex;
                gap: 0.5rem;
                flex-wrap: wrap;
            }

            .mobile-company-card .actions .btn,
            .mobile-company-card .actions form,
            .mobile-company-card .actions form button {
                flex: 1 1 100%;
                width: 100%;
            }
        }
    </style>

</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4 header-mobile">
        <h1>Admin - Companies</h1>

        <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
            Add company
        </a>
    </div>

    <div class="mb-3 top-actions">

        <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary">
            Back to public site
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
                Logout
            </button>
        </form>

    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.companies.index') }}">
                <div class="row g-2 search-row-mobile">
                    <div class="col-md-10">
                        <input
                            type="text"
                            name="q"
                            class="form-control"
                            placeholder="Search by company name or VAT number"
                            value="{{ $query ?? '' }}"
                        >
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-dark w-100">
                            Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(!empty($query))
        <div class="mb-3">
            <p class="text-muted mb-0">
                Results for "<strong>{{ $query }}</strong>" — {{ $companies->total() }} result(s)
            </p>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card desktop-table">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead>
                        <tr>
                            <th>VAT Number</th>
                            <th>Name</th>
                            <th>City</th>
                            <th>Status</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($companies as $company)
                            @php
                                $rawVat = strtoupper($company->vat_number ?? '');
                                $cleanVat = preg_replace('/\D/', '', str_replace('BE', '', $rawVat));

                                $formattedVat = strlen($cleanVat) === 10
                                    ? 'BE ' . substr($cleanVat,0,4) . '.' . substr($cleanVat,4,3) . '.' . substr($cleanVat,7,3)
                                    : ($company->vat_number ?: '—');
                            @endphp

                            <tr>
                                <td>{{ $formattedVat }}</td>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->city }}</td>
                                <td>{{ $company->status }}</td>
                                <td>
                                    <a href="{{ route('admin.companies.edit', $company) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.companies.destroy', $company) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this company?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5">
                                    No companies found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

            <div class="mt-3">
                {{ $companies->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>

    <div class="mobile-company-card">
        @forelse($companies as $company)
            @php
                $rawVat = strtoupper($company->vat_number ?? '');
                $cleanVat = preg_replace('/\D/', '', str_replace('BE', '', $rawVat));

                $formattedVat = strlen($cleanVat) === 10
                    ? 'BE ' . substr($cleanVat,0,4) . '.' . substr($cleanVat,4,3) . '.' . substr($cleanVat,7,3)
                    : ($company->vat_number ?: '—');
            @endphp

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-2">{{ $company->name }}</h5>

                    <p class="mb-1">
                        <strong>VAT:</strong> {{ $formattedVat }}
                    </p>

                    <p class="mb-1">
                        <strong>City:</strong> {{ $company->city ?: '—' }}
                    </p>

                    <p class="mb-3">
                        <strong>Status:</strong> {{ $company->status ?: '—' }}
                    </p>

                    <div class="actions">
                        <a href="{{ route('admin.companies.edit', $company) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('admin.companies.destroy', $company) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this company?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        @empty
            <div class="card">
                <div class="card-body">
                    No companies found.
                </div>
            </div>
        @endforelse

        <div class="mt-3">
            {{ $companies->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>

</body>
</html>