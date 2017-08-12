<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;

use App\Card;

use Auth;

class ProductsController extends Controller
{
    /**
     * Show the application products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(4);

        return view('products', compact('products'));
    }

    public function addToCard($product_id)
    {
        $card = new Card();
        $card->product_id = $product_id;
        $card->user_id = Auth::user()->id;
        $card->save();

        return back()->with('success', 'product Added To Your Card Successfully');
    }

    public function getCard()
    {
        $cards = Auth::user()->card()->with('product')->paginate(4);

        return view('cards', compact('cards'));
    }
}
