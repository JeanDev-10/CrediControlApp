<?php

namespace App\Observers;

use App\Models\Contact;
use App\Services\UserService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
class ContactObserver implements  ShouldHandleEventsAfterCommit
{
    public function __construct(protected UserService $userService)
    {
    }
    /**
     * Handle the Contact "created" event.
     */
    public function created(Contact $contact): void
    {
        activity()
            ->performedOn($contact)
            ->causedBy($this->userService->getUserLoggedIn())
            ->withProperties(['attributes' => $contact->toArray()])
            ->log('Creó un contacto');
    }

    /**
     * Handle the Contact "updated" event.
     */
    public function updated(Contact $contact): void
    {
         activity()
            ->performedOn($contact)
            ->causedBy($this->userService->getUserLoggedIn())
            ->withProperties([
                'old' => $contact->getOriginal(),
                'new' => $contact->getChanges()
            ])
            ->log('Actualizó un contacto');
    }

    /**
     * Handle the Contact "deleted" event.
     */
    public function deleted(Contact $contact): void
    {
         activity()
            ->performedOn($contact)
            ->causedBy($this->userService->getUserLoggedIn())
            ->withProperties(['attributes' => $contact->toArray()])
            ->log('Eliminó un contacto');
    }
    /**
     * Handle the Contact "restored" event.
     */
    public function restored(Contact $contact): void
    {
        //
    }

    /**
     * Handle the Contact "force deleted" event.
     */
    public function forceDeleted(Contact $contact): void
    {
        //
    }
}
