<?php

namespace App\Http\Requests;

use App\Models\Food;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFoodRequest extends FormRequest
{
    /**
     * Normalize empty strings to null before validating.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'description' => $this->filled('description') ? $this->input('description') : null,
            'image_url' => $this->filled('image_url') ? trim($this->input('image_url')) : null,
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
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'category' => ['required', 'string', Rule::in(Food::CATEGORIES)],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'portion_description' => ['required', 'string', 'min:2', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'calories' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'protein' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'carbs' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'fat' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'image_url' => ['nullable', 'url', 'max:255'],
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
            'name.required' => 'Nama makanan wajib diisi.',
            'name.min' => 'Nama makanan minimal 2 karakter.',
            'name.max' => 'Nama makanan maksimal 150 karakter.',
            'category.required' => 'Kategori wajib dipilih.',
            'category.in' => 'Kategori harus salah satu dari: '.implode(', ', Food::CATEGORIES).'.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'portion_description.required' => 'Deskripsi porsi wajib diisi.',
            'portion_description.min' => 'Deskripsi porsi minimal 2 karakter.',
            'portion_description.max' => 'Deskripsi porsi maksimal 100 karakter.',
            'description.max' => 'Deskripsi maksimal 2000 karakter.',
            'calories.required' => 'Kalori wajib diisi.',
            'calories.numeric' => 'Kalori harus berupa angka.',
            'calories.min' => 'Kalori tidak boleh negatif.',
            'protein.required' => 'Protein wajib diisi.',
            'protein.numeric' => 'Protein harus berupa angka.',
            'protein.min' => 'Protein tidak boleh negatif.',
            'carbs.required' => 'Karbohidrat wajib diisi.',
            'carbs.numeric' => 'Karbohidrat harus berupa angka.',
            'carbs.min' => 'Karbohidrat tidak boleh negatif.',
            'fat.required' => 'Lemak wajib diisi.',
            'fat.numeric' => 'Lemak harus berupa angka.',
            'fat.min' => 'Lemak tidak boleh negatif.',
            'image_url.url' => 'URL gambar tidak valid.',
            'image_url.max' => 'URL gambar maksimal 255 karakter.',
        ];
    }
}
