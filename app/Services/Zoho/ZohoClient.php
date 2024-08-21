<?php

namespace App\Services\Zoho;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\api\authenticator\store\DBBuilder;
use com\zoho\api\authenticator\store\FileStore;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\api\logger\LogBuilder;
use com\zoho\api\logger\Levels;
use com\zoho\crm\api\dc\EUDataCenter;
use com\zoho\crm\api\SDKConfigBuilder;

use com\zoho\crm\api\HeaderMap;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\record\ResponseWrapper;
use com\zoho\crm\api\record\ActionWrapper;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\BodyWrapper;
use com\zoho\crm\api\record\Record;
use com\zoho\crm\api\record\RecordOperations;
use com\zoho\crm\api\record\SuccessResponse;
use com\zoho\crm\api\util\APIResponse;
use com\zoho\crm\api\util\Choice;



class ZohoClient
{
    public static function init()
    {
        $zohoConfig = config('services.zoho');

        $logger = (new LogBuilder())
            ->level(Levels::INFO)
            ->filePath(storage_path('logs/zoho.log'))
            ->build();

        if ($zohoConfig['store'] === 'database') {
            $dbConfig = config('database.connections.' . config('database.default'));
            $tokenStore = (new DBBuilder())
                ->host($dbConfig['host'])
                ->databaseName($dbConfig['database'])
                ->userName($dbConfig['username'])
                ->portNumber($dbConfig['port'])
                ->tableName('oauth_tokens')
                ->password($dbConfig['password'])
                ->build();
        } else {
            $tokenStore = new FileStore(storage_path('sdk_tokens.txt'));
        }

        $environment = EUDataCenter::PRODUCTION();

        $token = (new OAuthBuilder())
            ->clientId($zohoConfig['client_id'])
            ->clientSecret($zohoConfig['client_secret'])
            ->grantToken($zohoConfig['grant_token'])
            ->build();

        $sdkConfig = (new SDKConfigBuilder())
            ->autoRefreshFields(false)
            ->pickListValidation(false)
            ->sslVerification(false)
            ->connectionTimeout(2)
            ->timeout(2)
            ->build();

        (new InitializeBuilder())
            ->environment($environment)
            ->token($token)
            ->store($tokenStore)
            ->SDKConfig($sdkConfig)
            ->resourcePath(storage_path())
            ->logger($logger)
            ->initialize();

        return new ZohoClient();
    }

    public function createRecords(string $moduleAPIName, Record $records)
    {
        $recordOperations = new RecordOperations($moduleAPIName);
        $bodyWrapper = new BodyWrapper();
        $headerInstance = new HeaderMap();
        $bodyWrapper->setData([$records]);
        $response = $recordOperations->createRecords($bodyWrapper, $headerInstance);

        return  $this->handleResponse($response);
    }

    public function getRecords(string $moduleAPIName, ParameterMap $params)
    {
        $recordOperations = new RecordOperations($moduleAPIName);
        $headerInstance = new HeaderMap();

        $response = $recordOperations->getRecords($params, $headerInstance);

        return $this->handleResponse($response);
    }

    private function handleResponse(APIResponse $apiResponse)
    {
        if ($apiResponse->isExpected()) {
            $responseData = $apiResponse->getObject();

            if ($responseData instanceof ResponseWrapper) {
                return $this->processResponseData($responseData);
            } elseif ($responseData instanceof ActionWrapper) {
                return $this->processActionData($responseData);
            } elseif ($responseData instanceof APIException) {
                return $this->getActionResponse($responseData);
            }
        }

        return $apiResponse;
    }

    private function processResponseData(ResponseWrapper $responseWrapper)
    {
        $records = collect($responseWrapper->getData())
            ->map(function ($record) {
                $processedRecord = $record->getKeyValues();
                foreach ($processedRecord as $key => $value) {
                    if ($value instanceof Choice) {
                        $processedRecord[$key] = $value->getValue();
                    }
                }

                return $processedRecord;
            });

        return $records;
    }

    private function processActionData(ActionWrapper $actionWrapper)
    {
        foreach ($actionWrapper->getData() as $actionResponse) {
            if ($actionResponse instanceof SuccessResponse || $actionResponse instanceof APIException) {
                return $this->getActionResponse($actionResponse);
            }
        }
    }

    private function getActionResponse(SuccessResponse|APIException $response): array
    {
        $messageValue = $response->getMessage() instanceof Choice
            ? $response->getMessage()->getValue()
            : $response->getMessage();

        return [
            'status' => $response->getStatus()->getValue(),
            'code' => $response->getCode()->getValue(),
            'details' => $response->getDetails(),
            'message' => $messageValue,
        ];
    }
}
