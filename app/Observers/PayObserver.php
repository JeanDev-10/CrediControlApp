<?php

namespace App\Observers;

use App\Models\Pay;
use App\Services\UserService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
class PayObserver implements  ShouldHandleEventsAfterCommit
{
    public function __construct(protected UserService $userService)
    {
    }
    /**
     * Handle the Contact "created" event.
     */
    public function created(Pay $pay): void
    {
        activity()
            ->performedOn($pay)
            ->causedBy($this->userService->getUserLoggedIn())
            ->withProperties(['attributes' => $pay->toArray()])
            ->log('Creó un pago');
    }

    /**
     * Handle the Contact "updated" event.
     */
    public function updated(Pay $pay): void
    {
         activity()
            ->performedOn($pay)
            ->causedBy($this->userService->getUserLoggedIn())
            ->withProperties([
                'old' => $pay->getOriginal(),
                'new' => $pay->getChanges()
            ])
            ->log('Actualizó un pago');
    }

    /**
     * Handle the Contact "deleted" event.
     */
    public function deleted(Pay $pay): void
    {
         activity()
            ->performedOn($pay)
            ->causedBy($this->userService->getUserLoggedIn())
            ->withProperties(['attributes' => $pay->toArray()])
            ->log('Eliminó un pago');
    }
    
}
