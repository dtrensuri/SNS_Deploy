<?php

namespace App\Providers\Facebook;

// Create a new file, e.g., FacebookSdkServiceProvider.php

use Illuminate\Support\ServiceProvider;

class FacebookSdkServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        spl_autoload_register([$this, 'autoload']);
    }

    /**
     * Autoload Facebook SDK classes.
     *
     * @param string $class The fully-qualified class name.
     *
     * @return void
     */
    public function autoload($class)
    {
        $prefix = 'Facebook\\';
        $baseDir = __DIR__ . '/'; // Adjust the base directory as needed

        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }

        $relativeClass = substr($class, $len);
        $file = rtrim($baseDir, '/') . '/' . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require $file;
        }
    }
}
