<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Services\UserService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
class TransactionObserver implements  ShouldHandleEventsAfterCommit
{
    public function __construct(protected UserService $userService)
    {
    }
    /**
     * Handle the Contact "created" event.
     */
    public function created(Transaction $transaction): void
    {
        activity()
            ->performedOn($transaction)
            ->causedBy($this->userService->getUserLoggedIn())
            ->withProperties(['attributes' => $transaction->toArray()])
            ->log('Creó una transacción');
    }

    /**
     * Handle the Contact "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
         activity()
            ->performedOn($transaction)
            ->causedBy($this->userService->getUserLoggedIn())
            ->withProperties([
                'old' => $transaction->getOriginal(),
                'new' => $transaction->getChanges()
            ])
            ->log('Actualizó una transacción');
    }

    /**
     * Handle the Contact "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
         activity()
            ->performedOn($transaction)
            ->causedBy($this->userService->getUserLoggedIn())
            ->withProperties(['attributes' => $transaction->toArray()])
            ->log('Eliminó una transacción');
    }
    
}
