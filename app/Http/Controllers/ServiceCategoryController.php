<?php

namespace App\Http\Controllers;

use id;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Auth;

class ServiceCategoryController extends Controller
{
    public function index()
    {

        $servicecategory = ServiceCategory::where('parent_id',0)->get();
        return view('services.index', ['servicecategory' => $servicecategory]);
    }
    public function getCategoryById($id)
    {
        $category = ServiceCategory::find($id);
        return response()->json($category);
    }


   public function storeServicescategory(Request $request)
    {
        $adminId = Auth::id();

        $request->validate([
            // 'category_name' => 'required|string|max:255',
            // 'category_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Allow empty file
        ]);

        // Check if a file was uploaded
        if ($request->hasFile('category_image')) {
            // Get the file from the request
            $categoryImage = $request->file('category_image');

            // Move the file to the desired public directory
            $imageName = time() . '_' . $categoryImage->getClientOriginalName();
            $categoryImage->move(public_path('images'), $imageName);
        } else {
            // If no image uploaded, set $imageName to null or any default value you prefer
            $imageName = null;
        }

        // Save the record to the database
        ServiceCategory::create([
            'category_name' => $request->input('category_name'),
            'category_image' => $imageName,
            'added_by' => $adminId,
            'updated_by' => $adminId,
        ]);

        return redirect()->route('services.index')->with('success', 'Service category created successfully!');
    }
    public function editServiceCategory($id)
    {
        // Find the service category by ID
        $service = ServiceCategory::find($id);

        // Check if the category exists
        if (!$service) {
            return redirect()->route('services.index')->with('error', 'Service category not found!');
        }

        return view('services.index', ['service' => $service]);
    }

    public function getStoryDetails(Request $request)
    {



        $ServiceCategory = ServiceCategory::where('id', $request->entry_id)->first();



        // $proreport=$proreport->get();
        //   $render_view= view('promotor_sale_summary',compact('proreport'))->render();
        // echo json_encode($proreport);
        // exit();resources\views\frontend\story_details.blade.php
        $render_view = view('services.service_category_render', compact('ServiceCategory'))->render();
        // return response()->json(['proreport'=>$proreport]);
        return response()->json($render_view);
    }







    public function updateServicescategory(Request $request)
{
    $adminId = Auth::id();

    $request->validate([
        // 'category_name' => 'required|string|max:255',
        // 'category_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $serviceCategory = ServiceCategory::find($request->input('category_id'));

    if (!$serviceCategory) {
        return redirect()->route('services.index')->with('error', 'Service category not found!');
    }

    // Update category_name
    $serviceCategory->category_name = $request->input('category_name');

    // Update category_image if a new image is provided or create a new image if none is present
    if ($request->hasFile('category_image')) {
        $categoryImage = $request->file('category_image');
        $imageName = time() . '_' . $categoryImage->getClientOriginalName();
        $categoryImage->move(public_path('images'), $imageName);

        // Delete the old image file if it exists
        if ($serviceCategory->category_image && file_exists(public_path('images/' . $serviceCategory->category_image))) {
            unlink(public_path('images/' . $serviceCategory->category_image));
        }

        $serviceCategory->category_image = $imageName;
    } elseif (!$serviceCategory->category_image) {
        // Create a new image if none is present
        $defaultImageName = 'default_image.jpg'; // Replace with your default image name
        $serviceCategory->category_image = $defaultImageName;
    }

    // Update the updated_by field
    $serviceCategory->updated_by = $adminId;

    // Save the changes to the database
    $serviceCategory->save();

    return redirect()->route('services.index')->with('success', 'Service category updated successfully!');
}


public function deleteServicescategory($id)
{
    $serviceCategory = ServiceCategory::find($id);

    if (!$serviceCategory) {
        return redirect()->route('services.index')->with('error', 'Service category not found!');
    }

    // Check if an image is associated with the service category
    if ($serviceCategory->category_image) {
        // Delete the image file if it exists
        $imagePath = public_path('images/' . $serviceCategory->category_image);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        } else {
            return redirect()->route('services.index')->with('error', 'Image not found for the service category. Only database record deleted.');
        }
    }

    // Delete the category from the database
    $serviceCategory->delete();

    return redirect()->route('services.index')->with('success', 'Service category deleted successfully!');
}






}