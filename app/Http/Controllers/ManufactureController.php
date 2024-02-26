<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManufactureController extends Controller
{
    public function mnufacturelist()
    {
        $manufacture = Manufacturer::all();
        return view('manufacturer.index', compact('manufacture'));
    }

    public function create()
    {

        return view('manufacturer.create');
    }
    public function edit($id)
    {

        $manufacture = Manufacturer::find($id);
        return view('manufacturer.edit', compact('manufacture'));
    }


    public function store(Request $request)
    {

        $adminId = Auth::id();

        $request->validate([

        ]);


        if ($request->hasFile('manufacture_image')) {

            $categoryImage = $request->file('manufacture_image');


            $imageName = time() . '_' . $categoryImage->getClientOriginalName();
            $categoryImage->move(public_path('images'), $imageName);
        } else {

            $imageName = null;
        }


        Manufacturer::create([
            'manufacturer_name' => $request->input('manufacturer_name'),
            'manufacturer_description' => $request->input('manufacturer_description'),

            'manufacturer_image' => $imageName,
            'added_by' => $adminId,
            'last_updated_by' => $adminId,
        ]);
        // dd(1);
        return redirect()->route('manufacturer.index')->with('success', 'Manufacturer created successfully!');
    }



    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            // Add validation rules for other fields if needed
        ]);

        $manufacturer = Manufacturer::findOrFail($id);
        $existingImageName = $manufacturer->manufacturer_image;

        // Update manufacturer_image if a new image is provided
        if ($request->hasFile('manufacturer_image')) {
            $newImage = $request->file('manufacturer_image');

            // Check if the file is a valid image
            if ($newImage->isValid() && in_array($newImage->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                $newImageName = time() . '_' . $newImage->getClientOriginalName();
                $newImage->move(public_path('images'), $newImageName);

                // Delete the old image file if it exists and is not a directory
                $oldImagePath = public_path('images/' . $existingImageName);

                if ($existingImageName && file_exists($oldImagePath) && !is_dir($oldImagePath)) {
                    unlink($oldImagePath);
                }

                $manufacturer->manufacturer_image = $newImageName;
            } else {
                // Handle invalid image file
                return redirect()->back()->with('error', 'Invalid image file. Please upload a valid image.');
            }
        }

        // Update other fields
        $manufacturer->update([
            'manufacturer_name' => $request->input('manufacturer_name'),
            'manufacturer_description' => $request->input('manufacturer_description'),
            'last_updated_by' => Auth::id(),
        ]);

        return redirect()->route('manufacturer.index')->with('success', 'Manufacturer updated successfully!');
    }


    public function destroy($id)
    {
        $manufacture = Manufacturer::find($id);

        if (!$manufacture) {
            return redirect()->route('manufacturer.index')->with('error', 'Manufacturer not found!');
        }

        // Delete the associated image (if it exists)
        if ($manufacture->manufacturer_image) {
            unlink(public_path('images/' . $manufacture->manufacturer_image));
        }

        // Delete the manufacturer record from the database
        $manufacture->delete();

        return redirect()->route('manufacturer.index')->with('success', 'Manufacturer deleted successfully!');
    }


}
