{{--
    Shared create/edit form for foods.

    Expects:
      $categories — list<string> from Food::CATEGORIES
      $food       — ?Food (null on create, model on edit)

    Field names must match StoreFoodRequest / UpdateFoodRequest rules.
--}}

<div class="form-grid two-col">
    <div class="field span-2">
        <label for="name">
            Nama makanan <span class="required" aria-hidden="true">*</span>
        </label>
        <input id="name" name="name" type="text"
            value="{{ old('name', $food?->name) }}"
            placeholder="Contoh: Nasi Goreng Spesial" required minlength="2" maxlength="150"
            @error('name') aria-invalid="true" aria-describedby="name_error" @enderror>
        @error('name')
            <p class="field-error" id="name_error">{{ $message }}</p>
        @enderror
    </div>

    <div class="field">
        <label for="category">
            Kategori <span class="required" aria-hidden="true">*</span>
        </label>
        <select id="category" name="category" required
            @error('category') aria-invalid="true" aria-describedby="category_error" @enderror>
            <option value="">Pilih kategori…</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}"
                    @selected(old('category', $food?->category) === $category)>{{ $category }}</option>
            @endforeach
        </select>
        @error('category')
            <p class="field-error" id="category_error">{{ $message }}</p>
        @enderror
    </div>

    <div class="field">
        <label for="price">
            Harga (Rp) <span class="required" aria-hidden="true">*</span>
        </label>
        <input id="price" name="price" type="number" inputmode="decimal"
            value="{{ old('price', $food?->price) }}"
            placeholder="Contoh: 25000" required min="0" step="0.01"
            @error('price') aria-invalid="true" aria-describedby="price_error" @enderror>
        @error('price')
            <p class="field-error" id="price_error">{{ $message }}</p>
        @enderror
    </div>

    <div class="field span-2">
        <label for="portion_description">
            Deskripsi porsi <span class="required" aria-hidden="true">*</span>
        </label>
        <input id="portion_description" name="portion_description" type="text"
            value="{{ old('portion_description', $food?->portion_description) }}"
            placeholder="Contoh: 1 piring (250 gram)" required minlength="2" maxlength="100"
            @error('portion_description') aria-invalid="true" aria-describedby="portion_description_error" @enderror>
        @error('portion_description')
            <p class="field-error" id="portion_description_error">{{ $message }}</p>
        @enderror
    </div>

    <div class="field span-2">
        <label for="description">Deskripsi</label>
        <textarea id="description" name="description" maxlength="2000"
            placeholder="Ceritakan rasa, bahan utama, atau cara penyajian…"
            @error('description') aria-invalid="true" aria-describedby="description_error" @enderror>{{ old('description', $food?->description) }}</textarea>
        <span class="hint">Opsional, maksimal 2000 karakter.</span>
        @error('description')
            <p class="field-error" id="description_error">{{ $message }}</p>
        @enderror
    </div>

    <div class="field">
        <label for="calories">
            Kalori (kkal) <span class="required" aria-hidden="true">*</span>
        </label>
        <input id="calories" name="calories" type="number" inputmode="decimal"
            value="{{ old('calories', $food?->calories) }}"
            placeholder="Contoh: 350" required min="0" step="0.01"
            @error('calories') aria-invalid="true" aria-describedby="calories_error" @enderror>
        @error('calories')
            <p class="field-error" id="calories_error">{{ $message }}</p>
        @enderror
    </div>

    <div class="field">
        <label for="protein">
            Protein (gram) <span class="required" aria-hidden="true">*</span>
        </label>
        <input id="protein" name="protein" type="number" inputmode="decimal"
            value="{{ old('protein', $food?->protein) }}"
            placeholder="Contoh: 20" required min="0" step="0.01"
            @error('protein') aria-invalid="true" aria-describedby="protein_error" @enderror>
        @error('protein')
            <p class="field-error" id="protein_error">{{ $message }}</p>
        @enderror
    </div>

    <div class="field">
        <label for="carbs">
            Karbohidrat (gram) <span class="required" aria-hidden="true">*</span>
        </label>
        <input id="carbs" name="carbs" type="number" inputmode="decimal"
            value="{{ old('carbs', $food?->carbs) }}"
            placeholder="Contoh: 40" required min="0" step="0.01"
            @error('carbs') aria-invalid="true" aria-describedby="carbs_error" @enderror>
        @error('carbs')
            <p class="field-error" id="carbs_error">{{ $message }}</p>
        @enderror
    </div>

    <div class="field">
        <label for="fat">
            Lemak (gram) <span class="required" aria-hidden="true">*</span>
        </label>
        <input id="fat" name="fat" type="number" inputmode="decimal"
            value="{{ old('fat', $food?->fat) }}"
            placeholder="Contoh: 12" required min="0" step="0.01"
            @error('fat') aria-invalid="true" aria-describedby="fat_error" @enderror>
        @error('fat')
            <p class="field-error" id="fat_error">{{ $message }}</p>
        @enderror
    </div>

    <div class="field span-2">
        <label for="image_url">URL gambar</label>
        <input id="image_url" name="image_url" type="url"
            value="{{ old('image_url', $food?->image_url) }}"
            placeholder="https://contoh.com/gambar.jpg" maxlength="255"
            @error('image_url') aria-invalid="true" aria-describedby="image_url_error" @enderror>
        <span class="hint">Opsional. Gunakan URL gambar yang dapat diakses publik.</span>
        @error('image_url')
            <p class="field-error" id="image_url_error">{{ $message }}</p>
        @enderror
    </div>
</div>
