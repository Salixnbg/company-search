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

        $companies = Company::query();

        if ($query !== '') {

            $normalized = strtoupper($query);
            $normalized = str_replace([' ', '.', '-', '/', '\\'], '', $normalized);

            if (str_starts_with($normalized, 'BE')) {
                $normalized = substr($normalized, 2);
            }

            $companies->where(function ($q) use ($query, $normalized) {

                $q->whereRaw("LOWER(name) LIKE ?", ['%' . strtolower($query) . '%'])

                  ->orWhereRaw("CAST(enterprise_number AS CHAR) LIKE ?", ['%' . $query . '%'])

                  ->orWhereRaw("
                    REPLACE(
                        REPLACE(
                            REPLACE(
                                REPLACE(
                                    REPLACE(UPPER(CAST(enterprise_number AS CHAR)),' ',''),'.',''
                                ),'-',''
                            ),'/',''
                        ),'\\\\',''
                    ) LIKE ?
                  ", ['%' . $normalized . '%']);
            });
        }

        $companies = $companies
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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'enterprise_number' => ['required', 'string', 'max:50'],
        ]);

        $company = new Company();
        $company->name = trim($validated['name']);
        $company->enterprise_number = trim($validated['enterprise_number']);
        $company->save();

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

        $company->name = trim($validated['name']);
        $company->enterprise_number = trim($validated['enterprise_number']);
        $company->save();

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