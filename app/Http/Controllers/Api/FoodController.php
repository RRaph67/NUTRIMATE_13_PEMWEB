<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FoodIndexRequest;
use App\Http\Requests\StoreFoodRequest;
use App\Http\Requests\UpdateFoodRequest;
use App\Http\Resources\FoodResource;
use App\Models\Food;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FoodController extends Controller
{
    /**
     * List foods with search, category filter, sorting and pagination.
     */
    public function index(FoodIndexRequest $request): AnonymousResourceCollection
    {
        $filters = $request->filters();

        $foods = Food::filter($filters)
            ->paginate(15)
            ->withQueryString();

        return FoodResource::collection($foods)->additional([
            'filters' => $filters,
        ]);
    }

    /**
     * Store a newly created food.
     */
    public function store(StoreFoodRequest $request): JsonResponse
    {
        $food = Food::create($request->validated());

        return response()->json([
            'message' => 'Makanan berhasil ditambahkan.',
            'data' => new FoodResource($food),
        ], 201);
    }

    /**
     * Display the specified food.
     */
    public function show(Food $food): JsonResponse
    {
        return response()->json([
            'data' => new FoodResource($food),
        ]);
    }

    /**
     * Update the specified food.
     */
    public function update(UpdateFoodRequest $request, Food $food): JsonResponse
    {
        $food->update($request->validated());

        return response()->json([
            'message' => 'Makanan berhasil diperbarui.',
            'data' => new FoodResource($food->refresh()),
        ]);
    }

    /**
     * Remove the specified food.
     */
    public function destroy(Food $food): JsonResponse
    {
        $food->delete();

        return response()->json([
            'message' => 'Makanan berhasil dihapus.',
        ]);
    }
}
