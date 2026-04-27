<x-layout>
    <div class="container py-5">
        <h2 class="mb-4 fw-bold"><i class="bi bi-shield-check"></i> Area Revisori</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($article_to_check)
            <div class="row">
                <div class="col-md-8">
                    @if($article_to_check->images->isNotEmpty())
                        <div class="row g-3 mb-4">
                            @foreach($article_to_check->images as $image)
                                <div class="col-md-4">
                                    <div class="card">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($image->path) }}"
                                             class="card-img-top" style="height:150px;object-fit:cover;" alt="immagine">
                                        <div class="card-body p-2">
                                            @if($image->labels)
                                                <p class="small text-muted mb-1"><strong>Etichette:</strong></p>
                                                <div class="d-flex flex-wrap gap-1 mb-2">
                                                    @foreach($image->labels as $label)
                                                        <span class="badge bg-light text-dark">{{ $label }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div class="d-flex gap-2 small">
                                                @if($image->adult)<span title="Adult"><i class="{{ $image->adult }}"></i></span>@endif
                                                @if($image->spoof)<span title="Spoof"><i class="{{ $image->spoof }}"></i></span>@endif
                                                @if($image->racy)<span title="Racy"><i class="{{ $image->racy }}"></i></span>@endif
                                                @if($image->violence)<span title="Violence"><i class="{{ $image->violence }}"></i></span>@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <img src="https://picsum.photos/600/300" class="img-fluid rounded mb-4" alt="placeholder">
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <span class="badge bg-secondary mb-2">{{ $article_to_check->category->name }}</span>
                            <h4 class="fw-bold">{{ $article_to_check->title }}</h4>
                            <p class="text-danger fw-bold">€ {{ number_format($article_to_check->price, 2) }}</p>
                            <p>{{ $article_to_check->description }}</p>
                            <p class="small text-muted">Da: {{ $article_to_check->user->name }}</p>
                            <p class="small text-muted">{{ $article_to_check->created_at->diffForHumans() }}</p>

                            <div class="d-grid gap-2 mt-3">
                                <form action="{{ route('revisor.accept', $article_to_check) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bi bi-check-circle"></i> Accetta
                                    </button>
                                </form>
                                <form action="{{ route('revisor.reject', $article_to_check) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="bi bi-x-circle"></i> Rifiuta
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-check-all display-1 text-success"></i>
                <h4 class="mt-3">Nessun annuncio da revisionare!</h4>
                <a href="{{ route('home') }}" class="btn btn-outline-danger mt-3">Torna alla home</a>
            </div>
        @endif
    </div>
</x-layout>
