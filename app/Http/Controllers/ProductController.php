<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Products;
use App\Models\ProductMeta;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function listingproduct()
    {
        $productcategory = ProductCategory::all();
        return view('product.listing_product', ['productcategory' => $productcategory]);
    }


    public function createproduct()
    {
        $manufacture = Manufacturer::all();
        $product = ProductCategory::get();
        $technicians = User::where('role', 'technician')->get();
        return view('product.create_product', compact('product', 'manufacture', 'technicians'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
       

        $adminId = Auth::id();

        // Handle image upload
        if ($request->hasFile('product_image')) {
            $categoryImage = $request->file('product_image');
            $imageName = time() . '_' . $categoryImage->getClientOriginalName();
            $categoryImage->move(public_path('product_image'), $imageName);
        } else {
            $imageName = null;
        }

        // Create a new product
        $product = new Products([
            'product_name' => $request->input('product_name'),
            'product_manu_id' => $request->input('product_manu_id'),


            'product_short' => $request->input('product_short'),
            'product_category_id' => $request->input('product_category_id'),
            'status' => $request->input('status'),
            'base_price' => $request->input('base_price'),
            'discount' => $request->input('discount'),
            'product_code' => $request->input('product_code'),
            'total' => $request->input('total'),
            'stock' => $request->input('stock'),
            'product_description' => $request->input('product_description'),
            'created_by' => $adminId,
            'updated_by' => $adminId,
            'assigned_to' => $request->input('assigned_to'),

            'product_image' => $imageName,
        ]);
        $product->save();
        $productId = $product->product_id;
        $currentTimestamp = now();

        // Create an array of product meta data
        $productmeta = [
            ['product_id' => $productId, 'meta_key' => 'Color', 'meta_value' => $request['Color']],
            ['product_id' => $productId, 'meta_key' => 'Sizes', 'meta_value' => $request['Sizes']],
            ['product_id' => $productId, 'meta_key' => 'material', 'meta_value' => $request['material']],
            ['product_id' => $productId, 'meta_key' => 'weight', 'meta_value' => $request['weight']],
            ['product_id' => $productId, 'meta_key' => 'created_at', 'meta_value' => $currentTimestamp],
            ['product_id' => $productId, 'meta_key' => 'updated_at', 'meta_value' => $currentTimestamp],
        ];
        $productCategoryId = $request->input('product_category_id');
        // Insert the product meta data into the database
        ProductMeta::insert($productmeta);


        // dd("end");
        $assignedTo = $request->input('assigned_to');

        if ($assignedTo === 'all') {
            // $technicianIds = User::where('role', 'technician')->pluck('id')->toArray();
            $technicianIds = $request->input('technician_id', []);
        } elseif ($assignedTo === 'selected') {
            $technicianIds = $request->input('technician_id', []);
        } else {

            return redirect()->route('product.listingproduct', ['product_id' => $productCategoryId])
                ->with('success', 'Product & Material created successfully');
        }

        foreach ($technicianIds as $technicianId) {
            DB::table('products_assigned')->insert([
                'product_id' => $productId,
                'technician_id' => $technicianId,
            ]);
        }




        return redirect()->route('product.index')
            ->with('success', 'Product & Material created successfully');
    }


    public function update(Request $request, $id)
    {
        // dd($request->all());
       

        $adminId = Auth::id();

        // Find the product by ID
        $product = Products::find($id);

        // Handle image upload
        if ($request->hasFile('product_image')) {
            $categoryImage = $request->file('product_image');
            $imageName = time() . '_' . $categoryImage->getClientOriginalName();
            $categoryImage->move(public_path('product_image'), $imageName);

            // Delete the previous image if it exists
            if ($product->product_image) {
                $imagePath = public_path('product_image') . '/' . $product->product_image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Update the product with the new image
            $product->update([
                'product_image' => $imageName,
            ]);
        }

        // Update other product fields
        $product->update([
            'product_name' => $request->input('product_name'),
            'product_short' => $request->input('product_short'),
            'product_manu_id' => $request->input('product_manu_id'),


            'product_category_id' => $request->input('product_category_id'),
            'status' => $request->input('status'),
            'base_price' => $request->input('base_price'),
            'discount' => $request->input('discount'),
            'total' => $request->input('total'),
            'product_code' => $request->input('product_code'),
            'stock' => $request->input('stock'),
            'product_description' => $request->input('product_description'),
            'updated_by' => $adminId,
            'created_by' => $adminId,
        ]);

        // Update associated product meta data
        $currentTimestamp = now();
        $productId = $product->product_id;

        $productmeta = [
            ['product_id' => $productId, 'meta_key' => 'Color', 'meta_value' => $request['Color']],
            ['product_id' => $productId, 'meta_key' => 'Sizes', 'meta_value' => $request['Sizes']],
            ['product_id' => $productId, 'meta_key' => 'material', 'meta_value' => $request['material']],
            ['product_id' => $productId, 'meta_key' => 'weight', 'meta_value' => $request['weight']],
            ['product_id' => $productId, 'meta_key' => 'created_at', 'meta_value' => $currentTimestamp],
            ['product_id' => $productId, 'meta_key' => 'updated_at', 'meta_value' => $currentTimestamp],
        ];

        // Update the product meta data in the database
        ProductMeta::where('product_id', $productId)->delete(); // Delete existing meta data
        ProductMeta::insert($productmeta); // Insert new meta data
        $assignedTo = $request->input('assigned_to');

        if ($assignedTo === 'all') {
            $technicianIds = $request->input('technician_id', []);
        } elseif ($assignedTo === 'selected') {
            $technicianIds = $request->input('technician_id', []);
        } else {

            $technicianIds = [];
        }

        // Update the product assignment in the products_assigned table
        DB::table('products_assigned')->where('product_id', $productId)->delete();
        foreach ($technicianIds as $technicianId) {
            DB::table('products_assigned')->insert([
                'product_id' => $productId,
                'technician_id' => $technicianId,
            ]);
        }


        // Redirect or respond as needed
        return redirect()->route('product.index')
            ->with('success', 'Product & Material updated successfully');
    }

    public function edit($id)
    {
        // Find the product by ID
        $product = Products::find($id);
        $manufacture = Manufacturer::all();
        $technicians = User::where('role', 'technician')->get();

        // Retrieve selected technicians for the product
        $selectedTechnicians = DB::table('products_assigned')
            ->where('product_id', $id)
            ->pluck('technician_id')
            ->toArray();

        // Find all product categories
        $productCategories = ProductCategory::all();
        $Color = $product->meta()->where('meta_key', 'Color')->value('meta_value') ?? '';
        $Sizes = $product->meta()->where('meta_key', 'Sizes')->value('meta_value') ?? '';
        $material = $product->meta()->where('meta_key', 'material')->value('meta_value') ?? '';
        $weight = $product->meta()->where('meta_key', 'weight')->value('meta_value') ?? '';

        // Pass both the product, product categories, and technicians to the view
        return view('product.edit_product', compact('product', 'manufacture', 'productCategories', 'Color', 'Sizes', 'material', 'weight', 'technicians', 'selectedTechnicians'));
    }

    public function destroy($id)
    {
        // Find the product by ID
        $product = Products::find($id);

        // Check if the product exists
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Delete associated meta data
        ProductMeta::where('product_id', $id)->delete();

        // Delete associated product assignment
        DB::table('products_assigned')->where('product_id', $id)->delete();

        // Delete the product image file if it exists
        if ($product->product_image) {
            $imagePath = public_path('product_image') . '/' . $product->product_image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Delete the product
        $product->delete();
        // Redirect or respond as needed
        return redirect()->route('product.listingproduct')->with('success', 'Product deleted successfully');
    }



    // Controller method
    public function listProductsAjax(Request $request)
    {
        dd(1); // Retrieve query parameters
        $category_id = $request->input('product_category_id');
        $manufacturer_id = $request->input('product_manu_id');

        // Query products based on the selected category and manufacturer
        $products = Products::query();

        if ($category_id) {
            $products->where('product_category_id', $category_id);
        }

        if ($manufacturer_id) {
            $products->where('product_manu_id', $manufacturer_id);
        }

        // Fetch products data
        $products = $products->get();

        // Pass data to the view and return the HTML
        return view('product.listingproduct', compact('products'))->render();
    }

    public function inactive(Request $request, $id)
    {
     
        $product = Products::find($id);

        $product->status = 'Draft';

        $product->update();

       return redirect()->back()->with('success', 'Status Inactive successfully');
    }

    public function active(Request $request, $id)
    {
     
        $product = Products::find($id);

        $product->status = 'Publish';

        $product->update();

       return redirect()->back()->with('success', 'Status Active successfully');
    }


}
