@if ($paginator->lastPage() > 1)
<div class="paginator">
    @if ($paginator->currentPage() != 1)
    <a href="{{ $paginator->previousPageUrl() }}"><button class="btn btn-lg" type="button">< 前</button></a>
    @else
    <a href="javascript:void(0)"><button type="button" class="btn btn-lg disabled" disabled="disabled">< 前</button></a>
    @endif
    <span>{{ $paginator->currentPage() }}/{{ $paginator->lastPage() }}</span>
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}"><button class="btn btn-lg" type="button">次 ></button></a>
    @else
    <a href="javascript:void(0)"><button class="btn btn-lg disabled" type="button" disabled="disabled">次 ></button></a>
    @endif
</div>
@endif