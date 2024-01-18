<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Counter extends Model
{
    use Searchable;


    protected $fillable = ['productId', 'unixTime', 'views'];


    protected $mapping = [
        'properties' => [
            'unixTime' => [
                'type' => 'date',
                'format' => 'epoch_seconds'
            ],
        ],
    ];

    protected $table = 'counter';
    public function toSearchableArray()
    {
        return [
            'productId' => $this->productId,
            'unixTime' => $this->unixTime,
            'views' => $this->views,
        ];
    }


}
