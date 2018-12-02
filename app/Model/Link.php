<?php

namespace App\Model;

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
     * @OA\Property(
     *   property="stat_in",
     *   type="integer",
     *   default=0,
     *   description="статистика обращений сокращения ссылки"
     * )
     * @OA\Property(
     *   property="stat_out",
     *   type="integer",
     *   default=0,
     *   description="статистика обращений получения ссылки"
     * )
     */

    protected $table = 'links';

    protected $fillable = [
        'key',
        'url',
        'stat_in',
        'stat_out',
    ];

}