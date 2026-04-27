<div>
    @if(session()->has('article-created'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Annuncio inserito con successo!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div x-data="{ success: false }" @article-created.window="success = true">
        <div x-show="success" class="alert alert-info border-0 shadow-sm" role="alert">
            <h5 class="alert-heading"><i class="bi bi-hourglass-split"></i> Annuncio inserito, in attesa di approvazione</h5>
            <p class="mb-2">Il tuo annuncio è stato ricevuto correttamente. Prima di essere visibile pubblicamente deve essere <strong>approvato da un revisore</strong>.</p>
            @if(auth()->user()->is_revisor)
                <hr>
                <a href="{{ route('revisor.index') }}" class="btn btn-sm btn-danger">
                    <i class="bi bi-shield-check"></i> Vai alla dashboard revisori per approvarlo
                </a>
            @endif
        </div>
    </div>

    <form wire:submit="store">
        <div class="mb-3">
            <label class="form-label fw-bold">Titolo *</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model.blur="title">
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Descrizione *</label>
            <textarea class="form-control @error('description') is-invalid @enderror" rows="4" wire:model.blur="description"></textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Prezzo (€) *</label>
            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" wire:model.blur="price">
            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Categoria *</label>
            <select class="form-select @error('category_id') is-invalid @enderror" wire:model.blur="category_id">
                <option value="">-- Seleziona categoria --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Immagini</label>
            <input type="file" class="form-control" wire:model="temporary_images" multiple accept="image/*">
            @error('temporary_images.*')<div class="text-danger small">{{ $message }}</div>@enderror
            @error('temporary_images')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        @if(!empty($images))
            <div class="mb-3 d-flex flex-wrap gap-2">
                @foreach($images as $key => $image)
                    <div wire:key="{{ $key }}" class="position-relative">
                        <div class="img-preview" style="background-image: url('{{ $image->temporaryUrl() }}'); background-size: cover; background-position: center;"></div>
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0" style="width:20px;height:20px;font-size:10px;"
                            wire:click="removeImage({{ $key }})">×</button>
                    </div>
                @endforeach
            </div>
        @endif

        <button type="submit" class="btn btn-danger">
            <i class="bi bi-plus-circle"></i> Inserisci Annuncio
        </button>
    </form>
</div>
