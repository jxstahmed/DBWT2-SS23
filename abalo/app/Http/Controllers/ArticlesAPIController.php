<?php

namespace App\Http\Controllers;

use App\Models\AbArticle;
use App\Models\AbUser;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use function Ratchet\Client\connect;

class ArticlesAPIController extends Controller {
    public function getArticles_api(Request $request) {
        $creator_id = 1;
        $search = $request->get("search");
        $page = $request->get("page");


        $articles = AbArticle::select("id", "ab_name", "ab_price", "ab_description", "ab_creator_id")->take(5);


        if(empty($search) === false) {
            $search = strtolower($search);
            $articles = $articles->where(AbArticle::raw('lower(ab_name)'), 'like', '%' . $search . '%');
        }

        $articles = $articles->with(["cart_item" => function($q) use ($creator_id) {
            $q->whereHas("cart", function ($w) use ($creator_id) {
                $w->where("ab_creator_id", $creator_id);
            });
        }]);


        $total_count = $articles->count();

        $rows_per_page = 5;
        $rows_skipped = $page * $rows_per_page;
        $articles = $articles->skip($rows_skipped)->take($rows_per_page);


        $articles = $articles->get();

        return response()->json(["articles" => $articles, "total_count" => $total_count], 200);
    }

    public function createArticles_api(Request $request) {
        $v = Validator::make($request->all(), array(
            'article_name' => 'required',
            'article_price' => 'required|numeric|min:1',
            'article_description' => 'required',
            'email' => 'required',
        ));

        if ($v->fails()) {
            return response()->json(["message" => "Please validate your input! " . implode(" | ", $v->messages()->all())], 500);
        }

        $article_name = $request->get("article_name");
        $article_price = $request->get("article_price");
        $article_description = $request->get("article_description");

        $user = AbUser::where("ab_mail", $request->get("email"))->first();

        $article = AbArticle::create([
            "ab_name" => $article_name,
            "ab_price" => $article_price,
            "ab_description" => $article_description,
            "ab_creator_id" => $user->id,
            "ab_createdate" => Carbon::now()
        ]);

        return response()->json(["message" => "Article has been created!", "id" => $article->id], 200);
    }

    public function getCartItems_api(Request $request) {
        // Because we don't have a user system, we will assume that the creator_id = 1
        $creator_id = 1;

        $items = ShoppingCartItem::with("article")->whereHas("article")->whereHas("cart", function($q) use ($creator_id) {
            $q->where("ab_creator_id", $creator_id);
        })->get();

        return response()->json(["items" => $items], 200);
    }


    public function addCartItem_api(Request $request) {
        // Because we don't have a user system, we will assume that the creator_id = 1
        $creator_id = 1;


        $v = Validator::make($request->all(), array(
            'articleid' => 'required',
        ));

        if ($v->fails()) {
            return response()->json(["message" => "Please validate your input! " . implode(" | ", $v->messages()->all())], 200);
        }

        $articleid = $request->get("articleid");
        $article = AbArticle::where("id", $articleid)->first();

        if(empty($article) === true) {
            return response()->json(["message" => "Article not found!"], 500);
        }

        $shopping_cart = ShoppingCart::where("ab_creator_id", $creator_id)->first();
        if(empty($shopping_cart) === true) {
            $shopping_cart = ShoppingCart::create([
                "ab_creator_id" => $creator_id,
                "ab_createdate" => Carbon::now()
            ]);
        }

        $shopping_cart_item = ShoppingCartItem::where("ab_shoppingcart_id", $shopping_cart->id)->where("ab_article_id", $article->id)->first();

        if(empty($shopping_cart_item) === false) {
            return response()->json(["message" => "Shopping cart item already exists!"], 500);
        }

        $shopping_cart_item = ShoppingCartItem::create([
            "ab_shoppingcart_id" => $shopping_cart->id,
            "ab_article_id" => $article->id,
            "ab_createdate" => Carbon::now()
        ]);

        return response()->json(["message" => "Cart item has been added!", "id" => $shopping_cart_item->id], 200);
    }

    public function removeCartItem_api(Request $request, $shoppingcardid, $articleId) {
        // Because we don't have a user system, we will assume that the creator_id = 1
        $creator_id = 1;
        $shopping_cart_item = collect();

        if($shoppingcardid == 0) {
            // it was removed via list
            $shopping_cart_item = ShoppingCartItem::whereHas("cart", function($q) use ($creator_id) {
                $q->where("ab_creator_id", $creator_id);
            })->where("ab_article_id", $articleId)->first();
        } else {
            $shopping_cart_item = ShoppingCartItem::where("ab_shoppingcart_id", $shoppingcardid)->where("ab_article_id", $articleId)->first();
        }



        if(empty($shopping_cart_item) === true) {
            return response()->json(["message" => "Shopping cart item doesn't exist!"], 500);
        }

        $shopping_cart_item->delete();

        return response()->json(["message" => "Cart item has been deleted!"], 200);
    }

    public function sellArticle_api(Request $request, $articleId) {
        Log::debug("Trying to emit a message to the broadcaster.");

        $article = AbArticle::find($articleId);

        if(empty($article) === true) {
            return response()->json(["message" => "Der Artikel wurde nicht gefunden."], 500);
        }

        $user = AbUser::where("id", $article->ab_creator_id)->first();

        if(empty($user) === true) {
            return response()->json(["message" => "Der Verkäufer wurde nicht gefunden."], 500);
        }

        $title = $article->ab_name;
        $message = "Großartig! Ihr Artikel $title wurde erfolgreich verkauf!“";

        connect('ws://localhost:8085/broadcast?email=none')->then(function($conn) use ($user, $message) {
            $conn->on('message', function($msg) use ($conn, $user, $message) {
                Log::debug("Message has been received.");
                Log::debug($msg);
                $conn->close();
            });
            $conn->send(json_encode(["type" => "article_sold", "message" => $message, "email" => $user->ab_mail]));
            $conn->close();
        }, function ($e) {
            Log::debug("Couldn't emit to the websocket.");
            Log::debug($e->getMessage());
        });

        return response()->json(["message" => "Der Artikel wurde gekauft."], 200);
    }


    public function offerArticle_api(Request $request, $articleId) {
        Log::debug("Trying to emit a message to the broadcaster.");

        $article = AbArticle::find($articleId);

        if(empty($article) === true) {
            return response()->json(["message" => "Der Artikel wurde nicht gefunden."], 500);
        }

        $user = AbUser::where("id", $article->ab_creator_id)->first();

        if(empty($user) === true) {
            return response()->json(["message" => "Der Verkäufer wurde nicht gefunden."], 500);
        }

        $title = $article->ab_name;
        $message = "„Der Artikel $title wird nun günstiger angeboten! Greifen Sie schnell zu";

        connect('ws://localhost:8085/broadcast?email=none')->then(function($conn) use ($user, $message, $article) {
            $conn->on('message', function($msg) use ($conn, $user, $message, $article) {
                Log::debug("Message has been received.");
                Log::debug($msg);
                $conn->close();
            });
            $conn->send(json_encode(["type" => "article_offer", "message" => $message, "article_id" => $article->id, "creator_email" => $user->ab_mail]));
            $conn->close();
        }, function ($e) {
            Log::debug("Couldn't emit to the websocket.");
            Log::debug($e->getMessage());
        });

        return response()->json(["message" => "Der Artikel wurde angeboten."], 200);
    }
}
