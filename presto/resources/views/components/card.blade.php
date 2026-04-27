<div class="card h-100 shadow-sm card-w mx-auto">
    <img src="{{ $article->images->isNotEmpty() ? \Illuminate\Support\Facades\Storage::url($article->images->first()->path) : 'https://picsum.photos/300/200' }}"
         class="card-img-top" style="height:200px;object-fit:cover;" alt="{{ $article->title }}">
    <div class="card-body">
        <span class="badge bg-secondary mb-2">{{ __('ui.' . $article->category->name) }}</span>
        <h5 class="card-title">{{ $article->title }}</h5>
        <p class="card-text fw-bold text-danger">€ {{ number_format($article->price, 2) }}</p>
    </div>
    <div class="card-footer">
        <a href="{{ route('article.show', $article) }}" class="btn btn-outline-danger btn-sm w-100">
            {{ __('ui.view_detail') }}
        </a>
    </div>
</div>
