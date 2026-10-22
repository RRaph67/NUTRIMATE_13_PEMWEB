{{-- Global flash messages and validation errors (Bahasa Indonesia) --}}

@isset($errors)
    @if ($errors->any())
        <div class="alert alert-error" role="alert" tabindex="-1" data-auto-focus>
            <strong>Formulir belum dapat diproses. Periksa isian berikut:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endisset

@if (session('status'))
    <div class="alert alert-success" role="status">
        {{ session('status') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-error" role="alert">
        {{ session('error') }}
    </div>
@endif
