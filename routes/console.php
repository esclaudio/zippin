<?php

use App\Mail\TestMail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Artisan::command('app:send-test-mail {email}', function () {
    Mail::to($this->argument('email'))->send(new TestMail);
})->purpose('Send test mail');
