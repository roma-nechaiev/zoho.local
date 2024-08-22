<?php

namespace App\Http\Controllers;

use App\Services\Zoho\ZohoClient;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\record\GetRecordsParam;
use com\zoho\crm\api\record\Record;
use com\zoho\crm\api\record\Accounts;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AccountController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(ZohoClient $zoho_client): Response
    {
        $paramInstance = new ParameterMap();

        $fieldNames = ['id', 'Account_Name', "Website", "Phone"];
        foreach ($fieldNames as $fieldName) {
            $paramInstance->add(GetRecordsParam::fields(), $fieldName);
        }
        $paramInstance->add(GetRecordsParam::page(), 1);
        $paramInstance->add(GetRecordsParam::perPage(), 20);

        $accounts = $zoho_client->getRecords('Accounts', $paramInstance);


        return Inertia::render('Account/Index', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, ZohoClient $zoho_client): RedirectResponse
    {;

        $request->validate([
            'Account_Name' => ['required', 'string', 'max:255'],
            'Phone' => ['required', 'numeric', 'digits:10'],
            'Website' => ['required', 'string', 'url'],
        ]);

        $record = new Record();
        $record->addFieldValue(Accounts::AccountName(), $request->input('Account_Name'));
        $record->addFieldValue(Accounts::Phone(), $request->input('Phone'));
        $record->addFieldValue(Accounts::Website(), $request->input('Website'));

        $response = $zoho_client->createRecords('Accounts', $record);
        $message = $response['status'] == "success" ? "Account Created Successfully" : $response['message'];

        return to_route('home')->with('message', $message);
    }
}
