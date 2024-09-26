<?php
$finder = (new PhpCsFixer\Finder())
    ->exclude(['.git', '.docker', 'vendor', 'var', 'node_modules','tools','bin'])
    ->ignoreUnreadableDirs()
    ->in(__DIR__);
return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        '@PER-CS' => true,
        '@PHP82Migration' => true,
        '@Symfony' => true,
    ])
    ->setFinder($finder)
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
;