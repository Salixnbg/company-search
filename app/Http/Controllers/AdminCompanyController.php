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

        $companies = Company::query()
            ->when($query !== '', function ($q) use ($query) {

                $normalized = strtoupper($query);
                $normalized = str_replace([' ', '.', '-', '/'], '', $normalized);

                if (str_starts_with($normalized, 'BE')) {
                    $normalized = substr($normalized, 2);
                }

                $q->where(function ($subQuery) use ($query, $normalized) {

                    $subQuery->where('name', 'like', '%' . $query . '%')
                             ->orWhere('enterprise_number', 'like', '%' . $query . '%')
                             ->orWhereRaw("
                                REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(UPPER(enterprise_number),' ',''),'.',''
                                        ),'-',''
                                    ),'/',''
                                ) LIKE ?
                             ", ['%' . $normalized . '%']);
                });
            })

            // TRI ALPHABÉTIQUE AUTOMATIQUE
            ->orderBy('name', 'asc')

            // PAGINATION
            ->paginate(50)
            ->appends(['q' => $query]);

        return view('admin.companies.index', [
            'companies' => $companies,
            'query' => $query
        ]);
    }
}