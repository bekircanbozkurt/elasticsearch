<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Elastic\Adapter\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Elasticsearch\ClientBuilder;


class ElasticSearch extends Controller
{


    public function index(Request $request, ?string $productId = '1975637')
    {


        $data = [
            'productId' => $productId,
        ];


        return view('chart.chart', $data);
    }

    public function fetchData(Request $request)
    {
        $productId = $request->input('productId');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $data = $this->fetchDataForLineChart($productId, $startDate, $endDate);

        return response()->json($data);

    }



    public function fetchDataForLineChart($productId, $startDate, $endDate)
    {
        $client = ClientBuilder::create()->build();

        $params = [
            'index' => 'counter_index', // this created on ExportToElasticsearch command 
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['term' => ['productId' => $productId]],
                            ['range' => ['unixTime' => ['gte' => $startDate, 'lte' => $endDate]]],
                        ],
                    ],
                ],
                'aggs' => [
                    'group_by_date' => [
                        'date_histogram' => [
                            'field' => 'unixTime',
                            'interval' => 'day',
                            'format' => 'yyyy-MM-dd',
                            'min_doc_count' => 1, // Exclude buckets with zero values
                        ],
                        'aggs' => [
                            'total_views' => [
                                'sum' => ['field' => 'views'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $response = $client->search($params);

        $aggregatedData = [];
        foreach ($response['aggregations']['group_by_date']['buckets'] as $bucket) {
            $aggregatedData[] = [
                'productId' => $productId,
                'unixTime' => $bucket['key_as_string'],
                'views' => $bucket['total_views']['value'],
            ];
        }

        return $aggregatedData;
    }


}
