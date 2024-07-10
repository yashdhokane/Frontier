<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\User;
use App\Models\ToolMeta;
use App\Models\ToolAssign;

use App\Models\Manufacturer;
use App\Models\ToolCategory;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ToolController extends Controller
{
    public function index()
    {


        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 37;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $products = Tool::with(['toolassign.Technician'])->orderBy('created_at', 'desc')->get();
        $manufacture = Manufacturer::all();
        $technician = User::where('role', 'technician')->where('status', 'active')->get();

        $product = ProductCategory::get();

        return view('tool.index', compact('products', 'technician', 'manufacture', 'product'));
    }

    public function createproduct()
    {
        $manufacture = Manufacturer::all();
        $product = ProductCategory::get();
        $technicians = User::where('role', 'technician')->where('status', 'active')->get();
        return view('tool.create_tool', compact('product', 'manufacture', 'technicians'));
    }
    public function store(Request $request)
    {
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
        $product = new Tool([
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

        // Insert the product meta data into the database
        ToolMeta::insert($productmeta);

        // Handle technician assignments
        $assignedTo = $request->input('assigned_to');
        if ($assignedTo === 'all' || $assignedTo === 'selected') {
            $technicianIds = $request->input('technician_id', []);
            foreach ($technicianIds as $technicianId) {
                DB::table('tool_assigned')->insert([
                    'product_id' => $productId,
                    'technician_id' => $technicianId,
                ]);
            }
        }

        // Redirect or respond as needed
        return redirect()->route('tool.index')->with('success', 'Tool created successfully');
    }

    // public function update(Request $request, $id)
    // {
    //     dd($request->all());
    //     $adminId = Auth::id();

    //     // Find the product by ID
    //     $product = Tool::find($id);

    //     // Handle image upload
    //     if ($request->hasFile('product_image')) {
    //         $categoryImage = $request->file('product_image');
    //         $imageName = time() . '_' . $categoryImage->getClientOriginalName();
    //         $categoryImage->move(public_path('product_image'), $imageName);

    //         // Delete the previous image if it exists
    //         if ($product->product_image) {
    //             $imagePath = public_path('product_image') . '/' . $product->product_image;
    //             if (file_exists($imagePath)) {
    //                 unlink($imagePath);
    //             }
    //         }

    //         // Update the product with the new image
    //         $product->update([
    //             'product_image' => $imageName,
    //         ]);
    //     }

    //     // Update other product fields
    //     $product->update([
    //         'product_name' => $request->input('product_name'),
    //         'product_short' => $request->input('product_short'),
    //         'product_manu_id' => $request->input('product_manu_id'),


    //         'product_category_id' => $request->input('product_category_id'),
    //         'status' => $request->input('status'),
    //         'base_price' => $request->input('base_price'),
    //         'discount' => $request->input('discount'),
    //         'total' => $request->input('total'),
    //         'product_code' => $request->input('product_code'),
    //         'stock' => $request->input('stock'),
    //         'product_description' => $request->input('product_description'),
    //         'updated_by' => $adminId,
    //         'created_by' => $adminId,
    //     ]);

    //     // Update associated product meta data
    //     $currentTimestamp = now();
    //     $productId = $product->product_id;

    //     $productmeta = [
    //         ['product_id' => $productId, 'meta_key' => 'Color', 'meta_value' => $request['Color']],
    //         ['product_id' => $productId, 'meta_key' => 'Sizes', 'meta_value' => $request['Sizes']],
    //         ['product_id' => $productId, 'meta_key' => 'material', 'meta_value' => $request['material']],
    //         ['product_id' => $productId, 'meta_key' => 'weight', 'meta_value' => $request['weight']],
    //         ['product_id' => $productId, 'meta_key' => 'created_at', 'meta_value' => $currentTimestamp],
    //         ['product_id' => $productId, 'meta_key' => 'updated_at', 'meta_value' => $currentTimestamp],
    //     ];

    //     // Update the product meta data in the database
    //     ToolMeta::where('product_id', $productId)->delete(); // Delete existing meta data
    //     ToolMeta::insert($productmeta); // Insert new meta data
    //     $assignedTo = $request->input('assigned_to');

    //     if ($assignedTo === 'all') {
    //         $technicianIds = $request->input('technician_id', []);
    //     } elseif ($assignedTo === 'selected') {
    //         $technicianIds = $request->input('technician_id', []);
    //     } else {

    //         $technicianIds = [];
    //     }

    //     // Update the product assignment in the products_assigned table
    //     DB::table('tool_assigned')->where('product_id', $productId)->delete();
    //     foreach ($technicianIds as $technicianId) {
    //         DB::table('tool_assigned')->insert([
    //             'product_id' => $productId,
    //             'technician_id' => $technicianId,
    //         ]);
    //     }


    //     // Redirect or respond as needed
    //     return redirect()->route('tool.index')
    //         ->with('success', 'Tool updated successfully');
    // }

    public function update(Request $request, $id)
    {
        //dd($request->all());
        $adminId = Auth::id();

        // Find the product by ID
        $product = Tool::find($id);

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

        // Update other product fields based on request inputs
        $fillableFields = [
            'product_name',
            'product_short',
            'product_manu_id',
            'product_category_id',
            'status',
            'base_price',
            'discount',
            'total',
            'product_code',
            'stock',
            'product_description'
        ];

        $updateData = [];
        foreach ($fillableFields as $field) {
            if ($request->has($field)) {
                $updateData[$field] = $request->input($field);
            }
        }

        // Add updated_by and created_by fields
        $updateData['updated_by'] = $adminId;
        $updateData['created_by'] = $adminId;

        // Update the product
        $product->update($updateData);
        $product->update([
            'assigned_to' => $request->input('assigned_to'), // Assuming assigned_to is directly from the request
        ]);
        // Update associated product meta data
        $currentTimestamp = now();
        $productId = $product->product_id;

        $productmeta = [
            ['product_id' => $productId, 'meta_key' => 'Color', 'meta_value' => $request['Color'] ?? ''],
            ['product_id' => $productId, 'meta_key' => 'Sizes', 'meta_value' => $request['Sizes'] ?? ''],
            ['product_id' => $productId, 'meta_key' => 'material', 'meta_value' => $request['material'] ?? ''],
            ['product_id' => $productId, 'meta_key' => 'weight', 'meta_value' => $request['weight'] ?? ''],
            ['product_id' => $productId, 'meta_key' => 'created_at', 'meta_value' => $currentTimestamp],
            ['product_id' => $productId, 'meta_key' => 'updated_at', 'meta_value' => $currentTimestamp],
        ];

        // Update the product meta data in the database
        ToolMeta::where('product_id', $productId)->delete(); // Delete existing meta data
        ToolMeta::insert($productmeta); // Insert new meta data
        $assignedTo = $request->input('assigned_to');

        if ($assignedTo === 'all') {
            $technicianIds = $request->input('technician_id', []);
        } elseif ($assignedTo === 'selected') {
            $technicianIds = $request->input('technician_id', []);
        } else {

            $technicianIds = [];
        }

        // Update the product assignment in the products_assigned table
        DB::table('tool_assigned')->where('product_id', $productId)->delete();
        foreach ($technicianIds as $technicianId) {
            DB::table('tool_assigned')->insert([
                'product_id' => $productId,
                'technician_id' => $technicianId,
            ]);
        }



        // Redirect or respond as needed
        return redirect()->route('tool.index')->with('success', 'Tool updated successfully');
    }


    public function edit($id)
    {
        // Find the product by ID
        $product = Tool::find($id);

        if (!$product) {
            return view('404');
        }

        $manufacture = Manufacturer::all();
        $technicians = User::where('role', 'technician')->where('status', 'active')->get();

        // Retrieve selected technicians for the product
        $selectedTechnicians = DB::table('tool_assigned')
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
        return view('tool.edit_tool', compact('product', 'manufacture', 'productCategories', 'Color', 'Sizes', 'material', 'weight', 'technicians', 'selectedTechnicians'));
    }

    public function destroy($id)
    {
        // Find the product by ID
        $product = Tool::find($id);

        // Check if the product exists
        if (!$product) {
            return redirect()->back()->with('error', 'Tool not found.');
        }

        // Delete associated meta data
        ToolMeta::where('product_id', $id)->delete();

        // Delete associated product assignment
        DB::table('tool_assigned')->where('product_id', $id)->delete();

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
        return redirect()->route('tool.index')->with('success', 'Tool deleted successfully');
    }



    // Controller method
    public function listProductsAjax(Request $request)
    {
        $category_id = $request->input('product_category_id');
        $manufacturer_id = $request->input('product_manu_id');

        // Query products based on the selected category and manufacturer
        $products = Tool::query();

        if ($category_id) {
            $products->where('product_category_id', $category_id);
        }

        if ($manufacturer_id) {
            $products->where('product_manu_id', $manufacturer_id);
        }

        // Fetch products data
        $products = $products->get();

        // Pass data to the view and return the HTML
        return view('tool.index', compact('products'))->render();
    }

    public function inactive(Request $request, $id)
    {

        $product = Tool::find($id);

        $product->status = 'Draft';

        $product->update();

        return redirect()->back()->with('success', 'Status Inactive successfully');
    }

    public function active(Request $request, $id)
    {

        $product = Tool::find($id);

        $product->status = 'Publish';

        $product->update();

        return redirect()->back()->with('success', 'Status Active successfully');
    }




    public function assign_product(Request $request)
    {

        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 38;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $technician = User::where('role', 'technician')->where('status', 'active')->get();

        $product = Tool::all();

        $assign = ToolAssign::with('Technician', 'Product')->get();

        return view('tool.assign_tool', compact('technician', 'product', 'assign'));
    }

    public function store_assign_tool(Request $request)
    {
        //dd($request->all());
        // Retrieve the product IDs and technician IDs from the request
        $productIds = $request->input('product_id');
        $technicianIds = $request->input('technician_id');

        // Iterate through each product ID
        foreach ($productIds as $productId) {
            // Iterate through each technician ID
            foreach ($technicianIds as $technicianId) {
                // Create a new database entry for the combination of product ID and technician ID
                $assign = new ToolAssign();
                $assign->product_id = $productId;
                $assign->technician_id = $technicianId;
                $assign->quantity = $request->quantity;
                $assign->save();
            }
        }

        // Optionally, you can redirect the user back or return a response
        return redirect()->back()->with('success', 'Assignments stored successfully.');
    }
}
