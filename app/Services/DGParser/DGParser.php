<?php


namespace App\Services\DGParser;


use App\Services\DGParser\DGBrand;
use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;

class DGParser
{

    private string $manufactureSelector;
    private \simplehtmldom_1_5\simple_html_dom $dg_parser;
    private array $brands = [];

    public function __construct(private string $target_url)
    {
        $request = (new Client())->request('get', 'https://www.allgen.ru/generator/');
        $dg_page_content = $request->getBody()->getContents();
        $this->dg_parser = HtmlDomParser::str_get_html($dg_page_content);
    }

    public function setManufactureSelector(string $selector)
    {
        $this->manufactureSelector = $selector;
        return $this;
    }

    public function start()
    {
        foreach ($this->dg_parser->find($this->manufactureSelector) as $manufacture) {
            $products_link = $manufacture->href;
            $manufacture = trim(str_replace([PHP_EOL . "\r", "\r\n", "\r \n"], ' ', $manufacture->text()));
            $brand_name = trim(preg_replace("/[[(].*[)]/", '', $manufacture));
            $country = trim(preg_replace("/^.*[(]|[)]/", '', $manufacture));
            $this->brands[] = (new DGBrand($brand_name, $country, $products_link));
        }

        return $this;
    }

    public function getBrands()
    {
        return $this->brands;
    }


    public function getManufactureLink()
    {
//        return
    }

    public function getElementList(string $attribute)
    {
        return $this->html->find($attribute);
    }
}
