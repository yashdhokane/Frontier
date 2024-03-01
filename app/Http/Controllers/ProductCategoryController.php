<?php

namespace App\Http\Controllers;

use App\Models\ProductAssigned;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Products;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends Controller
{
    public function index()
    {

        $productcategory = ProductCategory::all();
        return view('product.index', ['productcategory' => $productcategory]);
    }
    public function getCategoryById($id)
    {
        $category = ProductCategory::find($id);
        return response()->json($category);
    }


    public function storeproductcategory(Request $request)
    {
        $adminId = Auth::id();

        $request->validate([
            // 'category_name' => 'required|string|max:255',
            // 'category_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if a file is present in the request
        if ($request->hasFile('category_image')) {
            // Get the file from the request
            $categoryImage = $request->file('category_image');

            // Move the file to the desired public directory
            $imageName = time() . '_' . $categoryImage->getClientOriginalName();
            $categoryImage->move(public_path('images'), $imageName);
        } else {
            // If no file is present, set $imageName to null
            $imageName = null;
        }

        // Save the record to the database
        ProductCategory::create([
            'category_name' => $request->input('category_name'),
            'category_image' => $imageName,
            'added_by' => $adminId,
            'updated_by' => $adminId,
        ]);

        return redirect()->route('product.index')->with('success', 'Products & Materials created successfully!');
    }

    public function editproductCategory($id)
    {
        // Find the service category by ID
        $service = ProductCategory::find($id);

        // Check if the category exists
        if (!$service) {
            return redirect()->route('product.index')->with('error', 'Service category not found!');
        }

        return view('product.index', ['service' => $service]);
    }

    public function editproduct(Request $request)
    {



        $productCategory = ProductCategory::where('id', $request->entry_id)->first();



        // $proreport=$proreport->get();
        //   $render_view= view('promotor_sale_summary',compact('proreport'))->render();
        // echo json_encode($proreport);
        // exit();resources\views\frontend\story_details.blade.php
        $render_view = view('product.product_category_render', compact('productCategory'))->render();
        // return response()->json(['proreport'=>$proreport]);
        return response()->json($render_view);
    }




    public function updateproductcategory(Request $request)
    {
        $adminId = Auth::id();

        $request->validate([
            // 'category_name' => 'required|string|max:255',
            // 'category_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $productCategory = ProductCategory::find($request->input('category_id'));

        if (!$productCategory) {
            return redirect()->route('product.index')->with('error', 'Service category not found!');
        }

        // Update category_name
        $productCategory->category_name = $request->input('category_name');

        // Update category_image if a new image is provided
        if ($request->hasFile('category_image')) {
            $categoryImage = $request->file('category_image');
            $imageName = time() . '_' . $categoryImage->getClientOriginalName();
            $categoryImage->move(public_path('images'), $imageName);

            // Delete the old image file if it exists and is not a directory
            $oldImagePath = public_path('images/' . $productCategory->category_image);

            if ($productCategory->category_image && file_exists($oldImagePath) && !is_dir($oldImagePath)) {
                unlink($oldImagePath);
            }

            $productCategory->category_image = $imageName;
        }

        // Update the updated_by field
        $productCategory->updated_by = $adminId;

        // Save the changes to the database
        $productCategory->save();

        return redirect()->route('product.index')->with('success', 'Products & Materials updated successfully!');
    }
    public function deleteproductcategory($id)
    {
        $productCategory = ProductCategory::find($id);

        if (!$productCategory) {
            return redirect()->route('product.index')->with('error', 'Products & Materials not found!');
        }

        // Check if the image file exists and delete it
        if (!empty($productCategory->category_image) && file_exists(public_path('images/' . $productCategory->category_image))) {
            unlink(public_path('images/' . $productCategory->category_image));
        }

        // Delete the category from the database
        $productCategory->delete();

        return redirect()->route('product.index')->with('success', 'Products & Materials deleted successfully!');
    }

    public function assign_product(Request $request)
    {
        $technician = User::where('role', 'technician')->get();

        $product = Products::all();

        $assign = ProductAssigned::with('Technician')->get();

        return view('product.assign_product', compact('technician', 'product', 'assign'));
    }

    public function store_assign_product(Request $request)
    {
        // Retrieve the product IDs and technician IDs from the request
        $productIds = $request->input('product_id');
        $technicianIds = $request->input('technician_id');

        // Iterate through each product ID
        foreach ($productIds as $productId) {
            // Iterate through each technician ID
            foreach ($technicianIds as $technicianId) {
                // Create a new database entry for the combination of product ID and technician ID
                $assign = new ProductAssigned();
                $assign->product_id = $productId;
                $assign->technician_id = $technicianId;
                $assign->save();
            }
        }

        // Optionally, you can redirect the user back or return a response
        return redirect()->back()->with('success', 'Assignments stored successfully.');
    }
}
