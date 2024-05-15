<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EstimateTemplateCategory;

class EstimateCategoryController extends Controller
{
    public function index()
    {
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 40;
        
        $permissionCheck =  app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $estimatecategory = EstimateTemplateCategory::all();
        return view('estimate.index', ['estimatecategory' => $estimatecategory]);
    }
    public function getCategoryById($id)
    {
        $category = EstimateTemplateCategory::find($id);
        return response()->json($category);
    }


    public function storeestimatecategory(Request $request)
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
        EstimateTemplateCategory::create([
            'category_name' => $request->input('category_name'),
            'category_image' => $imageName,
            'added_by' => $adminId,
            'updated_by' => $adminId,
        ]);

        return redirect()->route('estimate.index')->with('success', 'Estimate category created successfully!');
    }


    public function editestimatecategory($id)
    {
        // Find the service category by ID
        $service = EstimateTemplateCategory::find($id);

        // Check if the category exists
        if (!$service) {
            return redirect()->route('product.index')->with('error', 'Estimate category not found!');
        }

        return view('product.index', ['service' => $service]);
    }

    public function estimateDetails(Request $request)
    {

        // dd(1);

        $estimateCategory = EstimateTemplateCategory::where('id', $request->entry_id)->first();



        // $proreport=$proreport->get();
        //   $render_view= view('promotor_sale_summary',compact('proreport'))->render();
        // echo json_encode($proreport);
        // exit();resources\views\frontend\story_details.blade.php
        $render_view = view('estimate.estimate_category_render', compact('estimateCategory'))->render();
        // return response()->json(['proreport'=>$proreport]);
        return response()->json($render_view);
    }



    public function updateestimatecategory(Request $request)
    {
        $adminId = Auth::id();

        $request->validate([
            // 'category_name' => 'required|string|max:255',
            // 'category_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $estimateCategory = EstimateTemplateCategory::find($request->input('category_id'));

        if (!$estimateCategory) {
            return redirect()->route('estimate.index')->with('error', 'Estimate category not found!');
        }

        // Update category_name
        $estimateCategory->category_name = $request->input('category_name');

        // Update category_image if a new image is provided
        if ($request->hasFile('category_image')) {
            $categoryImage = $request->file('category_image');
            $imageName = time() . '_' . $categoryImage->getClientOriginalName();
            $categoryImage->move(public_path('images'), $imageName);

            // Delete the old image file if it exists and is not a directory
            $oldImagePath = public_path('images/' . $estimateCategory->category_image);

            if ($estimateCategory->category_image && file_exists($oldImagePath) && !is_dir($oldImagePath)) {
                unlink($oldImagePath);
            }

            $estimateCategory->category_image = $imageName;
        }

        // Update the updated_by field
        $estimateCategory->last_updated_by = $adminId;
        $estimateCategory->added_by = $adminId;
        // Save the changes to the database
        $estimateCategory->save();

        return redirect()->route('estimate.index')->with('success', 'Estimate category updated successfully!');
    }



    public function deleteestimatecategory($id)
{
    $estimateCategory = EstimateTemplateCategory::find($id);

    if (!$estimateCategory) {
        return redirect()->route('estimate.index')->with('error', 'Estimate category not found!');
    }

    $imagePath = public_path('images/' . $estimateCategory->category_image);

    // Check if the image exists before attempting to delete it
    if ($estimateCategory->category_image && file_exists($imagePath) && is_file($imagePath)) {
        unlink($imagePath);
    }

    $estimateCategory->delete();

    return redirect()->route('estimate.index')->with('success', 'Estimate category deleted successfully!');
}


}