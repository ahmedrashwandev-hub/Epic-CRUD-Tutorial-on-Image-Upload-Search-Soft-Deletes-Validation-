<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request) 
    {
        // $products = Product::all();
        $Query = Product::query();
        if(request()->has("search") && $request->search)
            {
                $query = $Query->where("name","like","%".$request->search."%")
                               ->orWhere("description","like","%".$request->search."%");
            }
            $products = $Query->latest()->paginate(10);
        return view("product.product-list",compact("products"));
    }

    public function create()
    {
        $categories = Category::all();
        return view("product.create" ,compact("categories"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name"        => "required|string",
            "description" => "nullable|string",
            "price"       => "required|string",
            "quantity"    => "required|numeric",
            "status"      => "required",
            "category_id" => "required",
            "image"       => "nullable|image|mimes:jpg,png",
        ]);
        if( $request->hasFile("image") ){
            $validated["image"] = $request->file("image")->store("products","public");
        }
        Product::create($validated);

        return redirect()->route("product.index")->with("success","Product Added Successfully");
    }

    public function show($id)
    {
        $product = Product::find($id);
        return view("product.show" ,compact("product"));
    }

    public function edit($id)
    {
        $categories = Category::all();
        $product = Product::find($id);
        return view("product.edit" ,compact("product","categories","id"));
    }

    public function update(Request $request,$id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            "name"        => "required|string",
            "description" => "nullable|string",
            "price"       => "required|string",
            "quantity"    => "required|numeric",
            "status"      => "required",
            "category_id" => "required",
            "image"       => "nullable|image|mimes:jpg,png",
        ]);
        if ($request->hasFile("image")) {
            if($product->image && Storage::disk("public")->exists($product->image))
                Storage::disk("public")->delete( $product->image );
            $validated["image"] = $request->file("image")->store("products","public");
        }
        Product::find($id)->update($validated);
        return redirect()->route("product.index")->with("success","Product Updated Successfully");
    }

    public function destroy($id)
    {
        Product::find($id)->delete();
        return redirect()->route("product.index")->with("danger","Product Deleted Successfully");
    }

    public function trashedProducts(Request $request)
    {
        $Query = Product::query()->onlyTrashed();
        if(request()->has("search") && $request->search)
            {
                $query = $Query->where("name","like","%".$request->search."%")
                               ->orWhere("description","like","%".$request->search."%");
            }
            $products = $Query->paginate(5);
        return view("product.deleted-products" ,compact("products"));
    }

    public function restoreProduct($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->route("product.index")->with("success","Product Restored Successfully");
    }

    public function destroyProduct($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        if($product->image && Storage::exists($product->image))
            {
                Storage::delete($product->image);
            }
        $product->forceDelete();
        return redirect()->route("product.index")->with("danger","The Product Was Successfully Deleted Permanently");
    }


}

