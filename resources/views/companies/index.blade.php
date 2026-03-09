<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Search</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <h1 class="mb-4">Belgian Company Search</h1>

    <form method="GET" action="{{ route('companies.index') }}" autocomplete="off">
        <div class="position-relative">
            <div class="input-group mb-1">
                <input
                    type="text"
                    id="searchInput"
                    name="q"
                    class="form-control"
                    placeholder="VAT number or company name"
                    value="{{ $query }}"
                >

                <button class="btn btn-primary">
                    Search
                </button>
            </div>

            <div id="suggestions" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1000;"></div>
        </div>
    </form>

    @if(!empty($query))

        <h4 class="mb-3 mt-4">Results</h4>

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

                        Enterprise number: {{ $company->enterprise_number }}

                        @if($company->city)
                            <br>
                            {{ $company->city }}
                        @endif

                    </li>

                @endforeach

            </ul>

            <div class="mt-4">
                {{ $companies->appends(['q' => $query])->links() }}
            </div>

        @else

            <div class="alert alert-warning">
                No company found
            </div>

        @endif

    @endif

</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const suggestionsBox = document.getElementById('suggestions');

    searchInput.addEventListener('input', async function () {
        const query = this.value.trim();

        if (query.length < 2) {
            suggestionsBox.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`/api/companies/search?q=${encodeURIComponent(query)}`);
            const companies = await response.json();

            suggestionsBox.innerHTML = '';

            if (companies.length === 0) {
                return;
            }

            companies.forEach(company => {
                const item = document.createElement('a');
                item.href = `/companies/${company.id}`;
                item.className = 'list-group-item list-group-item-action';

                item.innerHTML = `
                    <strong>${company.name}</strong><br>
                    <small>Enterprise number: ${company.enterprise_number}${company.city ? ' - ' + company.city : ''}</small>
                `;

                suggestionsBox.appendChild(item);
            });
        } catch (error) {
            suggestionsBox.innerHTML = '';
        }
    });

    document.addEventListener('click', function (event) {
        if (!suggestionsBox.contains(event.target) && event.target !== searchInput) {
            suggestionsBox.innerHTML = '';
        }
    });
</script>

</body>
</html>