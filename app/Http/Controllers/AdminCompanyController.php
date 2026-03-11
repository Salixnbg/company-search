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

        if ($driver === 'pgsql') {
            $enterpriseNumberAsText = 'CAST(enterprise_number AS TEXT)';
        } else {
            $enterpriseNumberAsText = 'CAST(enterprise_number AS CHAR)';
        }

        $companies = Company::query()
            ->when($query !== '', function ($q) use ($query, $enterpriseNumberAsText) {
                $normalized = strtoupper($query);
                $normalized = str_replace([' ', '.', '-', '/'], '', $normalized);

                if (str_starts_with($normalized, 'BE')) {
                    $normalized = substr($normalized, 2);
                }

                $q->where(function ($subQuery) use ($query, $normalized, $enterpriseNumberAsText) {
                    $subQuery->where('name', 'like', '%' . $query . '%')
                        ->orWhereRaw("$enterpriseNumberAsText LIKE ?", ['%' . $query . '%'])
                        ->orWhereRaw("
                            REPLACE(
                                REPLACE(
                                    REPLACE(
                                        REPLACE(UPPER($enterpriseNumberAsText), ' ', ''),
                                    '.', ''),
                                '-', ''),
                            '/', '') LIKE ?
                        ", ['%' . $normalized . '%']);
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
}