<?php
namespace App\Repositories\Interfaces;
interface ImageRepositoryInterface extends BaseRepositoryInterface
{
    public function deleteByPayId(int $payId);

}
