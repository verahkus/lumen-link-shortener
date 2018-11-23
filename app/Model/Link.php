<?php

namespace App\Model;

use App\Model\Link;
use Illuminate\Database\Eloquent\Model;

class Link extends Model {

    protected $table = 'links';

    protected $fillable = [
        'key',
        'url'
    ];

}