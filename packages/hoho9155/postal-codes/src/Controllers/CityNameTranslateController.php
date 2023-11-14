<?php

namespace Hoho9155\PostalCodes\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Country;
use App\Models\City;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class CityNameTranslateController extends BaseController
{
    
    public function index()
    {
        return view('postal-codes::city-name-translate.index');
    }
    
    public function translate() 
    {
        $city_id = $_GET['id'];
        $city_name = $_GET['name'];
        $country_code = $_GET['country_code'];
        $lang_code = $_GET['lang_code'];
        
        $langs = explode(",", $lang_code);
        $langs_str = ['"en":"Name"'];
        foreach ($langs as $lang) {
            $langs_str[] = '"' . $lang . '":"Name"';
        }
        
        $client = new Client([
            'base_uri' => 'https://api.openai.com'
        ]);
        $url = '/v1/chat/completions';
        
        $apiKey = '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';
        $response = $client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [[
                    "role" => "user",
                    "content" => 'Please provide the translation of the city "' . $city_name . '" in the country(country code: ' . $country_code . ') by only json type below without any description: {' . implode(",", $langs_str) . '}'
                ]]
            ]
        ]);
        $body = $response->getBody();
        $bodyJS = json_decode($body);
        
        $answer = $bodyJS->choices[0]->message->content;
        try {
            $data = json_decode($answer);
            if ($data->en) {
                DB::update('UPDATE cities SET name = ? WHERE id = ?', [$answer, $city_id]);
                
                return response()->json($answer);
            }
        } catch (Exception $e) {

        }

        return response()->json('Error');
    }
    
    public function countries()
    {
        return response()->json(Country::where('active', 1)->get());
    }
    
    public function cities()
    {
        $country_code = $_GET['country_code'];
        if (empty($country_code))
            return response()->json([]);
        return response()->json(City::where('country_code', $country_code)->get());
    }
}
