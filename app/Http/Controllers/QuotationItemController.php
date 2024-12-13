<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\CustomerDetail;
use App\Models\Quotation;
use App\Models\Company;
use App\Models\CompanyMaintenance;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\QuotationItem;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationItemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'iQuoItemQuofk' => 'required|exists:quotations,iQuoPk',
            'cQuoItemProductCode' => 'required|string|max:255',
            'cQuoItemDescription' => 'required|string|max:255',
            'yQuoItemPriceUnit' => 'required|numeric|min:0',
            'iQuoItemQuantity' => 'required|integer|min:1',
            'yQuoItemTotal' => 'required|numeric|min:0',
        ]);

        // Fetch inventory details
        $inventory = \DB::table('inventory_master')
            ->where('product_code', $request->cQuoItemProductCode)
            ->first();

        if (!$inventory) {
            return response()->json(['message' => 'Product not found in inventory.'], 404);
        }

        if ($inventory->quantity_in - $inventory->quantity_out < $request->iQuoItemQuantity) {
            return response()->json(['message' => 'Quantity available only ' . ($inventory->quantity_in - $inventory->quantity_out)], 422);
        }

        // Create quotation item
        $quotationItem = QuotationItem::create($request->all());

        // Update inventory `quantity_out`
        \DB::table('inventory_master')
            ->where('product_code', $request->cQuoItemProductCode)
            ->increment('quantity_out', $request->iQuoItemQuantity);

        return response()->json(['message' => 'Quotation item added successfully.', 'item' => $quotationItem], 201);
    }


    public function destroy($id)
    {
        $item = QuotationItem::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Quotation item deleted successfully.'], 200);
    }
}
