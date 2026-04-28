<x-layout>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div style="position:relative; height:520px; overflow:hidden;">
        <img src="{{ asset('images/hero.jpg') }}"
             style="width:100%; height:100%; object-fit:cover; object-position:center top;" alt="hero">
        <div style="position:absolute; inset:0; background:rgba(0,0,0,0.25); display:flex; flex-direction:column; align-items:center; justify-content:center; gap:1.5rem;">
            <span style="font-size:3.5rem; font-weight:900; color:#fff; letter-spacing:3px; text-shadow:0 2px 12px rgba(0,0,0,0.5);">Presto.it</span>
            @auth
                <a href="{{ route('article.create') }}" class="btn btn-danger btn-lg fw-bold px-5 py-3 fs-5 shadow">
                    <i class="bi bi-plus-circle me-2"></i>{{ __('ui.add_ad') }}
                </a>
            @else
                <a href="/register" class="btn btn-danger btn-lg fw-bold px-5 py-3 fs-5 shadow">
                    {{ __('ui.start_now') }}
                </a>
            @endauth
        </div>
    </div>

    <div class="container my-5">
        <h2 class="mb-4 text-center">{{ __('ui.latest_ads') }}</h2>
        <div class="row g-4">
            @forelse($articles as $article)
                <div class="col-md-4 col-sm-6">
                    <x-card :article="$article" />
                </div>
            @empty
                <p class="text-muted">{{ __('ui.no_ads') }}</p>
            @endforelse
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('article.index') }}" class="btn btn-outline-danger">
                {{ __('ui.view_all') }} <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</x-layout>
