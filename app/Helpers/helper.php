<?php

function routeWithPreview($name, $parameters = [])
{
    $route = route($name, $parameters);
    if (env('MAINTENANCE_MODE') === 'true') {
        return $route . '?preview=' . env('MAINTENANCE_TOKEN');
    }
    return $route;
}
