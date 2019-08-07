<?php

namespace Ximdex\Providers;

use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;

class StorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Extends the laravel storage , adding a new driver
        Storage::extend('dropbox', function ($app, $config) {
            $client = new DropboxClient(
                $config['auth_token']
            );

            $dropbox = new DropboxAdapter($client);

            return new Filesystem($dropbox);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
