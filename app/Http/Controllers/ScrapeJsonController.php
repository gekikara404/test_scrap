<?php

namespace App\Http\Controllers;
// saya buat bersih clean code

use Illuminate\Http\Request;
use App\Scrape;
use App\Products;
use App\Iphone_prod;
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

        // ada error dikit pas import makanya pake set_time_limit untuk bypass engga mati saat import json ke db sekarang udha berjalan normal

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

        // yang ini kita dapetin data xml dari doc pdf lalu download kita convert ke json abis udah di convert kita panggil melalui storage
        
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
        // rencana soal 3 kalau pake php curl site lain google dll masih bisa , cuman kalau site yang pak pebri kasih agak challanging di captcha nya aja sih 
        // harus enable javascript sama cookies , untuk cookies udah di handle di curl tapi javascript kayanya engga bisa harus buat microservice kayanya.


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

    public function soalempat()
    {
        $mod = array('16gb','32gb','64gb','128gb','256gb','512gb');
        $network = array('att', 'factory-unlocked', 'other' , 'sprint','t-mobile','verizon','unlocked(AT&T)','unlocked(Sprint)','unlocked(Verizon)','unlocked(T-Mobile)','unlocked(Other)');
        $model = array('iphone-se-2020','iphone-iphone-11','iphone-iphone-11-pro','iphone-iphone-11-pro-max','iphone-iphone-xs','iphone-iphone-xs-max','iphone-iphone-xr','iphone-iphone-x','iphone-iphone-8','iphone-iphone-8-plus','7','7-plus','se','6s','6s-plus','6','6-plus','5S','5C','5','4s','4');        
        $condition = array('used','damaged','new');
        $condition_last = array('broken','damaged','cracked-glass','fair','normal','new');
 

       foreach($condition as $val1){

                foreach($model as $val2){

                    foreach($network as $val3){

                        foreach($mod as $val4){

                            foreach($condition_last as $val5){

                                $url = 'https://sellshark.com/sell/iphone/'.$val1.'/'.$val2.'/'.$val3.'/'.  $val4 . '/'. $val5;
                                
                                
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
                                curl_setopt($ch, CURLOPT_HEADER, 0);
                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_PROXY, '');
                            
                                $data = curl_exec($ch);
                                curl_close($ch);


                            
                                $dom = new \DOMDocument();;
                                @$dom->loadHTML($data);
                            
                                $xpath = new \DOMXPath($dom);
                            
                                $datas = $xpath->query('//*[@id="sell-breadcrumbs-nav"]/div/ul/li[1]/div/a');
                                $datas1 = $xpath->query('//*[@id="sell-breadcrumbs-nav"]/div/ul/li[2]/div/a');
                                $datas2 = $xpath->query('//*[@id="sell-breadcrumbs-nav"]/div/ul/li[3]/div/a');
                                $datas4 = $xpath->query('//*[@id="sell-breadcrumbs-nav"]/div/ul/li[5]/div/a');
                                $datas5 = $xpath->query('//*[@id="mydevice"]/div/div/div[2]/div/div/div[1]/span');

                                foreach( $datas as $node )
                                {
                                    $a = $node->nodeValue;
                                }
                                foreach( $datas1 as $node )
                                {
                                    $b = $node->nodeValue;
                                }
                                foreach( $datas2 as $node )
                                {
                                    $c = $node->nodeValue;
                                }
                                foreach( $datas4 as $node )
                                {
                                    $e = $node->nodeValue;
                                }
                                foreach( $datas5 as $node )
                                {
                                    $price = $node->nodeValue;
                                    $pric = Iphone_prod::where('price',$price)
                                    ->where('device' , $a)
                                    ->where('condition',$b)
                                    ->where('model',$c)
                                    ->where('network',$val3)
                                    ->where('size', $e)
                                    ->first();
                                }
                                


                                if(!empty($price) &&empty($pric))
                                {
                                        Iphone_prod::create([
                                            'device' => $a,
                                            'condition' => $b,
                                            'model' => $c,
                                            'network' => $val3 ,
                                            'size' => $e,
                                            'price' => $price
                                        ]);
                                    
                                }
                                
                            
                        }

                    }
                }
            }
        }
        
        echo "berhasil";
        
    }

    public function test()
    {
        $network = ['att', 'factory-unlocked', 'other' , 'sprint','t-mobile','verizon','unlocked(AT&T)','unlocked(Sprint)','unlocked(Verizon)','unlocked(T-Mobile)','unlocked(Other)'];
        $model = [
            'iphone-se-2020' => $network,
            'iphone-iphone-11' => $network,
            'iphone-iphone-11-pro' => $network,
            'iphone-iphone-11-pro-max' => $network,
            'iphone-iphone-xs' => $network,
            'iphone-iphone-xs-max' => $network,
            'iphone-iphone-xr' => $network,
            'iphone-iphone-x' => $network,
            'iphone-iphone-8' => $network,
            'iphone-iphone-8-plus' => $network,
            '7' => $network,
            '7-plus' => $network,
            'se' => $network,
            '6s' => $network,
            '6s-plus' => $network,
            '6','6-plus' => $network,
            '5S' => $network,
            '5C' => $network,
            '5' => $network,
            '4s' => $network,
            '4' => $network
        ];


        
        // $mod = array('16gb','32gb','64gb','128gb','256gb','512gb');
        // $network = array('att');
        // $model = array('iphone-se-2020');
        

        // $combined = array_map(null,$mod,$network,$model);

        // foreach ($combined as $datas) {

            // $url = 'https://sellshark.com/sell/iphone/used/'. $datas[2] .'/'.$datas[1].'/'.$datas[0];
            $url = 'https://sellshark.com/sell/iphone/new/iphone-iphone-x/att/64gb';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_PROXY, '');
        
            $data = curl_exec($ch);
            curl_close($ch);

         
        
            $dom = new \DOMDocument();;
            @$dom->loadHTML($data);
        
            $xpath = new \DOMXPath($dom);
        
            $datas = $xpath->query('//*[@id="sell-breadcrumbs-nav"]/div/ul/li[1]/div/a');
            $datas1 = $xpath->query('//*[@id="sell-breadcrumbs-nav"]/div/ul/li[2]/div/a');
            $datas2 = $xpath->query('//*[@id="sell-breadcrumbs-nav"]/div/ul/li[3]/div/a');
            $datas3 = $xpath->query('//*[@id="sell-breadcrumbs-nav"]/div/ul/li[4]/div/a');
            $datas4 = $xpath->query('//*[@id="sell-breadcrumbs-nav"]/div/ul/li[5]/div/a');
            $datas5 = $xpath->query('//*[@id="mydevice"]/div/div/div[2]/div/div/div[1]/span');
            
            foreach( $datas as $node )
            {
                $device = $node->nodeValue;
            }
            foreach( $datas1 as $node )
            {
                $condition = $node->nodeValue;
            }
            foreach( $datas2 as $node )
            {
                $model = $node->nodeValue;
            }
            foreach( $datas3 as $node )
            {
                $network = $node->nodeValue;
            }
            foreach( $datas4 as $node )
            {
                $size = $node->nodeValue;
            }
            foreach( $datas5 as $node )
            {
                $price = $node->nodeValue;
                $pric = Iphone_prod::where('price',$price)
                ->where('device' , $device)
                ->where('condition',$condition)
                ->where('model',$model)
                ->where('network',$network)
                ->where('size', $size)
                ->first();
            }



            

            
            if(!empty($price) &&empty($pric))
            {
                
                    Iphone_prod::create([
                        'device' => $device,
                        'condition' => $condition,
                        'model' => $model,
                        'network' => $network ,
                        'size' => $size,
                        'price' => $price
                    ]);
                
            }



    }

}
