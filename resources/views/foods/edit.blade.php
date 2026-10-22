@extends('layouts.app')

@section('title', 'Edit ' . $food->name . ' — Nutrimate')

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit makanan</h1>
            <p>Perbarui informasi <strong>{{ $food->name }}</strong> di katalog Anda.</p>
        </div>
        <a class="btn btn-secondary" href="{{ route('foods.show', $food) }}">← Kembali ke detail</a>
    </div>

    <div class="panel">
        <form action="{{ route('foods.update', $food) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            @include('foods._form')

            <div class="btn-row" style="margin-top: 18px">
                <button type="submit" class="btn btn-primary">Perbarui Makanan</button>
                <a class="btn btn-secondary" href="{{ route('foods.show', $food) }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
