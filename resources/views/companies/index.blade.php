<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Search</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <h1 class="mb-4">Belgian Company Search</h1>

    <form method="GET" action="{{ route('companies.index') }}">

        <div class="input-group mb-4">

            <input 
                type="text"
                name="q"
                class="form-control"
                placeholder="VAT number or company name"
                value="{{ $query }}"
            >

            <button class="btn btn-primary">
                Search
            </button>

        </div>

    </form>

@if(!empty($query))

    <h4 class="mb-3">Results</h4>

    <p class="text-muted mb-3">
        {{ $companies->total() }} result(s) found
    </p>

    @if($companies->count())

        <ul class="list-group">

            @foreach($companies as $company)

                <li class="list-group-item">

                    <a href="{{ route('companies.show', $company) }}">

                        <strong>{{ $company->name }}</strong>

                    </a>

                    <br>

                    Enterprise number : {{ $company->enterprise_number }}

                    @if($company->city)
                        <br>
                        {{ $company->city }}
                    @endif

                </li>

            @endforeach

        </ul>

        <div class="mt-4">
            {{ $companies->links() }}
        </div>

    @else

        <div class="alert alert-warning">
            No company found
        </div>

    @endif

@endif

</div>

</body>
</html>