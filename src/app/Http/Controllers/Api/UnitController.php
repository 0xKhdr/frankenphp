<?php

namespace App\Http\Controllers\Api;

use App\Actions\Units\ListUnitsAction;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * Get all units
     */
    public function index(ListUnitsAction $listUnitsAction): JsonResponse
    {
        $units = $listUnitsAction->execute();

        return response()->json([
            'success' => true,
            'message' => 'Units retrieved successfully',
            'total' => count($units),
            'data' => $units,
        ]);
    }
    //
    //    /**
    //     * Get a single unit by ID
    //     */
    //    public function show(string $id): JsonResponse
    //    {
    //        $unit = Unit::find($id);
    //
    //        if (!$unit) {
    //            return response()->json([
    //                'success' => false,
    //                'message' => 'Unit not found',
    //            ], 404);
    //        }
    //
    //        return response()->json([
    //            'success' => true,
    //            'data' => $unit,
    //        ]);
    //    }
    //
    //    /**
    //     * Create a new unit
    //     */
    //    public function store(Request $request): JsonResponse
    //    {
    //        $validator = Validator::make($request->all(), [
    //            'name' => 'required|string|max:255',
    //            'symbol' => 'nullable|string|max:50',
    //            'description' => 'nullable|string',
    //            'type' => 'required|string|max:100',
    //            'si_unit' => 'nullable|string|max:50',
    //            'conversion_factor' => 'required|numeric',
    //            'dimension' => 'nullable|string|max:50',
    //            'system' => 'required|string|max:50',
    //            'is_base_unit' => 'boolean',
    //            'category' => 'nullable|string|max:100',
    //            'unit_system' => 'nullable|string|max:100',
    //            'unit_group' => 'nullable|string|max:100',
    //            'is_active' => 'boolean',
    //            'sort_order' => 'integer|min:0',
    //            'metadata' => 'nullable|array',
    //        ]);
    //
    //        if ($validator->fails()) {
    //            return response()->json([
    //                'success' => false,
    //                'message' => 'Validation error',
    //                'errors' => $validator->errors(),
    //            ], 422);
    //        }
    //
    //        $unit = Unit::create($validator->validated());
    //
    //        // Clear the cache when a new unit is added
    //        Cache::forget($this->cacheKey);
    //
    //        return response()->json([
    //            'success' => true,
    //            'data' => $unit,
    //            'message' => 'Unit created successfully',
    //        ], 201);
    //    }
    //
    //    /**
    //     * Update an existing unit
    //     */
    //    public function update(Request $request, string $id): JsonResponse
    //    {
    //        $unit = Unit::find($id);
    //
    //        if (!$unit) {
    //            return response()->json([
    //                'success' => false,
    //                'message' => 'Unit not found',
    //            ], 404);
    //        }
    //
    //        $validator = Validator::make($request->all(), [
    //            'name' => 'sometimes|required|string|max:255',
    //            'symbol' => 'nullable|string|max:50',
    //            'description' => 'nullable|string',
    //            'type' => 'sometimes|required|string|max:100',
    //            'si_unit' => 'nullable|string|max:50',
    //            'conversion_factor' => 'sometimes|required|numeric',
    //            'dimension' => 'nullable|string|max:50',
    //            'system' => 'sometimes|required|string|max:50',
    //            'is_base_unit' => 'boolean',
    //            'category' => 'nullable|string|max:100',
    //            'unit_system' => 'nullable|string|max:100',
    //            'unit_group' => 'nullable|string|max:100',
    //            'is_active' => 'boolean',
    //            'sort_order' => 'integer|min:0',
    //            'metadata' => 'nullable|array',
    //        ]);
    //
    //        if ($validator->fails()) {
    //            return response()->json([
    //                'success' => false,
    //                'message' => 'Validation error',
    //                'errors' => $validator->errors(),
    //            ], 422);
    //        }
    //
    //        $unit->update($validator->validated());
    //
    //        // Clear the cache when a unit is updated
    //        Cache::forget($this->cacheKey);
    //
    //        return response()->json([
    //            'success' => true,
    //            'data' => $unit,
    //            'message' => 'Unit updated successfully',
    //        ]);
    //    }
    //
    //    /**
    //     * Delete a unit
    //     */
    //    public function destroy(string $id): JsonResponse
    //    {
    //        $unit = Unit::find($id);
    //
    //        if (!$unit) {
    //            return response()->json([
    //                'success' => false,
    //                'message' => 'Unit not found',
    //            ], 404);
    //        }
    //
    //        $unit->delete();
    //
    //        // Clear the cache when a unit is deleted
    //        Cache::forget($this->cacheKey);
    //
    //        return response()->json([
    //            'success' => true,
    //            'message' => 'Unit deleted successfully',
    //        ]);
    //    }
}
