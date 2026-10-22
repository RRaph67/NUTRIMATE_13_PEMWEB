@extends('layouts.app')

@section('title', 'Katalog Makanan — Nutrimate')

@section('content')
    <div class="page-header">
        <div>
            <h1>Katalog makanan</h1>
            <p>{{ $foods->total() }} data makanan tersedia.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('foods.create') }}">+ Tambah makanan</a>
    </div>

    {{-- Search, category filter and sort (whitelisted values only) --}}
    <form class="filters" action="{{ route('foods.index') }}" method="GET" role="search">
        <div class="filter-grid">
            <div class="field field-search">
                <label for="q">Cari nama makanan</label>
                <input id="q" name="q" type="search" value="{{ $filters['q'] }}"
                    placeholder="Contoh: nasi, ayam, jus…" maxlength="100">
            </div>

            <div class="field">
                <label for="category">Kategori</label>
                <select id="category" name="category">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}"
                            @selected($filters['category'] === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="sort">Urutkan</label>
                <select id="sort" name="sort">
                    <option value="">Nama (A–Z)</option>
                    <option value="termurah" @selected($filters['sort'] === 'termurah')>Harga termurah</option>
                    <option value="termahal" @selected($filters['sort'] === 'termahal')>Harga termahal</option>
                    <option value="kalori" @selected($filters['sort'] === 'kalori')>Kalori tertinggi</option>
                </select>
            </div>

            <div class="field">
                <button class="btn btn-primary" type="submit">Terapkan</button>
            </div>
        </div>

        @if ($filters['q'] !== '' || $filters['category'] !== '' || $filters['sort'] !== '')
            <div class="filter-meta">
                <span>Filter aktif diterapkan pada daftar di bawah.</span>
                <a href="{{ route('foods.index') }}">Hapus semua filter</a>
            </div>
        @endif
    </form>

    @if ($foods->count() === 0)
        <div class="state">
            <div class="state-icon" aria-hidden="true">🍽️</div>
            @if ($filters['q'] !== '' || $filters['category'] !== '' || $filters['sort'] !== '')
                <h2>Makanan tidak ditemukan</h2>
                <p>Tidak ada data yang cocok dengan pencarian atau filter Anda.</p>
                <a class="btn btn-secondary" href="{{ route('foods.index') }}">Tampilkan semua makanan</a>
            @else
                <h2>Belum ada data makanan</h2>
                <p>Katalog masih kosong. Tambahkan makanan pertama Anda.</p>
                <a class="btn btn-primary" href="{{ route('foods.create') }}">+ Tambah makanan</a>
            @endif
        </div>
    @else
        <ul class="food-grid">
            @foreach ($foods as $food)
                <li class="food-card">
                    <div class="food-media">
                        @if ($food->image_url)
                            <img src="{{ $food->image_url }}" alt="Gambar {{ $food->name }}" loading="lazy"
                                onerror="this.style.display='none'">
                        @endif
                        <span class="placeholder" aria-hidden="true">🍴</span>
                    </div>

                    <div class="food-card-body">
                        <span class="badge">{{ $food->category }}</span>

                        <h2 class="food-card-title">
                            <a href="{{ route('foods.show', $food) }}">{{ $food->name }}</a>
                        </h2>

                        <p class="food-price">Rp {{ number_format((float) $food->price, 0, ',', '.') }}</p>

                        <p class="food-meta">
                            <span>Porsi: {{ $food->portion_description }}</span>
                            <span>{{ number_format((float) $food->calories, 0, ',', '.') }} kkal</span>
                        </p>

                        <div class="food-card-footer">
                            <a class="btn btn-secondary btn-sm"
                                href="{{ route('foods.show', $food) }}">Detail</a>
                            <a class="btn btn-secondary btn-sm"
                                href="{{ route('foods.edit', $food) }}">Edit</a>
                            <form class="inline-form" action="{{ route('foods.destroy', $food) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus makanan &quot;{{ $food->name }}&quot;? Tindakan ini tidak dapat dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="pagination">
            {{ $foods->links('pagination.links') }}
        </div>
    @endif
@endsection
