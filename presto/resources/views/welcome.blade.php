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

    <div class="bg-danger text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">{{ __('ui.hero_title') }}</h1>
            <p class="lead">{{ __('ui.hero_subtitle') }}</p>
            @auth
                <a href="{{ route('article.create') }}" class="btn btn-light btn-lg fw-bold">
                    <i class="bi bi-plus-circle"></i> {{ __('ui.add_ad') }}
                </a>
            @else
                <a href="/register" class="btn btn-light btn-lg fw-bold">
                    {{ __('ui.start_now') }}
                </a>
            @endauth
        </div>
    </div>

    <div class="container my-5">
        <h2 class="mb-4">{{ __('ui.latest_ads') }}</h2>
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
