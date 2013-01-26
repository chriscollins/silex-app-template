<?php

// Configuration for the production environment.

$app['cache.path'] = __DIR__ . '/../../cache';

$app['http_cache.cache_dir'] = $app['cache.path'] . '/http';

// Twig.
$app['twig.path'] = __DIR__ . '/../views';
$app['twig.options.cache'] = $app['cache.path'] . '/twig';

// Assetic.
$app['assetic.enabled'] = true;
$app['assetic.path_to_cache'] = $app['cache.path'] . '/assetic' ;
$app['assetic.path_to_web'] = __DIR__ . '/../../web';
$app['assetic.input.path_to_assets'] = __DIR__ . '/../assets';

$app['assetic.input.path_to_css'] = $app['assetic.input.path_to_assets'] . '/css/*.css';
$app['assetic.input.path_to_scss'] = $app['assetic.input.path_to_assets'] . '/scss/*.scss';
$app['assetic.output.path_to_css'] = '/assets/css/all.css';


$app['assetic.input.path_to_js'] = $app['assetic.input.path_to_assets'] . '/js/*.js';
$app['assetic.output.path_to_js'] = '/assets/js/all.js';

$app['assetic.binaries.path_to_ruby'] = 'C:\Ruby193\bin\ruby.exe';
$app['assetic.binaries.path_to_sass'] = 'C:\Ruby193\bin\sass';

$app['settings'] = function () {
    return array(
        'site_name' => 'Site Name',
        'main_domain' => 'test.com',
        'contact_email' => 'test@test.com'
    );
};