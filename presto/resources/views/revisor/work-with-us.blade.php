<x-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h2 class="fw-bold mb-1">Lavora con noi</h2>
                        <p class="text-muted mb-4">Vuoi diventare revisore su Presto? Compilare il form qui sotto e ti contatteremo al più presto.</p>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('revisor.become') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nome</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Motivazione *</label>
                                <textarea name="message" rows="5"
                                    class="form-control @error('message') is-invalid @enderror"
                                    placeholder="Descrivi perché vorresti diventare revisore...">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-send"></i> Invia richiesta
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
