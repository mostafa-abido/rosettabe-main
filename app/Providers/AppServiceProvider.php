<?php

namespace App\Providers;

use App\Actions\NotifyUserWithCredsAction;
use App\Actions\ForceDeleteUser;
use App\Actions\BatchApproveAction;
use App\Actions\ViewAttachmentsAction;
use App\Actions\ViewCommentsAction;
use App\Actions\ViewMessagesAction;
use App\Actions\ViewNotesAction;
use App\Actions\ViewResponsesAction;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Voyager::addAction(NotifyUserWithCredsAction::class);
      Voyager::addAction(ForceDeleteUser::class);
      Voyager::addAction(BatchApproveAction::class);

      // Voyager::addAction(ViewAttachmentsAction::class);
      Voyager::addAction(ViewCommentsAction::class);
      Voyager::addAction(ViewMessagesAction::class);
      Voyager::addAction(ViewNotesAction::class);
      Voyager::addAction(ViewResponsesAction::class);

      User::observe(UserObserver::class);
    }
}
