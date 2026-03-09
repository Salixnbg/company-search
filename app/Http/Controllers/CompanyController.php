<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('q', ''));

        $companies = collect();

        if ($query !== '') {

            $normalized = strtoupper(str_replace([' ', '.', '-', '/'], '', $query));
            $normalized = str_replace('BE', '', $normalized);

            $companies = Company::query()
                ->when(is_numeric($normalized), function ($q) use ($normalized) {

                    $q->where('enterprise_number', 'like', '%' . $normalized . '%')
                      ->orWhere('vat_number', 'like', '%' . $normalized . '%');

                }, function ($q) use ($query) {

                    $q->where('name', 'like', '%' . $query . '%');

                })
                ->orderBy('name')
                ->paginate(10);
        }

        return view('companies.index', [
            'companies' => $companies,
            'query' => $query,
        ]);
    }

    public function show(Company $company)
    {
        return view('companies.show', compact('company'));
    }
}