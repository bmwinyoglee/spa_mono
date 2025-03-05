<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

// use App\Models\User;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = auth()
                    ->user()
                    ->products()
                    ->with('category')
                    ->where(function ($query){
                        if($search = request()->search){
                            $query->where('name', 'like','%'.$search.'%')
                                ->orWhereHas('category', function ($query) use ($search){
                                    $query->where('name', 'like','%'.$search.'%');
                                });
                        }
                    })
                    ->when(!request()->query('sort_by'), function($query){
                        $query->latest();
                    })

                    ->when(in_array(request()->query('sort_by'), ['name', 'price','weight']), function($query){
                        $sortBy = request()->query('sort_by');
                        $field = ltrim($sortBy, '-');
                        $direction = substr($sortBy, 0,1) === '-' ? 'desc' : 'asc';
                        $query->orderBy($field, $direction);
                    })

                    ->paginate(10)
                    ->withQueryString();

        // return ProductResource::collection($products);
        return inertia('Product/Index',[
            'products' => ProductResource::collection($products),
            'query' => (object) request()->query()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      
        return inertia('Product/Create', [
            'categories' => CategoryResource::collection(Category::orderBy('name')->get()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $request->user()->products()->create($request->validated());
        
        return redirect()
                ->route('product.index')
                ->with('message', 'Product has been created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return inertia('Product/Show', [
            'product' => ProductResource::make($product)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return inertia('Product/Edit', [
            'categories' => CategoryResource::collection(Category::orderBy('name')->get()),
            'product' => ProductResource::make($product)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {

        // dd($product->id);
        //persist data froom the request into the database
        $product->update($request->validated());

        //redirect user to the index page with list of products.
        return redirect()
                ->route('product.show', $product->id)
                ->with('message', 'Product has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
                ->route('product.index')
                ->with('message', 'Product has been deleted successfully');        
    }
}
