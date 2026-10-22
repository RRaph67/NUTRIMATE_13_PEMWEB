@extends('layouts.app')

@section('title', 'NutriMate - Database Makanan & Nutrisi')

@section('content')
    <main class="page">
        <div class="container">
            <!-- Kepala halaman -->
            <div class="page-head">
                <span class="page-badge">Katalog Nutrisi</span>
                <h1 class="page-title">Database Makanan &amp; Nutrisi</h1>
                <p class="page-subtitle">Katalog makanan bernutrisi ramah kantong mahasiswa dan anak kost.</p>
            </div>

            <!-- Search & Category Filter -->
            <form class="search-filter-box" action="{{ route('foods.index') }}" method="GET" id="foodFilterForm">
                <div class="search-row">
                    <div class="search-input-wrapper">
                        <svg class="search-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                        </svg>
                        <input type="text" id="searchInput" name="q" value="{{ $filters['q'] }}"
                            placeholder="Cari makanan..." aria-label="Cari makanan" autocomplete="off">
                    </div>
                    <select class="sort-select" id="sortSelect" name="sort" aria-label="Urutkan makanan">
                        <option value="cheap" @selected($filters['sort'] === 'cheap' || $filters['sort'] === 'termurah')>Termurah</option>
                        <option value="cal" @selected($filters['sort'] === 'cal')>Kalori Rendah</option>
                        <option value="termahal" @selected($filters['sort'] === 'termahal')>Termahal</option>
                        <option value="kalori" @selected($filters['sort'] === 'kalori')>Kalori Tertinggi</option>
                    </select>
                </div>
                <input type="hidden" name="category" id="categoryInput" value="{{ $filters['category'] }}">
                <div class="category-pills" id="categoryPills">
                    <button type="button" class="pill {{ empty($filters['category']) ? 'active' : '' }}" data-category="">Semua</button>
                    @foreach (['Karbohidrat', 'Protein', 'Sayuran', 'Buah', 'Minuman'] as $cat)
                        <button type="button" class="pill {{ $filters['category'] === $cat ? 'active' : '' }}" data-category="{{ $cat }}">{{ $cat }}</button>
                    @endforeach
                </div>
            </form>

            <div class="btn-row" style="margin-bottom: 22px; justify-content: flex-end;">
                <a href="{{ route('foods.create') }}" class="btn btn-primary">+ Tambah Menu Makanan</a>
            </div>

            <!-- Food Grid -->
            @if ($foods->count() === 0)
                <div class="grid-status">
                    <span class="status-title">Makanan tidak ditemukan</span>
                    <span>Coba kata kunci atau filter kategori lain.</span>
                    <br>
                    <a href="{{ route('foods.index') }}" class="btn-retry" style="display: inline-block; text-decoration: none;">Tampilkan Semua</a>
                </div>
            @else
                <div class="food-grid" id="foodGrid" aria-live="polite">
                    @foreach ($foods as $food)
                        <article class="food-card">
                            <div class="card-img-wrapper">
                                <span class="category-badge">{{ $food->category }}</span>
                                @if ($food->image_url)
                                    <img src="{{ $food->image_url }}" alt="{{ $food->name }}" loading="lazy"
                                        onerror="this.style.display='none'">
                                @endif
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">{{ $food->name }}</h3>
                                <div class="card-meta">
                                    <div class="card-price">
                                        Rp {{ number_format((float) $food->price, 0, ',', '.') }}
                                        <span>/ {{ $food->portion_description }}</span>
                                    </div>
                                    <div class="card-calories">{{ number_format((float) $food->calories, 0, ',', '.') }} kcal</div>
                                </div>
                                <button type="button" class="btn-detail"
                                    data-food-name="{{ $food->name }}"
                                    data-food-category="{{ $food->category }}"
                                    data-food-price="Rp {{ number_format((float) $food->price, 0, ',', '.') }}"
                                    data-food-unit="/ {{ $food->portion_description }}"
                                    data-food-desc="{{ $food->description ?: 'Tidak ada deskripsi rinci.' }}"
                                    data-food-cal="{{ number_format((float) $food->calories, 0, ',', '.') }} kcal"
                                    data-food-protein="{{ number_format((float) $food->protein, 0, ',', '.') }}g"
                                    data-food-carbs="{{ number_format((float) $food->carbs, 0, ',', '.') }}g"
                                    data-food-fat="{{ number_format((float) $food->fat, 0, ',', '.') }}g"
                                    data-food-image="{{ $food->image_url ?? '' }}"
                                    data-food-edit="{{ route('foods.edit', $food) }}"
                                    data-food-show="{{ route('foods.show', $food) }}">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                                    </svg>
                                    Detail &amp; Gizi
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="pagination" style="margin-top: 26px;">
                    {{ $foods->links('pagination.links') }}
                </div>
            @endif

        </div>
    </main>

    <!-- Modal Popup Detail Makanan -->
    <div class="modal-overlay" id="modalOverlay" aria-hidden="true">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
            <div class="modal-img-wrapper">
                <span class="category-badge" id="modalBadge"></span>
                <button type="button" class="btn-close" id="btnCloseModal" aria-label="Tutup">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
                <img id="modalImg" src="" alt="" style="display: none;">
            </div>
            <div class="modal-content">
                <h2 class="modal-title" id="modalTitle"></h2>
                <div class="modal-price"><span id="modalPrice"></span> <span id="modalUnit"></span></div>

                <div class="modal-desc-box" id="modalDesc"></div>

                <div class="macro-title">Kandungan Makronutrisi:</div>
                <div class="macro-grid">
                    <div class="macro-box">
                        <div class="macro-label">Kalori</div>
                        <div class="macro-val" id="modalCal"></div>
                    </div>
                    <div class="macro-box">
                        <div class="macro-label">Protein</div>
                        <div class="macro-val highlight" id="modalProtein"></div>
                    </div>
                    <div class="macro-box">
                        <div class="macro-label">Karbo</div>
                        <div class="macro-val" id="modalCarbs"></div>
                    </div>
                    <div class="macro-box">
                        <div class="macro-label">Lemak</div>
                        <div class="macro-val" id="modalFat"></div>
                    </div>
                </div>

                <div class="btn-row" style="margin-top: 14px; gap: 8px;">
                    <button type="button" class="btn-add-planner" id="btnAddPlanner" style="flex: 1;">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
                        Tambah ke Meal Planner
                    </button>
                    <a href="#" id="modalEditLink" class="btn btn-secondary btn-sm" style="border-radius: 99px;">Edit</a>
                </div>
            </div>
        </div>
    </div>
@endsection
