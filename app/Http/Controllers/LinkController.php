<?php

namespace App\Http\Controllers;

use App\Events\LogEvent;
use App\Jobs\StatLinkJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use App\Rules\UrlRule;
use App\Model\Link;


class LinkController extends Controller
{
    protected $link;

    /**
     * сокращение ссылки
     *
     * @OA\Post(
     *   path="/short_link",
     *   summary="Сокращение ссылки",
     *   operationId="shortLink",
     * @OA\Parameter(
     *    in="query",
     *    name="link",
     *    required=true,
     *    @OA\Schema(type="string"),
     *    example="http://127.0.0.1/test",
     *    description="ссылка для сокращения",
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Ссылка сделана",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="link",
     *                  type="string",
     *                  description="сокращенная ссылка"
     *              )
     *         )
     *     ),
     * ),
     * @OA\Response(
     *     response="400",
     *     description="validation.required или validation.correctness"
     * )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function shortLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'link' => 'required|correctness'
        ]);

        if ($validator->fails()) {
            Event::fire(new LogEvent(trans('app.validator_fails'),$request->input('link'),$request->server));
            return response()->json($validator->messages(), 400);
        }

        $this->link = $request->input('link');

        $link = Link::firstOrCreate(['url' => $this->link]);
        $link->key = $this->encodeId($link->id);
        $link->save();

        //статистика
        dispatch(new StatLinkJob($link,0));

        //логирование
        Event::fire(new LogEvent(trans('app.response_link'),$request->input('link'),$request->server));
        return response()->json(['link' => self::getUrlLink($link->key)], 200);
    }

    protected function getUrlLink($key) {
        return config('app.site_url').$key;
    }

    protected function encodeId($id)
    {
        return str_replace('=', '', base64_encode($id));
    }
}
