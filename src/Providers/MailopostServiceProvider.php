<?php

namespace Xserg\LaravelMailopost\Providers;

use Illuminate\Mail\MailServiceProvider;
use Xserg\LaravelMailopost\Mail\MailopostMailManager;

class MailopostServiceProvider extends MailServiceProvider
{
    /**
     * Register the Illuminate mailer instance.
     *
     * @return void
     */
    protected function registerIlluminateMailer()
    {
        $this->app->singleton('mail.manager', function($app) {
            return new MailopostMailManager($app);
        });

        // Copied from Illuminate\Mail\MailServiceProvider
        $this->app->bind('mailer', function ($app) {
            return $app->make('mail.manager')->mailer();
        });
    }

}
