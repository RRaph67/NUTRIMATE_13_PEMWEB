<?php

namespace App\Http\Requests;

use App\Models\Food;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Shared by the Blade catalogue and the JSON API so both accept exactly the
 * same search, filter and sort parameters.
 */
class FoodIndexRequest extends FormRequest
{
    /**
     * Normalize empty query strings to null so `filled()` behaves predictably.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'q' => filled($this->input('q')) ? trim((string) $this->input('q')) : null,
            'category' => filled($this->input('category')) ? (string) $this->input('category') : null,
            'sort' => filled($this->input('sort')) ? (string) $this->input('sort') : null,
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', Rule::in(Food::CATEGORIES)],
            'sort' => ['nullable', 'string', Rule::in(array_keys(Food::SORTS))],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Custom messages in Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'q.string' => 'Kata kunci pencarian harus berupa teks.',
            'q.max' => 'Kata kunci pencarian maksimal 100 karakter.',
            'category.in' => 'Kategori harus salah satu dari: '.implode(', ', Food::CATEGORIES).'.',
            'sort.in' => 'Urutan harus salah satu dari: '.implode(', ', array_keys(Food::SORTS)).'.',
        ];
    }

    /**
     * The validated filters, with defaults ready for the view / response.
     *
     * @return array{q: string, category: string, sort: string}
     */
    public function filters(): array
    {
        $validated = $this->validated();

        return [
            'q' => (string) ($validated['q'] ?? ''),
            'category' => (string) ($validated['category'] ?? ''),
            'sort' => (string) ($validated['sort'] ?? ''),
        ];
    }
}
