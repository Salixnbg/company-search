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

    </style>

</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h1>Admin - Companies</h1>

        <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
            Add company
        </a>

    </div>

    <div class="mb-3 d-flex gap-2">

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

                <div class="row g-2">

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

    <div class="card">

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

                            <tr>

                                <td>

                                    @if($company->vat_number)
                                        BE {{ substr($company->vat_number,0,4) }}.{{ substr($company->vat_number,4,3) }}.{{ substr($company->vat_number,7,3) }}
                                    @else
                                        —
                                    @endif

                                </td>

                                <td>
                                    {{ $company->name }}
                                </td>

                                <td>
                                    {{ $company->city }}
                                </td>

                                <td>
                                    {{ $company->status }}
                                </td>

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

</div>

</body>
</html>