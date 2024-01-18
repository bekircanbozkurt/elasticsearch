<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Elasticsearch\ClientBuilder;
use App\Models\Counter;

class ExportToElasticsearch extends Command
{
    protected $signature = 'export:elasticsearch';
    protected $description = 'Export data from MySQL to Elasticsearch for Suvido task!';

    public function handle()
    {

        $elasticsearch = ClientBuilder::create()->build();
        $indexName = 'counter_index';

        $params = [
            'index' => $indexName,
            'body' => [
                'mappings' => [
                    'counter_type' => [
                        'properties' => [
                            'unixTime' => [
                                'type' => 'date',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $elasticsearch->indices()->create($params);

        $data = Counter::all();

        foreach ($data as $record) {
            $params = [
                'index' => $indexName,
                'body' => [
                    'productId' => $record->productId,
                    'unixTime' => date('Y-m-d', $record->unixTime),
                    'views' => $record->views,
                ],
                'type' => 'counter_type',

            ];

            $elasticsearch->index($params);
        }

        $this->info('Data exported to Elasticsearch successfully.');
    }
}
