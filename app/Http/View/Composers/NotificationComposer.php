<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class NotificationComposer
{
    protected $notifService;

    // Dependency Injection
    public function __construct(NotificationService $notifService)
    {
        $this->notifService = $notifService;
    }

    /**
     * Bind data ke view.
     */
    public function compose(View $view)
    {
        if (Auth::check()) {
            $notifs = $this->notifService->getNotificationsForUser(Auth::user());

            $view->with([
                'globalNotifs' => $notifs,
                'globalNotifCount' => $notifs->count()
            ]);
        }
    }
}
