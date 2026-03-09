<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Companies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Admin - Companies</h1>

        <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
            Add company
        </a>
    </div>

    <div class="mb-3">
        <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary">
            Back to public site
        </a>
    </div>

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
                            <th>Enterprise Number</th>
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
                                    {{ $company->enterprise_number }}
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
                {{ $companies->links() }}
            </div>

        </div>
    </div>

</div>

</body>
</html>