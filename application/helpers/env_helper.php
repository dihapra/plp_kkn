<?php

// if (!function_exists('load_env')) {
//     /**
//      * Memuat variabel lingkungan dari file .env
//      */
//     function load_env()
//     {
//         if (!file_exists(FCPATH . '.env')) {
//             return;
//         }

//         $env = parse_ini_file(FCPATH . '.env', true);

//         foreach ($env as $key => $value) {
//             putenv("$key=$value");
//             $_ENV[$key] = $value;
//             $_SERVER[$key] = $value;
//         }
//     }
// }


if (!function_exists('load_env')) {
    /**
     * Memuat variabel lingkungan dari file .env
     * Support 2 lokasi:
     * - DEV: di root project (setara index.php)
     * - PROD: di atas folder webapp
     */
    function load_env()
    {
        // Lokasi dev (root project)
        $envDev = FCPATH . '.env';
        // Lokasi prod (satu tingkat di atas root project)
        $envProd = dirname(FCPATH) . '/.env';

        $envPath = null;
        if (file_exists($envProd)) {
            $envPath = $envProd; // Production
        } elseif (file_exists($envDev)) {
            $envPath = $envDev;  // Development
        }

        if ($envPath) {
            $env = parse_ini_file($envPath, true);
            foreach ($env as $key => $value) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}
