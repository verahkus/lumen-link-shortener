<?php

namespace App\Http\Controllers;

use App\Events\LogEvent;
use App\Jobs\StatLinkJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use App\Model\Link;

class LinkController extends Controller
{
    protected $link;
    protected $result_link;

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
        $validator = $this->validatorShortLink($request);

        if ($validator->fails()) {
            //логирование
            Event::fire(new LogEvent(trans('app.validator_shortLink_fails'),$request->input('link'),$request->server));
            return response()->json($validator->messages(), 400);
        }

        $this->link = $request->input('link');

        $link = Link::firstOrCreate([
            'url' => preg_replace("#/$#", "", $this->link)
        ]);
        $link->key = $this->encodeId($link->id);
        $link->save();

        //статистика
        dispatch(new StatLinkJob($link,0));

        //логирование
        Event::fire(new LogEvent(trans('app.response_shortLink'),$request->input('link'),$request->server));
        return response()->json(['link' => self::getUrlLink($link->key)], 200);
    }

    /**
     * получение ссылки по сокращенной ссылке
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLink(Request $request)
    {
        $validator = $this->validatorGetLink($request);

        if ($validator->fails()) {
            //логирование
            Event::fire(new LogEvent(trans('app.validator_getLink_fails'),$request->input('link'),$request->server));
            return response()->json($validator->messages(), 400);
        }

        $this->link = $request->input('link');
        $this->result_link = Link::find($this->getIdLink());

        if ($this->result_link) {
            //статистика
            dispatch(new StatLinkJob($this->result_link,1));

            //логирование
            Event::fire(new LogEvent(trans('app.response_getLink'),$request->input('link'),$request->server));
            return response()->json(['link' => $this->result_link->url], 200);
        } else {
            //логирование
            Event::fire(new LogEvent(trans('app.response_getLink_notFound'),$request->input('link'),$request->server));
            return response()->json(['link' => array('validator.not_found')], 404);
        }
    }

    protected function validatorShortLink($request)
    {
        return Validator::make($request->all(), [
            'link' => 'required|correctness'
        ]);
    }

    protected function validatorGetLink($request)
    {
        return Validator::make($request->all(), [
            'link' => 'required|correctness|base64key'
        ]);
    }

    protected function getIdLink() {
        return base64_decode(str_replace(config('app.site_url'),"",$this->link),true);
    }

    protected function getUrlLink($key) {
        return config('app.site_url').$key;
    }

    protected function encodeId($id)
    {
        return str_replace('=', '', base64_encode($id));
    }
}
