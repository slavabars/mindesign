<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $categories = \App\Categories::whereParent(null)->get();
    $products = \Illuminate\Support\Facades\DB::table('offers')
        ->join('products_offers','products_offers.offers_id','=','offers.id')
        ->join('products','products.id','=','products_offers.product_id')
        ->selectRaw('products.*')
        ->orderBy('offers.sales','desc')
        ->limit(20)->get();

    return view('main.index', compact('categories','products'));
});


Route::get('/category/{alias}', function ($alias) {

    $category = \App\Categories::whereAlias($alias)->first();
    if(is_null($category)) abort(404);

    $categories = \App\Categories::whereParent(null)->get();
    $products = \Illuminate\Support\Facades\DB::table('products')
        ->join('products_categories','products_categories.product_id','=','products.id')
        ->where('products_categories.categories_id','=',$category->id)
        ->selectRaw('products.*')
        ->get();

    return view('main.index', compact('categories','products'));
});

Route::post('/search', function (\Illuminate\Http\Request $request) {

    $categories = \App\Categories::whereParent(null)->get();
    $products = \App\Product::where('title','like', '%'.$request->get('key').'%')
        ->orWhere('description','like', '%'.$request->get('key').'%')
        ->get();

    return view('main.index', compact('categories','products'));
});

Route::get('/update', function () {

    $markethot = file_get_contents('https://markethot.ru/export/bestsp');
    $json = json_decode($markethot);

    foreach ($json->products as $product){
        dump($product);

        $offer_array = [];
        $category_array = [];

        $productS = \App\Product::find($product->id);
        if(is_null($productS)){
            $productS = new \App\Product;
        }
        $productS->id = $product->id;
        $productS->title = $product->title;
        $productS->image = $product->image;
        $productS->description = $product->description;
        $productS->first_invoice = $product->first_invoice;
        $productS->url = $product->url;
        $productS->price = $product->price;
        $productS->amount = $product->amount;
        if($productS->save()){
            foreach ($product->offers as $offer){
                $offerS = \App\Offers::find($offer->id);
                if(is_null($offerS)){
                    $offerS = new \App\Offers;
                }
                $offerS->id = $offer->id;
                $offerS->price = $offer->price;
                $offerS->amount = $offer->amount;
                $offerS->sales = $offer->sales;
                $offerS->article = $offer->article;
                if($offerS->save()){
                    $offer_array[] = $offerS->id;
                }
            }
            foreach ($product->categories as $category){
                $categoryS = \App\Categories::find($category->id);
                if(is_null($categoryS)){
                    $categoryS = new \App\Categories;
                }
                $categoryS->id = $category->id;
                $categoryS->title = $category->title;
                $categoryS->alias = $category->alias;
                $categoryS->parent = $category->parent;
                $categoryS->acrm_id = $category->acrm_id;
                if($categoryS->save()){
                    $category_array[] = $categoryS->id;
                }
            }
        }
        if(count($offer_array)>0){
            $productS->offers()->sync($offer_array);
        }
        if(count($offer_array)>0){
            $productS->categories()->sync($category_array);
        }
    }

    return exit;
});