<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->input('q', ''));

        $companies = Company::query()
            ->when($query !== '', function ($builder) use ($query) {
                $this->applySearch($builder, $query);
            })
            ->orderByRaw('LOWER(name) ASC')
            ->paginate(50)
            ->appends(['q' => $query]);

        return view('admin.companies.index', [
            'companies' => $companies,
            'query' => $query,
        ]);
    }

    public function create()
    {
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateCompany($request);

        Company::create($validated);

        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Company created successfully.');
    }

    public function edit(Company $company)
    {
        return view('admin.companies.edit', [
            'company' => $company,
        ]);
    }

    public function update(Request $request, Company $company)
    {
        $validated = $this->validateCompany($request);

        $company->update($validated);

        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Company deleted successfully.');
    }

    private function validateCompany(Request $request): array
    {
        return $request->validate([
            'enterprise_number' => ['required', 'string', 'max:50'],
            'vat_number' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:100'],
            'legal_form' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
        ]);
    }

    private function applySearch($builder, string $query): void
    {
        $driver = DB::connection()->getDriverName();
        $normalizedQuery = $this->normalizeNumber($query);

        $enterpriseExpr = $this->castAsString('enterprise_number', $driver);
        $vatExpr = $this->castAsString('vat_number', $driver);

        $normalizedEnterpriseExpr = $this->normalizedSqlExpression($enterpriseExpr);
        $normalizedVatExpr = $this->normalizedSqlExpression($vatExpr);

        $builder->where(function ($q) use (
            $query,
            $normalizedQuery,
            $enterpriseExpr,
            $vatExpr,
            $normalizedEnterpriseExpr,
            $normalizedVatExpr
        ) {
            $q->whereRaw('LOWER(name) LIKE ?', ['%' . mb_strtolower($query) . '%'])
                ->orWhereRaw("{$enterpriseExpr} LIKE ?", ['%' . $query . '%'])
                ->orWhereRaw("{$normalizedEnterpriseExpr} LIKE ?", ['%' . $normalizedQuery . '%'])
                ->orWhereRaw("{$vatExpr} LIKE ?", ['%' . $query . '%'])
                ->orWhereRaw("{$normalizedVatExpr} LIKE ?", ['%' . $normalizedQuery . '%']);
        });
    }

    private function normalizeNumber(string $value): string
    {
        $normalized = strtoupper($value);
        $normalized = str_replace([' ', '.', '-', '/', '\\'], '', $normalized);

        if (str_starts_with($normalized, 'BE')) {
            $normalized = substr($normalized, 2);
        }

        return $normalized;
    }

    private function castAsString(string $column, string $driver): string
    {
        return $driver === 'pgsql'
            ? "CAST({$column} AS TEXT)"
            : "CAST({$column} AS CHAR)";
    }

    private function normalizedSqlExpression(string $expression): string
    {
        return "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(UPPER({$expression}), ' ', ''), '.', ''), '-', ''), '/', ''), '\\\\', '')";
    }
}