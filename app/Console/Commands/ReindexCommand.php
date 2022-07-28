<?php

namespace App\Console\Commands;

use App\Models\Catalog\Benefit;
use App\Models\Catalog\DG\DGProduct;
use Elasticsearch\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all elements to Elasticsearch';

    /**
     * Create a new command instance.
     *
     * @param Client $elasticsearch
     */
    public function __construct(private Client $elasticsearch)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Indexing all elements. This might take a while...');

//        $pageModelData = new PageModelData();
//        $pageModels = $pageModelData->getAllElementCategories();

//        /**
//         * @var PageModel $pageModel
//         */
//        foreach($pageModels as $pageModel) {
//            $model = $pageModel->createObject();
        $search_models = [DGProduct::class/*, Benefit::class*/];
//        $model = DGProduct::class;
        foreach ($search_models as $search_model) {
            $this->info("\nIndexing for model ");
            foreach ($search_model::cursor() as $model) {
                try {
                    $this->elasticsearch->index([
                        'index' => $model->getSearchIndex(),
                        'type' => $model->getSearchType(),
                        'id' => $model->getKey(),
                        'body' => $model->toSearchArray(),
                    ]);

                    $this->output->write('.');
                } catch (\Exception $e) {
                    Log::debug($e->getMessage());
                    $this->output->write('e');
                }
            }
        }

        $this->info('Done!');

        return 0;
    }
}
