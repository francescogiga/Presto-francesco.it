<x-layout>
    <div class="container py-5">
        <h2 class="mb-4 fw-bold">{{ __('ui.all_ads') }}</h2>
        <div class="row g-4">
            @forelse($articles as $article)
                <div class="col-md-4 col-sm-6">
                    <x-card :article="$article" />
                </div>
            @empty
                <p class="text-muted">{{ __('ui.no_ads') }}</p>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $articles->links() }}
        </div>
    </div>
</x-layout>
