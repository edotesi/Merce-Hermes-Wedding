<?php

if (!function_exists('routeWithPreview')) {
    function routeWithPreview($name, $parameters = []) {
        $route = route($name, $parameters);

        // Si estamos en modo mantenimiento O ya hay un token válido en la URL actual
        if (env('MAINTENANCE_MODE') === 'true' ||
            request()->query('preview') === env('MAINTENANCE_TOKEN')) {
            return $route . '?preview=' . env('MAINTENANCE_TOKEN');
        }

        return $route;
    }
}
