<?php

namespace App\Policies;

use App\Models\Image;
use App\Models\User;

class ImagePolicy
{
    public function delete(User $user, Image $image)
    {
        return $user->id === $image->pay->debt->user_id;
    }
}
