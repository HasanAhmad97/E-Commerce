<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Enumerations\CategoryType;
use App\Http\Requests\GeneralProductRequest;
use App\Http\Requests\MainCategoryRequest;
use App\Http\Requests\ProductImagesRequest;
use App\Http\Requests\ProductPriceValidation;
use App\Http\Requests\ProductStockRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use DB;
use phpDocumentor\Reflection\Types\Collection;

class ProductsController extends Controller
{

    public function index()
    {
        $products = Product::select('id','slug','price', 'created_at')->paginate(PAGINATION_COUNT);
        return view('dashboard.products.general.index', compact('products'));
    }

    public function create()
    {
        $data = [];
        $data['brands'] = Brand::active()->select('id')->get();
        $data['tags'] = Tag::select('id')->get();
        $data['categories'] = Category::active()->select('id')->get();
        $product = Product::first();
        return view('dashboard.products.general.create', $data,compact('product'));
    }

    public function store(GeneralProductRequest $request)
    {


        DB::beginTransaction();

        //validation

        if (!$request->has('is_active'))
            $request->request->add(['is_active' => 0]);
        else
            $request->request->add(['is_active' => 1]);

        $product = Product::create([
            'slug' => $request->slug,
            'brand_id' => $request->brand_id,
            'is_active' => $request->is_active,
        ]);
        //save translations
        $product->name = $request->name;
        $product->description = $request->description;
        $product->short_description = $request->short_description;
        $product->save();

        //save product categories

        $product->categories()->attach($request->categories);
        //save product tags
        DB::commit();
        return view('dashboard.products.prices.create',compact('product'))->with(['success' => '???? ?????????????? ??????????']);

    }



    public function getPrice($product_id){
        $product = Product::whereId($product_id)->get()->first();
        return view('dashboard.products.prices.edit',compact('product')) -> with('id',$product_id) ;
    }

    public function saveProductPrice(ProductPriceValidation $request){

        try{

            Product::whereId($request -> product_id) -> update($request -> only(['price','special_price','special_price_type','special_price_start','special_price_end']));
            return redirect()->route('admin.products')->with(['success' => '???? ?????????????? ??????????']);
        }
        catch(\Exception $ex){

        }
    }

    public function createProductPrice(ProductPriceValidation $request){
//
        Product::whereId($request -> product_id) -> update($request -> only(['price','special_price','special_price_type','special_price_start','special_price_end']));
        $product_id = $request -> product_id;
        return view('dashboard.products.images.create',compact('product_id'))->with(['success' => '???? ?????????????? ??????????']);
    }


    public function getStock($product_id){
        $product = Product::whereId($product_id)->get()->first();
        return view('dashboard.products.stock.edit',compact('product')) -> with('id',$product_id) ;
    }

    public function saveProductStock (ProductStockRequest $request){


            Product::whereId($request -> product_id) -> update($request -> except(['_token','product_id']));

            return redirect()->route('admin.products')->with(['success' => '???? ?????????????? ??????????']);

    }

    public function addImages($product_id){
        return view('dashboard.products.images.create',compact('product_id'))->withId($product_id);
    }

    //to save images to folder only
    public function saveProductImages(Request $request ){

        $file = $request->file('dzfile');
        $filename = uploadImage('products', $file);

        return response()->json([
            'name' => $filename,
            'original_name' => $file->getClientOriginalName(),
        ]);

    }

    public function saveProductImagesDB(ProductImagesRequest $request){

        try {
            // save dropzone images
            if ($request->has('document') && count($request->document) > 0) {
                foreach ($request->document as $image) {
                    Image::create([
                        'product_id' => $request->product_id,
                        'photo' => $image,
                    ]);
                }
            }
            $productId = $request->product_id;
            return view('dashboard.products.stock.create',compact('productId'))->with(['success' => '???? ?????????????? ??????????']);
            return redirect()->route('admin.products')->with(['success' => '???? ?????????????? ??????????']);

        }catch(\Exception $ex){

        }
    }
    public function edit($id)
    {

        //get specific categories and its translations
        $category = Category::orderBy('id', 'DESC')->find($id);

        if (!$category)
            return redirect()->route('admin.maincategories')->with(['error' => '?????? ?????????? ?????? ?????????? ']);

        return view('dashboard.categories.edit', compact('category'));

    }


    public function update($id, MainCategoryRequest $request)
    {
        try {
            //validation

            //update DB


            $category = Category::find($id);

            if (!$category)
                return redirect()->route('admin.maincategories')->with(['error' => '?????? ?????????? ?????? ??????????']);

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

            $category->update($request->all());

            //save translations
            $category->name = $request->name;
            $category->save();

            return redirect()->route('admin.maincategories')->with(['success' => '???? ?????????????? ??????????']);
        } catch (\Exception $ex) {

            return redirect()->route('admin.maincategories')->with(['error' => '?????? ?????? ???? ?????????? ???????????????? ??????????']);
        }

    }


    public function destroy($id)
    {

        try {
            //get specific categories and its translations
            $category = Category::orderBy('id', 'DESC')->find($id);

            if (!$category)
                return redirect()->route('admin.maincategories')->with(['error' => '?????? ?????????? ?????? ?????????? ']);

            $category->delete();

            return redirect()->route('admin.maincategories')->with(['success' => '????  ?????????? ??????????']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.maincategories')->with(['error' => '?????? ?????? ???? ?????????? ???????????????? ??????????']);
        }
    }

}
