{{-- Plain-CSS pagination (no framework) — rendered by App\Http\Controllers\FoodController@index --}}
@if ($paginator->hasPages())
    <nav aria-label="Navigasi halaman">
        <ul>
            <li>
                @if ($paginator->onFirstPage())
                    <span class="page-link is-disabled" aria-disabled="true">&laquo; Sebelumnya</span>
                @else
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Sebelumnya</a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="page-ellipsis" aria-hidden="true">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="page-link is-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li>
                @if ($paginator->hasMorePages())
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya &raquo;</a>
                @else
                    <span class="page-link is-disabled" aria-disabled="true">Berikutnya &raquo;</span>
                @endif
            </li>
        </ul>
    </nav>

    <p class="pagination-summary">
        Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
        dari {{ $paginator->total() }} makanan
    </p>
@endif
