<?php

namespace App\Jobs;

use App\Models\Image;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GoogleVisionLabelImage implements ShouldQueue
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
            $image = file_get_contents(storage_path('app/public/' . $i->path));
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);

            $imageAnnotator = new \Google\Cloud\Vision\V1\Client\ImageAnnotatorClient();
            $google_image = new \Google\Cloud\Vision\V1\Image();
            $google_image->setContent($image);

            $googleFeature = new \Google\Cloud\Vision\V1\Feature();
            $googleFeature->setType(\Google\Cloud\Vision\V1\Feature\Type::LABEL_DETECTION);

            $request = new \Google\Cloud\Vision\V1\AnnotateImageRequest();
            $request->setImage($google_image);
            $request->setFeatures([$googleFeature]);

            $batchRequest = new \Google\Cloud\Vision\V1\BatchAnnotateImagesRequest();
            $batchRequest->setRequests([$request]);

            $batchResponse = $imageAnnotator->batchAnnotateImages($batchRequest);
            $responses = $batchResponse->getResponses();
            $response = $responses[0];

            $labels = $response->getLabelAnnotations();
            if ($labels) {
                $result = [];
                foreach ($labels as $label) {
                    $result[] = $label->getDescription();
                }
                $i->labels = $result;
            }

            $i->save();
            $imageAnnotator->close();
        } catch (\Exception) {
            //
        }
    }
}
