@extends('layouts.app')

@section('title', 'Tambah Makanan — Nutrimate')

@section('content')
    <div class="page-header">
        <div>
            <h1>Tambah makanan</h1>
            <p>Lengkapi formulir berikut untuk menambahkan makanan baru ke katalog.</p>
        </div>
        <a class="btn btn-secondary" href="{{ route('foods.index') }}">← Kembali ke katalog</a>
    </div>

    <div class="panel">
        <form action="{{ route('foods.store') }}" method="POST" novalidate>
            @csrf

            @include('foods._form', ['food' => null])

            <div class="btn-row" style="margin-top: 18px">
                <button type="submit" class="btn btn-primary">Simpan Makanan</button>
                <a class="btn btn-secondary" href="{{ route('foods.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
