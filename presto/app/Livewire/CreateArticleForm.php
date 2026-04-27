<?php

namespace App\Livewire;

use App\Jobs\GoogleVisionLabelImage;
use App\Jobs\GoogleVisionSafeSearch;
use App\Jobs\RemoveFaces;
use App\Jobs\ResizeImage;
use App\Models\Article;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateArticleForm extends Component
{
    use WithFileUploads;

    #[Validate('required|min:3')]
    public string $title = '';

    #[Validate('required|min:10')]
    public string $description = '';

    #[Validate('required|numeric|min:0')]
    public string $price = '';

    #[Validate('required|exists:categories,id')]
    public string $category_id = '';

    public ?Article $article = null;
    public array $images = [];
    public mixed $temporary_images = null;

    public function updatedTemporaryImages(): void
    {
        $this->validate([
            'temporary_images.*' => 'image|max:1024',
            'temporary_images' => 'max:6',
        ]);

        foreach ($this->temporary_images as $image) {
            $this->images[] = $image;
        }
    }

    public function removeImage(int $key): void
    {
        if (in_array($this->images[$key], $this->images)) {
            $keys = array_keys($this->images);
            unset($this->images[$keys[$key]]);
        }
    }

    public function store(): void
    {
        $this->validate();

        $this->article = Article::create([
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'user_id' => auth()->id(),
        ]);

        if (!empty($this->images)) {
            foreach ($this->images as $image) {
                $newFileName = "articles/{$this->article->id}";
                $newImage = $this->article->images()->create([
                    'path' => $image->store($newFileName, 'public'),
                ]);

                dispatch(new ResizeImage($newImage->path, 300, 300))
                    ->withChain([
                        new RemoveFaces($newImage->id),
                        new GoogleVisionSafeSearch($newImage->id),
                        new GoogleVisionLabelImage($newImage->id),
                    ]);
            }
        }

        File::deleteDirectory(storage_path('/app/livewire-tmp'));

        $this->dispatch('article-created');
        $this->cleanForm();
    }

    public function cleanForm(): void
    {
        $this->title = '';
        $this->description = '';
        $this->price = '';
        $this->category_id = '';
        $this->article = null;
        $this->images = [];
        $this->temporary_images = null;
    }

    public function render()
    {
        return view('livewire.create-article-form', [
            'categories' => \App\Models\Category::orderBy('name')->get(),
        ]);
    }
}
