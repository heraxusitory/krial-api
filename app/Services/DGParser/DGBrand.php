<?php


namespace App\Services\DGParser;


use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;

class DGBrand
{
    public $brand_products_parser;
    private string $brand_description;

    public function __construct(private $name, private $country, private $products_link)
    {
    }

    public function getBrandProducts()
    {
        $stream_opts = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ]);

        $request = (new \GuzzleHttp\Client())->post('https://www.allgen.ru/listgenset.php', [
            $stream_opts,
            'form_params' => [
                'brand' => $this->name,
                'minkva' => '0',
                'maxkva' => '10000'
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => '*/*',
            ],
        ]);

        $brand_products_page_content = $request->getBody()->getContents();
//        dd($brand_products_page_content);
        $this->brand_products_parser = HtmlDomParser::str_get_html($brand_products_page_content);

//          dd($this->brand_description);

        return $this->brand_products_parser;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getBrandDescription()
    {
        $request2 = (new Client())->get($this->products_link);
        $response2 = $request2->getBody()->getContents();

        $request2_parser = HtmlDomParser::str_get_html($response2);
        $this->brand_description = $request2_parser->find('.main .content .manufacturer div.manufacturer-body')[0]->innertext();
//
        return $this->brand_description;
    }

    public function getCountry()
    {
        return $this->country;
    }
}
