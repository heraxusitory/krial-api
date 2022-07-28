<?php


namespace App\Services;


use App\Interfaces\ISearchRepository;
use App\Models\Catalog\Benefit;
use App\Models\Catalog\DG\DGProduct;
use Elasticsearch\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SearchService implements ISearchRepository
{
    public function __construct(private Client $elasticsearch)
    {
    }

    public function search(string $query = ''): Collection
    {
//        $this->params = new SearchParam($params);
        $items = $this->searchOnElasticsearch($query);
        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch(string $query): array
    {
//        $pageModelData = new PageModelData();
//        $pageModels = $pageModelData->getAllElementCategories();
        $collections = [];
        $queryStrArray = explode(' ', $query);
        $must = [];
        foreach ($queryStrArray as $queryStr) {
            $must[] = [
                'bool' => [
                    'should' => [
                        [
                            'wildcard' => [
                                'name' => [
                                    'value' => mb_strtolower($queryStr) . '*',
                                    'boost' => 2.0
                                ]
                            ]
                        ],
                        [
                            'wildcard' => [
                                'name' => [
                                    'value' => '*' . mb_strtolower($queryStr) . '*',
                                    'boost' => 2.0
                                ]
                            ]
                        ],
                        [
                            'wildcard' => [
                                'name' => [
                                    'value' => mb_strtolower($queryStr),
                                    'boost' => 2.0
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }

        $models = [new DGProduct()/*, new Benefit()*/];
        $model = new DGProduct();
//        foreach ($pageModels as $key => $pageModel) {
//            $model = $pageModel->createObject();
//            $collections[$key]['page_model'] = $pageModel;
//        foreach ($models as $model) {
            $items = $this->elasticsearch->search([
                'index' => $model->getSearchIndex(),
                'type' => $model->getSearchType(),
                'body' => [
                    'query' => [
                        'bool' => [
                            'must' => $must
                        ]
                    ],
                ],
            ]);
//        }

        return $items;
    }

    private function buildCollection(array $items): Collection
    {
        $collecton = [];
//        foreach ($items as $class => $item)
            $ids = Arr::pluck($items['hits']['hits'], '_id');
        $collecton['products'] = DGProduct::findMany($ids)
                ->sortBy(function ($product) use ($ids) {
                    return array_search($product->getKey(), $ids);
                });
//        }
        return collect($collecton);
    }
}
