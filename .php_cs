<?php

$header = <<<EOF
EOF;

//Symfony\CS\Fixer\Contrib\HeaderCommentFixer::setHeader($header);

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('bin')
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony'              => true,
        '@PSR2'                 => true,
        '@PSR1'                 => true,
        'short_array_syntax'    => true,
        'ordered_imports'       => true,
        'strict_comparison'     => true,
        'strict_param'          => true,
        'phpdoc_order'          => true,
        'no_useless_return'     => true,
        'ereg_to_preg'          => true,
        'concat_with_spaces'    => true,
        'concat_without_spaces' => false,
        'align_double_arrow'    => true,
        'align_equals'          => true
    ])
    //->setUsingLinter(false)
    ->finder($finder)
    ->setUsingCache(true);
