<?php

namespace Xserg\LaravelMailopost\Mail;

use Illuminate\Mail\MailManager;
use Xserg\LaravelMailopost\Mail\MailopostTransport;


class MailopostMailManager extends MailManager
{
    protected function createMailopostTransport()
    {
        $config = $this->app['config']->get('services.mailopost_mail', []);

        return new MailopostTransport(
            $config['key'], $config['url']
        );
    }
}
