<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodIndexRequest;
use App\Http\Requests\StoreFoodRequest;
use App\Http\Requests\UpdateFoodRequest;
use App\Models\Food;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class FoodController extends Controller
{
    /**
     * List foods with search, category filter, sorting and pagination.
     */
    public function index(FoodIndexRequest $request): View
    {
        $filters = $request->filters();

        $foods = Food::filter($filters)->paginate(9)->withQueryString();

        return view('foods.index', [
            'foods' => $foods,
            'categories' => Food::CATEGORIES,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new food.
     */
    public function create(): View
    {
        return view('foods.create', [
            'categories' => Food::CATEGORIES,
        ]);
    }

    /**
     * Persist a newly created food.
     */
    public function store(StoreFoodRequest $request): RedirectResponse
    {
        $food = Food::create($request->validated());

        return redirect()
            ->route('foods.show', $food)
            ->with('status', 'Makanan berhasil ditambahkan.');
    }

    /**
     * Display the specified food.
     */
    public function show(Food $food): View
    {
        return view('foods.show', [
            'food' => $food,
        ]);
    }

    /**
     * Show the form for editing the specified food.
     */
    public function edit(Food $food): View
    {
        return view('foods.edit', [
            'food' => $food,
            'categories' => Food::CATEGORIES,
        ]);
    }

    /**
     * Update the specified food in storage.
     */
    public function update(UpdateFoodRequest $request, Food $food): RedirectResponse
    {
        $food->update($request->validated());

        return redirect()
            ->route('foods.show', $food)
            ->with('status', 'Makanan berhasil diperbarui.');
    }

    /**
     * Remove the specified food from storage.
     */
    public function destroy(Food $food): RedirectResponse
    {
        try {
            $food->delete();
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus makanan: '.$e->getMessage(), ['food_id' => $food->id]);

            return redirect()
                ->route('foods.index')
                ->with('error', 'Makanan gagal dihapus. Coba lagi.');
        }

        return redirect()
            ->route('foods.index')
            ->with('status', 'Makanan berhasil dihapus.');
    }
}
