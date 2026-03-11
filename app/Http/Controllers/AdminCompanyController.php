<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('q', ''));
        $driver = DB::connection()->getDriverName();

        $companies = Company::query()
            ->when($query !== '', function ($q) use ($query, $driver) {
                $normalized = strtoupper($query);
                $normalized = str_replace([' ', '.', '-', '/', '\\'], '', $normalized);

                if (str_starts_with($normalized, 'BE')) {
                    $normalized = substr($normalized, 2);
                }

                $nameOperator = $driver === 'pgsql' ? 'ILIKE' : 'LIKE';

                $enterpriseExpr = $driver === 'pgsql'
                    ? "CAST(enterprise_number AS TEXT)"
                    : "CAST(enterprise_number AS CHAR)";

                $normalizedEnterpriseExpr = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(UPPER({$enterpriseExpr}), ' ', ''), '.', ''), '-', ''), '/', ''), '\\\\', '')";

                $q->where(function ($subQuery) use ($query, $normalized, $nameOperator, $enterpriseExpr, $normalizedEnterpriseExpr) {
                    $subQuery->whereRaw("name {$nameOperator} ?", ['%' . $query . '%'])
                        ->orWhereRaw("{$enterpriseExpr} LIKE ?", ['%' . $query . '%'])
                        ->orWhereRaw("{$normalizedEnterpriseExpr} LIKE ?", ['%' . $normalized . '%']);
                });
            })
            ->orderByRaw('LOWER(name) asc')
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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'enterprise_number' => ['required', 'string', 'max:50'],
        ]);

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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'enterprise_number' => ['required', 'string', 'max:50'],
        ]);

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
}