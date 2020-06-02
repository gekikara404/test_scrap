<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Scrape;
use App\Products;
use Illuminate\Support\Facades\Http;
use Response;
use Storage;
use Orchestra\Parser\Xml\Facade as XmlParser; 
use Browser\Casper;


set_time_limit(1000000000000);

class ScrapeJsonController extends Controller
{
    public function soalsatu()
    {
       // kalau langsung url lewat api tapi lebih enak localan karena insert DB nya lebih cepet
        // $res = Http::get('https://www.t-mobile.com/srvspub/tradeInData.json');
        // $json = $res->getBody()->getContents();

        $json = Storage::disk('local')->get('trade.json');
        $area = json_decode($json,true);
        foreach ($area['productOfferings'] as $key => $obj) {
            Scrape::create([
                'manufacturer' => $obj['products'][0]['manufacturer'],
                'model' => $obj['products'][0]['serviceProvider'],
                'carrier' => $obj['products'][0]['model'],
                'price' => $obj['prices'][0]['oneTimeCharges'][0]['amount']
            ]);
            
        }

        return response()->json([
            'state' => true,
            'message' => 'data sukses ter import'
        ], 200);
    }

    public function soaldua()
    {
        // fungsi xml to json

        // $xml_string = Storage::disk('local')->get('cayote.xml');
        // $xml_string = str_replace(array("\n", "\r", "\t"), '', $xml_string);
        // $xml_string = trim(str_replace('"', "'", $xml_string));
        // $xml = simplexml_load_string($xml_string);
        // $json = json_encode($xml);
        // $array = json_decode($json,TRUE);
        
        $json = Storage::disk('local')->get('xmlcon.json');
        $area = json_decode($json,true);

        foreach ($area['product_specs'] as $key => $value) {
            $model = $value['classification']['pn'];
            $brand_name = $value['classification']['brand_name'];
            if(!empty($value['managed_data']['column_data']['map'])){
                $price = $value['managed_data']['column_data']['map'];
            }else{
                $price = 0;
            }
                Products::create([
                    'model' => $model,
                    'brand_name' => $brand_name,
                    'price' => $price
                ]);
        }

        return response()->json([
            'state' => true,
            'message' => 'data sukses ter import'
        ], 200);
 
    }

    public function soaltiga()
    {
        // $casper = new Casper();

        // $casper->start('http://www.google.com');

        // // click the first result
        // $casper->getCurrentPageContent();
        // var_dump($casper->getOutput());

        // var_dump($casper->getRequestedUrls());

        


        // $json = Storage::disk('local')->get('xmlcon.json');
        // $area = json_decode($json,true);

        // foreach ($area['product_specs'] as $key => $value) {
        //     $model = $value['classification']['pn'];
        //     $brand_name = $value['classification']['brand_name'];
        //     if(!empty($value['managed_data']['column_data']['map'])){
        //         $price = $value['managed_data']['column_data']['map'];
        //     }else{
        //         $price = 0;
        //     }
        //         Products::create([
        //             'model' => $model,
        //             'brand_name' => $brand_name,
        //             'price' => $price
        //         ]);
        // }
    }

}