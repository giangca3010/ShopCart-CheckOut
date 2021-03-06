<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Cart;

class CartController extends Controller
{
    public function index()
    {
        $products = DB::table('product')->get();
        $currentCart = Session('cart') ? Session('cart') : null;
        $totalQuanty = $currentCart->totalQuanty ?? 0;
//        dd($totalQuanty);
        return view('index', compact('products', 'totalQuanty'));
    }

    public function addToCart(Request $req, $id)
    {
        $product = DB::table('product')->where('id', $id)->first();
        if ($product != null) {
            $oldCart = Session('cart') ? Session('cart') : null;
            $newCart = new Cart($oldCart);
            $newCart->AddCart($product, $id);
            $req->session()->put('cart', $newCart);
            $totalQuanty = $newCart->totalQuanty ?? 0;
        }
        return view('cart', compact('newCart', 'totalQuanty'));
    }

    public function deleteCart(Request $req, $id)
    {
        $oldCart = Session('cart') ? Session('cart') : null;
        $newCart = new Cart($oldCart);
        $newCart->DeleteItemCart(trim($id));

        if (count($newCart->products) > 0) {
            $req->session()->put('cart', $newCart);
        } else {
            $req->Session()->forget('cart');
        }
        return view('cart', compact('newCart'));

    }

    public function getQuantyCart()
    {
        $currentCart =  Session('cart') ? Session('cart') : null;
        return $currentCart->totalQuanty ?? 0;
    }

    public function viewListCart()
    {
        return view('list');
    }

    public function deleteItemListCart(Request $req, $id)
    {
        $oldCart = Session('cart') ? Session('cart') : null;
        $newCart = new Cart($oldCart);
        $newCart->DeleteItemCart(trim($id));

        if (count($newCart->products) > 0) {
            $req->session()->put('cart', $newCart);
        } else {
            $req->Session()->forget('cart');
        }
        return view('list-cart', compact('newCart'));
    }

    public function saveItemListCart(Request $req, $id, $quanty)
    {
        $oldCart = Session('cart') ? Session('cart') : null;
        $newCart = new Cart($oldCart);
        $newCart->UpdateItemCart(trim($id), $quanty);

        $req->session()->put('cart', $newCart);

        return view('list-cart', compact('newCart'));
    }
}
