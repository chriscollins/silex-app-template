<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Assetic\AssetWriter;
use Assetic\Extension\Twig\TwigFormulaLoader;
use Assetic\Extension\Twig\TwigResource;
use Assetic\Factory\LazyAssetManager;

$console = new Application('Silex Portfolio', '0.1');

$console->register('assetic:dump')
    ->setDescription('Dumps all assets to the filesystem.')
    ->setCode(
        function (InputInterface $input, OutputInterface $output) use ($app) {
            $dumper = $app['assetic.dumper'];
            if (isset($app['twig'])) {
                $dumper->addTwigAssets();
            }
            $dumper->dumpAssets();
            $output->writeln('<info>Dump finished</info>');
        }
    );

return $console;
