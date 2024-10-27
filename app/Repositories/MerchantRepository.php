<?php


namespace App\Repositories;


use App\Models\Merchant;

class MerchantRepository extends BaseRepository
{
    public function __construct(Merchant $model)
    {
        $this->model = $model;
    }
}
