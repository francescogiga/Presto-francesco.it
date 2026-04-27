<x-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                @if($article->images->isNotEmpty())
                    <div id="articleCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($article->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($image->path) }}"
                                         class="d-block w-100" style="height:400px;object-fit:cover;" alt="{{ $article->title }}">
                                </div>
                            @endforeach
                        </div>
                        @if($article->images->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#articleCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#articleCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <img src="https://picsum.photos/800/400" class="img-fluid rounded mb-4" alt="{{ $article->title }}">
                @endif
            </div>
            <div class="col-md-4">
                <span class="badge bg-secondary mb-2">{{ __('ui.' . $article->category->name) }}</span>
                <h2 class="fw-bold">{{ $article->title }}</h2>
                <h3 class="text-danger mb-3">€ {{ number_format($article->price, 2) }}</h3>
                <p class="text-muted">{{ $article->description }}</p>
                <p class="small text-muted">Pubblicato da: {{ $article->user->name }}</p>
                <p class="small text-muted">{{ $article->created_at->diffForHumans() }}</p>
                <a href="{{ route('article.index') }}" class="btn btn-outline-danger">
                    <i class="bi bi-arrow-left"></i> {{ __('ui.back') }}
                </a>
            </div>
        </div>
    </div>
</x-layout>
