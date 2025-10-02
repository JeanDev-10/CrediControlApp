<?php

namespace App\Repositories\Eloquent;

use App\Models\Image;
use App\Repositories\Interfaces\ImageRepositoryInterface;

class ImageRepository extends BaseRepository implements ImageRepositoryInterface
{
    public function __construct(Image $model)
    {
        $this->model = $model;
    }
    public function deleteByPayId(int $payId): int
    {
        return $this->model->where('pay_id', $payId)->delete();
    }
}
