function routeWithPreview($name, $parameters = []) {
    $route = route($name, $parameters);
    if (request()->query('preview')) {
        return $route . '?preview=' . request()->query('preview');
    }
    return $route;
}
