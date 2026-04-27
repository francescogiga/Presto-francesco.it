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
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Image as SpatieImage;

class RemoveFaces implements ShouldQueue
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
            $srcPath = storage_path('app/public/' . $i->path);
            $imageContent = file_get_contents($srcPath);
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);

            $googleVisionClient = new ImageAnnotatorClient();

            $google_image = new VisionImage();
            $google_image->setContent($imageContent);

            $googleFeature = new Feature();
            $googleFeature->setType(Type::FACE_DETECTION);

            $request = new AnnotateImageRequest();
            $request->setImage($google_image);
            $request->setFeatures([$googleFeature]);

            $batchRequest = new BatchAnnotateImagesRequest();
            $batchRequest->setRequests([$request]);

            $batchResponse = $googleVisionClient->batchAnnotateImages($batchRequest);
            $response = $batchResponse->getResponses();
            $googleVisionClient->close();

            $faces = $response[0]->getFaceAnnotations();

            foreach ($faces as $face) {
                $vertices = $face->getBoundingPoly()->getVertices();
                $bounds = [];
                foreach ($vertices as $vertex) {
                    $bounds[] = ['x' => $vertex->getX(), 'y' => $vertex->getY()];
                }

                $width = $bounds[1]['x'] - $bounds[0]['x'];
                $height = $bounds[2]['y'] - $bounds[0]['y'];

                SpatieImage::load($srcPath)
                    ->watermark(
                        public_path('images/censura.png'),
                        AlignPosition::TopLeft,
                        $bounds[0]['x'],
                        $bounds[0]['y'],
                        Unit::Pixel,
                        $width,
                        Unit::Pixel,
                        $height,
                        Unit::Pixel,
                        Fit::Stretch
                    )
                    ->save($srcPath);
            }
        } catch (\Exception) {
            //
        }
    }
}
