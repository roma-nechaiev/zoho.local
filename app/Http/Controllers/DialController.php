<?php

namespace App\Http\Controllers;

use App\Services\Zoho\ZohoClient;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\record\GetRecordsParam;
use com\zoho\crm\api\record\Record;
use com\zoho\crm\api\record\Deals;
use com\zoho\crm\api\util\Choice;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DialController extends Controller
{
    public $zoho_client;
    public function __construct()
    {
        $this->zoho_client = ZohoClient::init();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $paramInstance = new ParameterMap();

        $fieldNames = ['id', 'Deal_Name', "Stage"];
        foreach ($fieldNames as $fieldName) {
            $paramInstance->add(GetRecordsParam::fields(), $fieldName);
        }
        $paramInstance->add(GetRecordsParam::page(), 1);
        $paramInstance->add(GetRecordsParam::perPage(), 20);

        $dials = $this->zoho_client->getRecords('Deals', $paramInstance);

        return Inertia::render('Dial/Index', [
            'dials' => $dials,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'Deal_Name' => ['required', 'string', 'max:255'],
            'Stage' => ['required', 'string',],
            'id' => ['required'],
        ]);

        $record = new Record();
        $record->addFieldValue(Deals::DealName(), $request->input('Deal_Name'));
        $record->addFieldValue(Deals::Stage(), new Choice($request->input('Stage')));

        $account_name = new Record();
        $account_name->setId($request->input('id'));
        $record->addFieldValue(Deals::AccountName(), $account_name);

        $response = $this->zoho_client->createRecords('Deals', $record);

        $message = $response['status'] == "success" ? "Dial Created Successfully" : $response['message'];

        return to_route('home')->with('message', $message);
    }
}
