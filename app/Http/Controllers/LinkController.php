<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\UrlRule;
use App\Model\Link;


class LinkController extends Controller
{
    protected $link;

    public function shortLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'link' => 'required', new UrlRule($request->input('link'))
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $this->link = $request->input('link');

        $link = Link::firstOrCreate(['url' => $this->link]);
        $link->key = $this->encodeId($link->id);
        $link->save();

        return response()->json(['link' => self::getUrlLink($link->key)], 200);
    }

    protected function getUrlLink($key) {
        return config('app.site_url').'/'.$key;
    }

    protected function encodeId($id)
    {
        return str_replace('=', '', base64_encode($id));
    }
}
