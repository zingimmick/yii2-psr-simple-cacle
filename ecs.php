<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Zing\CodingStandard\Set\ECSSetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->sets([ECSSetList::PHP_54, ECSSetList::CUSTOM]);

    $ecsConfig->parallel();
    $ecsConfig->skip([
        \SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff::class,
        \PhpCsFixer\Fixer\FunctionNotation\NullableTypeDeclarationForDefaultNullValueFixer::class,

        \SlevomatCodingStandard\Sniffs\ControlStructures\RequireNullCoalesceOperatorSniff::class,
        \SlevomatCodingStandard\Sniffs\TypeHints\NullableTypeForNullDefaultValueSniff::class,
    ]);
    $ecsConfig->paths([__DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/ecs.php', __DIR__ . '/rector.php']);
};
