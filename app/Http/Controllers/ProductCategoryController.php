<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
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

        
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 37;
        
        $permissionCheck =  app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $products = Products::orderBy('created_at', 'desc')->get();

        $manufacture = Manufacturer::all();
        $product = ProductCategory::get();

        return view('product.index', compact('products', 'manufacture', 'product'));
    }
    public function getCategoryById($id)
    {
        $category = ProductCategory::find($id);
        return response()->json($category);
    }


    public function storeproductcategory(Request $request)
    {
        $adminId = Auth::id();

        $cat = new ProductCategory();

        $cat->category_name = $request->input('category_name');
        $cat->added_by = $adminId;
        $cat->updated_by = $adminId;

        $cat->save();

        if ($request->hasFile('category_image')) {
            // Generate a unique directory name based on user ID and timestamp
            $directoryName = $cat->id;

            // Construct the full path for the directory
            $directoryPath = public_path('images/parts/' . $directoryName);

            // Ensure the directory exists; if not, create it
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }

            // Move the uploaded image to the unique directory
            $image = $request->file('category_image');
            $imageName = $image->getClientOriginalName(); // Or generate a unique name if needed
            $image->move($directoryPath, $imageName);

            // Save the image path in the user record
            $cat->category_image =  $imageName;
            $cat->save();
        }

        return redirect()->back()->with('success', 'Products & Materials created successfully!');
    }

    public function editproductCategory($id)
    {
        // Find the service category by ID
        $service = ProductCategory::find($id);

        // Check if the category exists
        if (!$service) {
            return view('404');
        }

        return view('product.index', ['service' => $service]);
    }

    public function editproduct(Request $request)
    {
        $productCategory = ProductCategory::where('id', $request->entry_id)->first();

        $render_view = view('product.product_category_render', compact('productCategory'))->render();

        return response()->json($render_view);
    }




    public function updateproductcategory(Request $request)
    {
        $adminId = Auth::id();

        $request->validate([
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

        return redirect()->back()->with('success', 'Products & Materials updated successfully!');
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
        
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 38;
        
        $permissionCheck =  app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $technician = User::where('role', 'technician')->get();

        $product = Products::all();

        $assign = ProductAssigned::with('Technician', 'Product')->get();

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
                $assign->quantity = $request->quantity;
                $assign->save();
            }
        }

        // Optionally, you can redirect the user back or return a response
        return redirect()->back()->with('success', 'Assignments stored successfully.');
    }
}
