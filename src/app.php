<?php

use Silex\Application;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use SilexAssetic\AsseticExtension;

$app->register(new FormServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new SwiftmailerServiceProvider());
$app->register(
    new TranslationServiceProvider(),
    array(
        'translator.messages' => array(),
    )
);
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(
    new TwigServiceProvider(),
    array(
        'twig.path'    => array($app['twig.path']),
        'twig.options' => array('cache' => $app['twig.options.cache']),
        'twig.form.templates'   => array('form_div_layout.html.twig', 'common/form_div_layout.html.twig'),
    )
);


$app['twig'] = $app->share(
    $app->extend(
        'twig',
        function ($twig, $app) {
            // Add custom globals, filters, tags.
            return $twig;
        }
    )
);

$app->register(
    new AsseticExtension(),
    array(
        'assetic.class_path' => __DIR__ . '/vendor/assetic/src',
        'assetic.path_to_web' => $app['assetic.path_to_web'],
        'assetic.options' => array(
            'auto_dump_assets' => $app['debug'],
            'debug' => $app['debug']
        ),
        'assetic.filters' => $app->protect(
            function ($fm) use ($app) {
                $fm->set(
                    'scss',
                    new Assetic\Filter\Sass\ScssFilter(
                        $app['assetic.binaries.path_to_sass'],
                        $app['assetic.binaries.path_to_ruby']
                    )
                );
                $fm->set('cssmin', new Assetic\Filter\CssMinFilter());
            }
        ),
        'assetic.assets' => $app->protect(
            function ($am, $fm) use ($app) {
                $styles = new Assetic\Asset\AssetCollection(
                    array(
                        new Assetic\Asset\GlobAsset($app['assetic.input.path_to_css']),
                        new Assetic\Asset\GlobAsset($app['assetic.input.path_to_scss'], array($fm->get('scss'))),
                    ),
                    array($fm->get('cssmin'))
                );

                $am->set('styles', $styles, new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache']));
                $am->get('styles')->setTargetPath($app['assetic.output.path_to_css']);

                $am->set(
                    'scripts',
                    new Assetic\Asset\AssetCache(
                        new Assetic\Asset\GlobAsset($app['assetic.input.path_to_js'], array()),
                        new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
                    )
                );
                $am->get('scripts')->setTargetPath($app['assetic.output.path_to_js']);
            }
        )
    )
);

// Dump Assetic assets if in debug mode.
if ($app['assetic.options']['auto_dump_assets']) {
    $dumper = $app['assetic.dumper'];
    if (isset($app['twig'])) {
        $dumper->addTwigAssets();
    }
    $dumper->dumpAssets();
}
