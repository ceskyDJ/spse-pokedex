<?php

declare(strict_types = 1);

namespace App\Routing;

use App\Exceptions\PageNotFoundException;
use function file_exists;
use function header;
use function trim;

/**
 * Class Router
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Routing
 */
class Router
{
    /**
     * Loads specified URL
     *
     * @param string $url URL for loading
     *
     * @return string File for including
     * @throws \App\Exceptions\PageNotFoundException Bad URL, no page found
     */
    public function loadUrl(string $url): string
    {
        // Remove unnecessary slashes
        $url = trim($url, "/");

        // Fix for main page (no path in URL)
        $url = $url === "" ? "home" : $url;
        $url = $url === "admin" ? "admin/home" : $url;

        // TODO: Do it better, please...
        if(file_exists("{$url}.php")) {
            return "{$url}.php";
        } else {
            throw new PageNotFoundException("Page with this URL isn't exists.");
        }
    }

    /**
     * Routes to specific URL
     *
     * @param string $url URL
     */
    public function route(string $url): void
    {
        header("Location: /$url");
        exit;
    }

    /**
     * Refreshes page
     *
     * @param int $delay Delay (in seconds)
     */
    public function refresh(int $delay = 0): void
    {
        header("Refresh:{$delay}");
    }
}