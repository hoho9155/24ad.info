<?php

namespace Hoho9155\PostalCodes\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Category;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class CategoryTranslateController extends BaseController
{
    
    public function index()
    {
        return view('postal-codes::category-name-translate.index');
    }
    
    public function translate() 
    {
        $category_id = $_GET['id'];
        $category_name = $_GET['name'];

        $langs = [ 'ar', 'bg', 'bn', 'cs', 'da', 'de', 'el', 'en', 'es', 'et', 'fr', 'hi', 'hr', 'hu', 'it', 'ja', 'ka', 'lt', 'lv', 'pl', 'pt', 'ro', 'ru', 'sk', 'sl', 'sv', 'th', 'tr', 'uk', 'zh'];
        $langs_str = [];
        foreach ($langs as $lang) {
            $langs_str[] = '"' . $lang . '": "Category Name"';
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
                    "content" => 'Please provide the translation of the category of "' . $category_name . '" by only json type below without any description: {' . implode(",", $langs_str) . '}'
                ]]
            ]
        ]);
        $body = $response->getBody();
        $bodyJS = json_decode($body);
        
        $answer = $bodyJS->choices[0]->message->content;
        try {
            $data = json_decode($answer);
            if ($data->en) {
                DB::update('UPDATE categories SET name = ? WHERE id = ?', [$answer, $category_id]);
                
                return response()->json($answer);
            }
        } catch (Exception $e) {

        }

        return response()->json('Error');
    }
    
    public function categories()
    {
        return response()->json(Category::all());
    }
}
