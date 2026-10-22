@extends('layouts.app')

@section('title', $food->name . ' — Nutrimate')

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ $food->name }}</h1>
            <p>Detail lengkap makanan, termasuk harga, porsi, dan nilai gizi.</p>
        </div>
        <a class="btn btn-secondary" href="{{ route('foods.index') }}">← Kembali ke katalog</a>
    </div>

    <div class="show-layout">
        <div class="detail-media">
            @if ($food->image_url)
                <img src="{{ $food->image_url }}" alt="Gambar {{ $food->name }}"
                    onerror="this.style.display='none'">
            @else
                <div class="placeholder" aria-hidden="true">🍴</div>
            @endif
        </div>

        <div>
            <div class="detail-title">
                <span class="badge">{{ $food->category }}</span>
                <p class="food-price">Rp {{ number_format((float) $food->price, 0, ',', '.') }}</p>
            </div>

            <section class="detail-section">
                <h2>Informasi umum</h2>
                <dl class="definition-list">
                    <div>
                        <dt>Nama</dt>
                        <dd>{{ $food->name }}</dd>
                    </div>
                    <div>
                        <dt>Kategori</dt>
                        <dd>{{ $food->category }}</dd>
                    </div>
                    <div>
                        <dt>Harga</dt>
                        <dd>Rp {{ number_format((float) $food->price, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt>Porsi</dt>
                        <dd>{{ $food->portion_description }}</dd>
                    </div>
                    <div>
                        <dt>Deskripsi</dt>
                        <dd>{{ $food->description ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt>Ditambahkan</dt>
                        <dd>{{ $food->created_at?->translatedFormat('d F Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt>Terakhir diperbarui</dt>
                        <dd>{{ $food->updated_at?->translatedFormat('d F Y') ?? '-' }}</dd>
                    </div>
                </dl>
            </section>

            <section class="detail-section">
                <h2>Nilai gizi per porsi</h2>
                <div class="macro-grid">
                    <div class="macro">
                        <span class="value">{{ number_format((float) $food->calories, 0, ',', '.') }}</span>
                        <span class="label">kkal kalori</span>
                    </div>
                    <div class="macro">
                        <span class="value">{{ number_format((float) $food->protein, 0, ',', '.') }}</span>
                        <span class="label">gram protein</span>
                    </div>
                    <div class="macro">
                        <span class="value">{{ number_format((float) $food->carbs, 0, ',', '.') }}</span>
                        <span class="label">gram karbohidrat</span>
                    </div>
                    <div class="macro">
                        <span class="value">{{ number_format((float) $food->fat, 0, ',', '.') }}</span>
                        <span class="label">gram lemak</span>
                    </div>
                </div>
            </section>

            <div class="btn-row" style="margin-top: 24px">
                <a class="btn btn-primary" href="{{ route('foods.edit', $food) }}">Edit makanan</a>
                <a class="btn btn-secondary" href="{{ route('foods.index') }}">Lihat katalog</a>
                <form class="inline-form" action="{{ route('foods.destroy', $food) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus makanan &quot;{{ $food->name }}&quot;? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Hapus makanan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
