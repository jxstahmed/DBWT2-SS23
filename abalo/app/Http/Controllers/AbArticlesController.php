<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\AbArticle;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AbArticlesController extends Controller {
    public function getSearch(Request $request) {
        $search = $request->get("search");

        if (empty($search) === true) {
            $articles = AbArticle::all()->take(5);
        } else {
            $search = strtolower($search);
            $articles = AbArticle::select("id", "ab_name", "ab_price", "ab_description")->where(AbArticle::raw('lower(ab_name)'), 'like', '%' . $search . '%')->take(5)->get();
        }

        return view('/articles.articles')->with('articles', $articles);
    }

    public function getCreateArticleRouter(Request $request) {
        return view('/articles.newarticle');
    }

    public function createArticle(Request $request) {
        $v = Validator::make($request->all(), array(
            'article_name' => 'required',
            'article_price' => 'required|numeric|min:0',
            'article_description' => 'required',
        ));

        if ($v->fails()) {
            return response()->json(["message" => "Please validate your input! " . implode(" | ", $v->messages()->all())], 200);
        }

        $article_name = $request->get("article_name");
        $article_price = $request->get("article_price");
        $article_description = $request->get("article_description");

        AbArticle::create([
            "ab_name" => $article_name,
            "ab_price" => $article_price,
            "ab_description" => $article_description,
            "ab_creator_id" => 1,
            "ab_createdate" => Carbon::now()
        ]);

        return response()->json(["message" => "Article has been created!"], 200);
    }

}
