<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('id','desc')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'description'=>'nullable|string',
            'price'=>'required|numeric',
            'stock'=>'nullable|integer',
            'category_id'=>'nullable|exists:categories,id',
            'image'=>'nullable|image|max:5120',
            'image_url'=>'nullable|url'
        ]);

        // If image uploaded
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products','public');
        }
        // If image URL provided (download it)
        elseif (!empty($request->image_url)) {
            try {
                $ext = pathinfo(parse_url($request->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                $name = 'products/'.Str::random(12).'.'.$ext;
                $contents = @file_get_contents($request->image_url);
                if ($contents) {
                    Storage::disk('public')->put($name, $contents);
                    $data['image'] = $name;
                }
            } catch (\Exception $e) { /* ignore download failure */ }
        }

        Product::create($data);
        return redirect()->route('admin.products.index')->with('success','Product created.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product','categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'description'=>'nullable|string',
            'price'=>'required|numeric',
            'stock'=>'nullable|integer',
            'category_id'=>'nullable|exists:categories,id',
            'image'=>'nullable|image|max:5120',
            'image_url'=>'nullable|url'
        ]);

        if ($request->hasFile('image')) {
            // delete old
            if ($product->image) { Storage::disk('public')->delete($product->image); }
            $data['image'] = $request->file('image')->store('products','public');
        } elseif (!empty($request->image_url)) {
            // try download
            try {
                $ext = pathinfo(parse_url($request->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                $name = 'products/'.Str::random(12).'.'.$ext;
                $contents = @file_get_contents($request->image_url);
                if ($contents) {
                    Storage::disk('public')->put($name, $contents);
                    $data['image'] = $name;
                }
            } catch (\Exception $e) {}
        }

        $product->update($data);
        return redirect()->route('admin.products.index')->with('success','Product updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) { Storage::disk('public')->delete($product->image); }
        $product->delete();
        return back()->with('success','Product deleted.');
    }

    // Simple import (CSV) without packages
    public function importForm()
    {
        return view('admin.products.import');
    }

    public function import(Request $request)
    {
        $request->validate(['file'=>'required|file|mimes:csv,txt,xlsx']);
        $path = $request->file('file')->getRealPath();
        // support CSV only here for simplicity
        if (($handle = fopen($path, 'r')) !== false) {
            $header = null;
            while (($row = fgetcsv($handle, 0, ',')) !== false) {
                if (!$header) { $header = $row; continue; }
                $data = array_combine($header, $row);
                // create category if provided
                $catId = null;
                if (!empty($data['category'])) {
                    $cat = Category::firstOrCreate(['name'=>trim($data['category'])]);
                    $catId = $cat->id;
                }
                $imagePath = null;
                if (!empty($data['image_url'])) {
                    try {
                        $ext = pathinfo(parse_url($data['image_url'], PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                        $name = 'products/'.Str::random(12).'.'.$ext;
                        $contents = @file_get_contents($data['image_url']);
                        if ($contents) { Storage::disk('public')->put($name, $contents); $imagePath = $name; }
                    } catch (\Exception $e) {}
                }
                Product::create([
                    'name'=>$data['name'] ?? 'No name',
                    'description'=>$data['description'] ?? null,
                    'price'=> (float)($data['price'] ?? 0),
                    'stock'=> (int)($data['stock'] ?? 0),
                    'category_id'=>$catId,
                    'image'=>$imagePath
                ]);
            }
            fclose($handle);
        }
        return redirect()->route('admin.products.index')->with('success','Import finished.');
    }
}
