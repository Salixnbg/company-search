<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class AdminCompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('q', ''));

        $companies = Company::query()
            ->when($query !== '', function ($q) use ($query) {
                $normalized = strtoupper(str_replace([' ', '.', '-', '/'], '', $query));
                $normalized = str_replace('BE', '', $normalized);

                $q->where(function ($subQuery) use ($query, $normalized) {
                    $subQuery->where('name', 'like', '%' . $query . '%')
                             ->orWhere('enterprise_number', 'like', '%' . $normalized . '%');
                });
            })
            ->orderBy('name', 'asc')
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
        $data = $request->validate([
            'enterprise_number' => 'required|string|max:255|unique:companies,enterprise_number',
            'vat_number' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'status' => 'nullable|string|max:255',
            'legal_form' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
        ]);

        Company::create($data);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company created successfully.');
    }

    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'enterprise_number' => 'required|string|max:255|unique:companies,enterprise_number,' . $company->id,
            'vat_number' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'status' => 'nullable|string|max:255',
            'legal_form' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
        ]);

        $company->update($data);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company deleted successfully.');
    }
}