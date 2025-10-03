<?php

namespace App\Observers;

use App\Models\Debt;
use App\Models\Transaction;
use App\Services\UserService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
class DebtObserver implements  ShouldHandleEventsAfterCommit
{
    public function __construct(protected UserService $userService)
    {
    }
    /**
     * Handle the Contact "created" event.
     */
    public function created(Debt $debt): void
    {
        activity()
            ->performedOn($debt)
            ->causedBy($this->userService->getUserLoggedIn())
            ->withProperties(['attributes' => $debt->toArray()])
            ->log('Creó una deuda');
    }

    /**
     * Handle the Contact "updated" event.
     */
    public function updated(Debt $debt): void
    {
         activity()
            ->performedOn($debt)
            ->causedBy($this->userService->getUserLoggedIn())
            ->withProperties([
                'old' => $debt->getOriginal(),
                'new' => $debt->getChanges()
            ])
            ->log('Actualizó una deuda');
    }

    /**
     * Handle the Contact "deleted" event.
     */
    public function deleted(Debt $debt): void
    {
         activity()
            ->performedOn($debt)
            ->causedBy($this->userService->getUserLoggedIn())
            ->withProperties(['attributes' => $debt->toArray()])
            ->log('Eliminó una deuda');
    }
    
}
