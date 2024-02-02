<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class ProductController extends Controller
{
    //index
    public function index()
    {
        $products = \App\Models\Product::paginate(20);
        return view('pages.product.index', compact('products'));
    }

    //create
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('pages.product.create', compact('categories'));
    }

    //store
    public function store(Request $request)
    {
        $data = $request->all();
        $product = new Product();
        $product->name = $data['name'];
        $product->price = (int) $data['price'];
        $product->stock = (int) $data['stock'];
        $product->category_id = $data['category_id'];
        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/products', $filename);
            $product->image = $filename;
        }
        $product->save();
        return redirect()->route('product.index');
    }

    //show
    public function show($id)
    {
        $product = \App\Models\Product::find($id);
        return view('pages.product.show', compact('product'));
    }

    //edit
    public function edit($id)
    {
        $product = \App\Models\Product::find($id);
        $categories = \App\Models\Category::all();
        return view('pages.product.edit', compact('product', 'categories'));
    }

    //update
    //update
public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $filename = $product->image;
    //check image
    if ($request->hasFile('image')) {
        $filename = time() . '.' . $request->image->extension();
        $request->image->storeAs('public/products', $filename);
        $product->image = $filename;
    }

    $product->update([
        'name' => $request->name,
        'price' => (int) $request->price,
        'stock' => (int) $request->stock,
        'category_id' => $request->category_id,
        'image' => $filename,
    ]);
    return redirect()->route('product.index')->with('success', 'Product successfully updated');
}

    //destroy
    public function destroy($id)
    {
        $product = \App\Models\Product::find($id);
        $product->delete();
        return redirect()->route('product.index');
    }

}
