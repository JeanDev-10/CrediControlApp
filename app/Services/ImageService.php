<?php

namespace App\Services;

use App\Repositories\Interfaces\ImageRepositoryInterface;

class ImageService
{
    public function __construct(protected ImageRepositoryInterface $imageRepository)
    {
        // Constructor vacío o con dependencias si es necesario
    }
    public function get(int $id)
    {
        return $this->imageRepository->find($id);
    }
    // Aquí puedes agregar métodos relacionados con la gestión de imágenes
}
