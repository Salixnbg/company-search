<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;
use Exception;

class VatController extends Controller
{
    public function index()
    {
        return view('vat.index');
    }

    public function check(Request $request)
    {
        $request->validate([
            'vat_number' => ['required', 'string'],
        ]);

        $input = strtoupper(trim($request->vat_number));
        $input = str_replace([' ', '.', '-', '/'], '', $input);

        if (str_starts_with($input, 'BE')) {
            $countryCode = 'BE';
            $vatNumber = substr($input, 2);
        } else {
            $countryCode = 'BE';
            $vatNumber = $input;
        }

        $result = null;
        $error = null;

        try {
            $client = new SoapClient('https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl', [
                'exceptions' => true,
                'trace' => false,
                'cache_wsdl' => WSDL_CACHE_NONE,
            ]);

            $response = $client->checkVat([
                'countryCode' => $countryCode,
                'vatNumber' => $vatNumber,
            ]);

            $result = [
                'countryCode' => $response->countryCode ?? $countryCode,
                'vatNumber'   => $response->vatNumber ?? $vatNumber,
                'valid'       => $response->valid ?? false,
                'name'        => $response->name ?? '',
                'address'     => $response->address ?? '',
            ];
        } catch (Exception $e) {
            $error = 'Unable to verify the VAT number at the moment.';
        }

        return view('vat.index', [
            'result' => $result,
            'error' => $error,
            'inputVat' => $request->vat_number,
        ]);
    }
}