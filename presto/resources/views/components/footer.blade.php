<footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5 class="fw-bold">🏷️ Presto</h5>
                <p class="text-muted">{{ __('ui.footer_desc') }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                @auth
                    <a href="{{ route('revisor.work-with-us') }}" class="btn btn-outline-light btn-sm">
                        {{ __('ui.work_with_us') }}
                    </a>
                @endauth
                <p class="text-muted mt-2 mb-0">&copy; {{ date('Y') }} Presto. Tutti i diritti riservati.</p>
            </div>
        </div>
    </div>
</footer>
