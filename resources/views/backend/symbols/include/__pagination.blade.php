<div class="pagination-container mt-4 ml-4">
    Showing <span id="paginationInfo">{{ $mt5Symbols->firstItem() }} to {{ $mt5Symbols->lastItem() }} of {{ $mt5Symbols->total() }}</span> symbols
    <div >
        {{ $mt5Symbols->appends(request()->query())->links() }}
    </div>
</div>
