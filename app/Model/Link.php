<?php

namespace App\Model;

use App\Model\Link;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema()
 */
class Link extends Model {

    /**
     * @OA\Property(
     *   property="id",
     *   type="integer",
     * )
     * @OA\Property(
     *   property="url",
     *   type="string",
     *   description="url ссылки"
     * )
     * @OA\Property(
     *   property="key",
     *   type="string",
     *   description="код ссылки"
     * )
     */

    protected $table = 'links';

    protected $fillable = [
        'key',
        'url'
    ];

}