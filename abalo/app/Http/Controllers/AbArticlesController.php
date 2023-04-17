<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbArticle;

class AbArticlesController extends Controller
{
    public function getSearch(Request $request) {

        $search = $request->get("search");

        if(empty($search) === true)
        {
            $articles = AbArticle::all();
        }
        else
        {
            $search = strtolower($search);
            $articles = AbArticle::select("id", "ab_name", "ab_price", "ab_description")->where(AbArticle::raw('lower(ab_name)'),'like','%'.$search.'%')->get();
        }

        return view('/articles')->with('articles',$articles);
    }

}
