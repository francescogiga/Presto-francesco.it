<?php

namespace App\Jobs;

use App\Models\Image;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\Image as VisionImage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GoogleVisionSafeSearch implements ShouldQueue
{
    use Queueable;

    private int $article_image_id;

    public function __construct(int $article_image_id)
    {
        $this->article_image_id = $article_image_id;
    }

    public function handle(): void
    {
        $i = Image::find($this->article_image_id);
        if (!$i) return;

        $credentialsPath = base_path('google_credential.json');
        if (!file_exists($credentialsPath)) return;

        try {
            $imageContent = file_get_contents(storage_path('app/public/' . $i->path));
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);

            $googleVisionClient = new ImageAnnotatorClient();

            $google_image = new VisionImage();
            $google_image->setContent($imageContent);

            $googleFeature = new Feature();
            $googleFeature->setType(Type::SAFE_SEARCH_DETECTION);

            $request = new AnnotateImageRequest();
            $request->setImage($google_image);
            $request->setFeatures([$googleFeature]);

            $batchRequest = new BatchAnnotateImagesRequest();
            $batchRequest->setRequests([$request]);

            $batchResponse = $googleVisionClient->batchAnnotateImages($batchRequest);
            $response = $batchResponse->getResponses();
            $googleVisionClient->close();

            $safe = $response[0]->getSafeSearchAnnotation();
            $adult    = $safe->getAdult();
            $spoof    = $safe->getSpoof();
            $racy     = $safe->getRacy();
            $medical  = $safe->getMedical();
            $violence = $safe->getViolence();

            $likelihoodName = [
                0 => 'bi bi-question-circle',
                1 => 'bi bi-check-circle text-success',
                2 => 'bi bi-check-circle text-success',
                3 => 'bi bi-exclamation-triangle text-warning',
                4 => 'bi bi-exclamation-triangle text-danger',
                5 => 'bi bi-x-circle text-danger',
            ];

            $i->adult    = $likelihoodName[$adult]    ?? 'bi bi-question-circle';
            $i->spoof    = $likelihoodName[$spoof]    ?? 'bi bi-question-circle';
            $i->racy     = $likelihoodName[$racy]     ?? 'bi bi-question-circle';
            $i->medical  = $likelihoodName[$medical]  ?? 'bi bi-question-circle';
            $i->violence = $likelihoodName[$violence] ?? 'bi bi-question-circle';
            $i->save();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('GoogleVisionSafeSearch: ' . $e->getMessage());
        }
    }
}
