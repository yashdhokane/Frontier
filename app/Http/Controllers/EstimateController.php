<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use App\Models\Products;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EstimateTemplateCategory;
use App\Models\EstimateTemplatesServices;
use App\Models\EstimateTemplatesProductItems;

class EstimateController extends Controller
{
    public function listingestimate($category_id = null)
    {
        // If $category_id is provided, filter estimates by category ID
        $query = Estimate::orderBy('created_at', 'desc');
        if ($category_id) {
            $query->where('template_category_id', $category_id);
        }

        // Fetch estimates based on the query
        $estimate = $query->get();

        // Pass the fetched estimates and the category_id to the view
        return view('estimate.estimate_listing', compact('estimate', 'category_id'));
    }


    public function createestimate()
    {
        $category = EstimateTemplateCategory::all();
        $service = Services::all();
        $products = Products::all();
        $estimate = EstimateTemplatesServices::all();
        return view('estimate.create_estimate', compact('estimate', 'products', 'category', 'service'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $adminId = Auth::id();
        $estimate = Estimate::create([
            'template_name' => $request->input('template_name'),
            'template_description' => $request->input('template_description'),
            'estimate_subtotal' => $request->input('estimate_subtotal'),
            // 'estimate_tax' => $request->input('estimate_tax'),

            'estimate_discount' => $request->input('estimate_discount'),
            'estimate_total' => $request->input('estimate_total'),
            'template_category_id' => $request->input('template_category_id'),
            'added_by' => $adminId,
            'last_updated_by' => $adminId,
        ]);
        $estimate->save();
        // Create a new Service record
        $service = EstimateTemplatesServices::create([
            'template_id' => $estimate->template_id,
            'service_id' => $request->input('service_id'),
            'description_service' => $request->input('service_description'),
            'quantity_service' => $request->input('service_quantity'),
            'cost_service' => $request->input('service_cost'),
            'price_service' => $request->input('service_price'),
            'discount_service'=>$request->input('service_discount'),
            'tax_service'=>$request->input('service_tax'),
        ]);
        $service->save();


        // Create a new Product record
        $product = EstimateTemplatesProductItems::create([
            'estimate_id' => $estimate->template_id,
            'product_id' => $request->input('product_id'),
            'description_product' => $request->input('product_description'),
            'quantity_product' => $request->input('product_quantity'),
            'cost_product' => $request->input('product_cost'),
            'price_product' => $request->input('product_price'),
            'tax' => $request->input('tax'),
            'discount' => $request->input('discount'),

        ]);
        $product->save();

        // dd(1);
        // Redirect to the index route with a success message
        return redirect()->route('estimate.listingestimate', ['category_id' => $request->input('template_category_id')])
        ->with('success', 'Estimate Template created successfully!');
    }


    public function edit($template_id)
    {
        $service = Services::all();
        $estimate = Estimate::findOrFail($template_id);
        $products = Products::all();

        $serviceCategories = EstimateTemplateCategory::all();
        $estimateService = EstimateTemplatesServices::where('template_id', $template_id)->first();
        $estimateProduct = EstimateTemplatesProductItems::where('estimate_id', $template_id)->first();

        return view('estimate.edit_estimate', compact('estimate', 'service', 'serviceCategories', 'products', 'estimateService', 'estimateProduct'));
    }




  public function update(Request $request, $id)
{
    // dd($request->all());
    $request->validate([
        // Add your validation rules here
    ]);

    $adminId = Auth::id();

    // Find the existing Estimate instance by ID
    $estimate = Estimate::findOrFail($id);

    // Update the existing Estimate with values from the form
    $estimate->update([
        'template_name' => $request->input('template_name'),
        'template_description' => $request->input('template_description'),
        'estimate_subtotal' => $request->input('estimate_subtotal'),
        'estimate_discount' => $request->input('estimate_discount'),
        'estimate_total' => $request->input('estimate_total'),
        'template_category_id' => $request->input('template_category_id'),
        'last_updated_by' => $adminId,
    ]);

    // Find the existing EstimateTemplatesServices instance by template_id
    $estimateService = EstimateTemplatesServices::where('template_id', $id)->first();

    // Update the existing EstimateTemplatesServices with values from the form
    $estimateService->update([
        'service_id' => $request->input('service_id'),
        'description_service' => $request->input('service_description'),
        'quantity_service' => $request->input('service_quantity'),
        'cost_service' => $request->input('service_cost'),
        'price_service' => $request->input('service_price'),
        'discount_service' => $request->input('service_discount'),
        'tax_service' => $request->input('service_tax'),
    ]);

    // Find the existing EstimateTemplatesProductItems instance by estimate_id
    $estimateProduct = EstimateTemplatesProductItems::where('estimate_id', $id)->first();

    // Update the existing EstimateTemplatesProductItems with values from the form
    $estimateProduct->update([
        'product_id' => $request->input('product_id'),
        'description_product' => $request->input('product_description'),
        'quantity_product' => $request->input('product_quantity'),
        'cost_product' => $request->input('product_cost'),
        'price_product' => $request->input('product_price'),
        'discount' => $request->input('discount'),
        'tax' => $request->input('tax'),
    ]);

    // Redirect to the index route with a success message
    return redirect()->route('estimate.listingestimate', ['category_id' => $request->input('template_category_id')])
        ->with('success', 'Estimate Template updated successfully!');
}

    public function destroy($template_id)
    {
        // Find the Estimate instance by ID
        $estimate = Estimate::find($template_id);

        if (!$estimate) {
            // Handle not found case, redirect or show an error message
            return redirect()->back()->with('error', 'Estimate Template not found.');
        }

        // Delete related EstimateTemplatesServices and EstimateTemplatesProductItems
        $estimate->services()->delete();
        $estimate->products()->delete();

        // Delete the Estimate
        $estimate->delete();

        // Redirect to the index route with a success message
        return redirect()->back()->with('success', 'Estimate Template deleted successfully.');
    }

    public function getServiceDetails($id)
    {
        $service = Services::find($id);

        $totalCost = $service->service_cost * $service->service_quantity;
        return response()->json([
            'service_cost' => $service->service_cost,
            'service_quantity' => $service->service_quantity,
            'unitcost' => $totalCost,
            'service_tax' => $service->service_tax,
            'service_discount' => $service->service_discount,

        ]);
    }
    // public function getProductDetails($id)
    // {
    //     // dd(1);
    //     $product = Products::find($id);

    //     return response()->json([
    //         'base_price' => $product->base_price,
    //         'stock' => $product->stock,
    //     ]);
    // }
    public function getProductDetails($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }

        // Calculate a third variable (e.g., total_cost)
        $totalCost = $product->base_price * $product->stock;

        return response()->json([
            'base_price' => $product->base_price,
            'stock' => $product->stock,
            'unitcostone' => $totalCost,
            'discount' => $product->discount,

            'tax' => $product->tax,

        ]);
    }




}