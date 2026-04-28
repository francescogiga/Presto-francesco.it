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
use Illuminate\Support\Facades\Log;

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

            Log::info('RemoveFaces: rilevati ' . count($faces) . ' volti', [
                'image_id' => $this->article_image_id,
            ]);

            if (count($faces) === 0) return;

            $img = $this->loadGdImage($srcPath);
            if (!$img) return;

            foreach ($faces as $face) {
                $vertices = $face->getBoundingPoly()->getVertices();
                $bounds = [];
                foreach ($vertices as $vertex) {
                    $bounds[] = ['x' => $vertex->getX(), 'y' => $vertex->getY()];
                }

                $x      = max(0, $bounds[0]['x']);
                $y      = max(0, $bounds[0]['y']);
                $width  = $bounds[1]['x'] - $bounds[0]['x'];
                $height = $bounds[2]['y'] - $bounds[0]['y'];

                $this->pixelateRegion($img, $x, $y, $width, $height, 15);
            }

            $this->saveGdImage($img, $srcPath);
            imagedestroy($img);

        } catch (\Exception $e) {
            Log::error('RemoveFaces failed: ' . $e->getMessage(), [
                'image_id' => $this->article_image_id,
            ]);
        }
    }

    private function pixelateRegion(\GdImage $img, int $x, int $y, int $w, int $h, int $blockSize): void
    {
        $imgW = imagesx($img);
        $imgH = imagesy($img);

        for ($px = $x; $px < $x + $w; $px += $blockSize) {
            for ($py = $y; $py < $y + $h; $py += $blockSize) {
                $sampleX = min($px + intval($blockSize / 2), $imgW - 1);
                $sampleY = min($py + intval($blockSize / 2), $imgH - 1);

                $color = imagecolorat($img, $sampleX, $sampleY);
                $r = ($color >> 16) & 0xFF;
                $g = ($color >> 8) & 0xFF;
                $b = $color & 0xFF;

                $fill = imagecolorallocate($img, $r, $g, $b);
                imagefilledrectangle(
                    $img,
                    $px, $py,
                    min($px + $blockSize - 1, $x + $w - 1),
                    min($py + $blockSize - 1, $y + $h - 1),
                    $fill
                );
            }
        }
    }

    private function loadGdImage(string $path): \GdImage|false
    {
        return match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'jpg', 'jpeg' => imagecreatefromjpeg($path),
            'png'         => imagecreatefrompng($path),
            'webp'        => imagecreatefromwebp($path),
            default       => false,
        };
    }

    private function saveGdImage(\GdImage $img, string $path): void
    {
        match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'jpg', 'jpeg' => imagejpeg($img, $path, 90),
            'png'         => imagepng($img, $path),
            'webp'        => imagewebp($img, $path, 90),
            default       => imagejpeg($img, $path, 90),
        };
    }
}
