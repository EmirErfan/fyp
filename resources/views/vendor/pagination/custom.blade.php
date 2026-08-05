@if ($paginator->hasPages())
    <nav class="pagination-nav">
        @if ($paginator->onFirstPage())
            <span class="page-item disabled">&laquo; Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-item" rel="prev">&laquo; Previous</a>
        @endif

        <div class="page-numbers">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="page-item disabled">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-item active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-item">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-item" rel="next">Next &raquo;</a>
        @else
            <span class="page-item disabled">Next &raquo;</span>
        @endif
    </nav>
@endif
