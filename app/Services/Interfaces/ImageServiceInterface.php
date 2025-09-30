<?php
namespace App\Services\Interfaces;
interface ImageServiceInterface
{
    public function uploadImages($files, string $folder): array;
    public function deleteImage(string $filePath): bool;
    public function deleteImages(array $filePaths): bool;
}
