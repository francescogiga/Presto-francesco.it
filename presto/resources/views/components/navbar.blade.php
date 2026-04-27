<nav class="navbar navbar-expand-lg navbar-dark bg-danger">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">🏷️ Presto</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">{{ __('ui.home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('article.index') }}">{{ __('ui.all_ads') }}</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ __('ui.categories') }}</a>
                    <ul class="dropdown-menu">
                        @foreach($categories as $category)
                            <li>
                                <a class="dropdown-item" href="{{ route('article.byCategory', $category) }}">
                                    {{ __('ui.' . $category->name) }}
                                </a>
                            </li>
                            @if(!$loop->last)<li><hr class="dropdown-divider"></li>@endif
                        @endforeach
                    </ul>
                </li>
            </ul>

            <form class="d-flex me-2" action="{{ route('article.search') }}" method="GET">
                <input class="form-control me-2" type="search" name="query" placeholder="{{ __('ui.search') }}...">
                <button class="btn btn-outline-light" type="submit"><i class="bi bi-search"></i></button>
            </form>

            <ul class="navbar-nav">
                <li class="nav-item d-flex align-items-center gap-1 me-2">
                    <x-_locale lang="it" />
                    <x-_locale lang="uk" />
                    <x-_locale lang="es" />
                </li>

                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('article.create') }}">
                                    <i class="bi bi-plus-circle"></i> {{ __('ui.add_ad') }}
                                </a>
                            </li>
                            @if(auth()->user()->is_revisor)
                                <li>
                                    <a class="dropdown-item" href="{{ route('revisor.index') }}">
                                        <i class="bi bi-shield-check"></i> Area Revisori
                                        <span class="badge bg-danger ms-1">{{ App\Models\Article::toBeRevisedCount() }}</span>
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="/logout" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="/login">{{ __('ui.login') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">{{ __('ui.register') }}</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
