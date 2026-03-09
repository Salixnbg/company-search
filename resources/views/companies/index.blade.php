<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Search</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f7fb;
        }

        .search-wrapper {
            max-width: 900px;
            margin: 0 auto;
        }

        .search-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .hero-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f3c88;
        }

        .hero-text {
            color: #6c757d;
        }

        .result-link {
            text-decoration: none;
            color: inherit;
        }

        .result-link:hover {
            text-decoration: none;
            color: inherit;
        }

        .result-item {
            border-radius: 10px;
            transition: 0.2s ease;
        }

        .result-item:hover {
            background-color: #f8fbff;
        }

        #suggestions {
            border-radius: 10px;
            overflow: hidden;
        }

        .pagination {
            margin-bottom: 0;
        }

        .pagination-wrapper nav {
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>

<div class="container py-4">
    <div class="search-wrapper">

        <div class="d-flex justify-content-end gap-2 mb-4">

            <a href="{{ route('vat.index') }}" class="btn btn-outline-primary btn-sm">
                VAT Check
            </a>

            @auth
                <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-dark btn-sm">
                    Admin
                </a>

                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                    Login
                </a>
            @endauth

        </div>

        <div class="text-center mb-4">
            <h1 class="hero-title">
                Belgian Company Search
            </h1>

            <p class="hero-text">
                Search for a Belgian company by VAT number or company name
            </p>
        </div>

        <div class="card search-card mb-4">
            <div class="card-body p-4">

                <form method="GET" action="{{ route('companies.index') }}" autocomplete="off">

                    <div class="position-relative">

                        <div class="input-group">

                            <input
                                type="text"
                                id="searchInput"
                                name="q"
                                class="form-control form-control-lg"
                                placeholder="Enter VAT number or company name"
                                value="{{ $query }}"
                            >

                            <button class="btn btn-primary btn-lg">
                                Search
                            </button>

                        </div>

                        <div
                            id="suggestions"
                            class="list-group position-absolute w-100 mt-1 shadow-sm"
                            style="z-index:1000;">
                        </div>

                    </div>

                </form>

            </div>
        </div>

        @if(!empty($query))

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h4 class="mb-0">
                    Results
                </h4>

                <p class="text-muted mb-0">
                    {{ $companies->total() }} result(s) found
                </p>

            </div>

            @if($companies->count())

                <div class="list-group">

                    @foreach($companies as $company)

                        @php
                            $formattedEnterpriseNumber = $company->enterprise_number
                                ? substr($company->enterprise_number, 0, 4) . '.' .
                                  substr($company->enterprise_number, 4, 3) . '.' .
                                  substr($company->enterprise_number, 7, 3)
                                : 'N/A';
                        @endphp

                        <a
                            href="{{ route('companies.show', $company) }}"
                            class="result-link">

                            <div class="list-group-item result-item mb-2 border">

                                <div class="d-flex justify-content-between align-items-start">

                                    <div>

                                        <h5 class="mb-1">
                                            {{ $company->name }}
                                        </h5>

                                        <p class="mb-1 text-muted">
                                            Enterprise number: {{ $formattedEnterpriseNumber }}
                                        </p>

                                        @if($company->city || $company->postal_code)

                                            <small class="text-secondary">
                                                {{ $company->postal_code }} {{ $company->city }}
                                            </small>

                                        @endif

                                    </div>

                                    @if($company->status)

                                        <span class="badge bg-secondary">
                                            {{ $company->status }}
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>

                <div class="mt-4 pagination-wrapper">
                    {{ $companies->appends(['q' => $query])->links('pagination::bootstrap-5') }}
                </div>

            @else

                <div class="alert alert-warning">
                    No company found.
                </div>

            @endif

        @endif

    </div>
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
            const formattedEnterpriseNumber = company.enterprise_number
                ? `${company.enterprise_number.substring(0,4)}.${company.enterprise_number.substring(4,7)}.${company.enterprise_number.substring(7,10)}`
                : 'N/A';

            const item = document.createElement('a');
            item.href = `/companies/${company.id}`;
            item.className = 'list-group-item list-group-item-action';

            item.innerHTML = `
                <strong>${company.name}</strong><br>
                <small>Enterprise number: ${formattedEnterpriseNumber}${company.city ? ' - ' + company.city : ''}</small>
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