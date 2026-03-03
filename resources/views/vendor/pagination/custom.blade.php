@if ($paginator->hasPages())
    <div style="display: flex; justify-content: center; align-items: center; gap: 0.5rem; margin-top: 2rem;">
        {{-- First Page Link --}}
        @if ($paginator->onFirstPage())
            <button disabled
                style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; background: #f3f4f6; color: #6b7280; border-radius: 0.25rem; cursor: not-allowed;">
                <i class="fas fa-step-backward"></i>
            </button>
        @else
            <a href="{{ $paginator->url(1) }}"
                style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; background: #ffffff; color: #1f2937; border-radius: 0.25rem; text-decoration: none;">
                <i class="fas fa-step-backward"></i>
            </a>
        @endif

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <button disabled
                style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; background: #f3f4f6; color: #6b7280; border-radius: 0.25rem; cursor: not-allowed;">
                <i class="fas fa-chevron-left"></i>
            </button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
                style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; background: #ffffff; color: #1f2937; border-radius: 0.25rem; text-decoration: none;">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        {{-- Page Number Display --}}
        <div
            style="padding: 0.5rem 1rem; background: #10b981; color: #ffffff; border-radius: 0.25rem; font-weight: 600; min-width: 60px; text-align: center;">
            {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
                style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; background: #ffffff; color: #1f2937; border-radius: 0.25rem; text-decoration: none;">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <button disabled
                style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; background: #f3f4f6; color: #6b7280; border-radius: 0.25rem; cursor: not-allowed;">
                <i class="fas fa-chevron-right"></i>
            </button>
        @endif

        {{-- Last Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->url($paginator->lastPage()) }}"
                style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; background: #ffffff; color: #1f2937; border-radius: 0.25rem; text-decoration: none;">
                <i class="fas fa-step-forward"></i>
            </a>
        @else
            <button disabled
                style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; background: #f3f4f6; color: #6b7280; border-radius: 0.25rem; cursor: not-allowed;">
                <i class="fas fa-step-forward"></i>
            </button>
        @endif
    </div>
@endif
