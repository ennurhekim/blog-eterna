<?php

use App\Console\Commands\DeleteOldBlogs;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('app:delete-old-blogs', function () {
    (new DeleteOldBlogs())->handle(); // tomorrow
})->everyMinute();