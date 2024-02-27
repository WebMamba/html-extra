<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\Extra\Html\Tests;

use PHPUnit\Framework\TestCase;
use Twig\Extra\Html\CVA;

/**
 * @author Mathéo Daninos <matheo.daninos@gmail.com>
 */
class CVATest extends TestCase
{
    /**
     * @dataProvider recipeProvider
     */
    public function testRecipes(array $recipe, array $recipes, string $expected): void
    {
        $recipeClass = new CVA($recipe['base'] ?? '', $recipe['variants'] ?? [], $recipe['compounds'] ?? []);

        $this->assertEquals($expected, $recipeClass->resolve($recipes));
    }

    public static function recipeProvider(): iterable
    {
        yield 'base null' => [
            ['variants' => [
                'colors' => [
                    'primary' => 'text-primary',
                    'secondary' => 'text-secondary',
                ],
                'sizes' => [
                    'sm' => 'text-sm',
                    'md' => 'text-md',
                    'lg' => 'text-lg',
                ],
            ]],
            ['colors' => 'primary', 'sizes' => 'sm'],
            'text-primary text-sm',
        ];

        yield 'base empty' => [
            [
                'base' => '',
                'variants' => [
                    'colors' => [
                        'primary' => 'text-primary',
                        'secondary' => 'text-secondary',
                    ],
                    'sizes' => [
                        'sm' => 'text-sm',
                        'md' => 'text-md',
                        'lg' => 'text-lg',
                    ],
                ]],
            ['colors' => 'primary', 'sizes' => 'sm'],
            'text-primary text-sm',
        ];

        yield 'no recipes match' => [
            [
                'base' => 'font-semibold border rounded',
                'variants' => [
                    'colors' => [
                        'primary' => 'text-primary',
                        'secondary' => 'text-secondary',
                    ],
                    'sizes' => [
                        'sm' => 'text-sm',
                        'md' => 'text-md',
                        'lg' => 'text-lg',
                    ],
                ],
            ],
            ['colors' => 'red', 'sizes' => 'test'],
            'font-semibold border rounded',
        ];

        yield 'simple variants' => [
            [
                'base' => 'font-semibold border rounded',
                'variants' => [
                    'colors' => [
                        'primary' => 'text-primary',
                        'secondary' => 'text-secondary',
                    ],
                    'sizes' => [
                        'sm' => 'text-sm',
                        'md' => 'text-md',
                        'lg' => 'text-lg',
                    ],
                ],
            ],
            ['colors' => 'primary', 'sizes' => 'sm'],
            'font-semibold border rounded text-primary text-sm',
        ];

        yield 'simple variants with custom' => [
            [
                'base' => 'font-semibold border rounded',
                'variants' => [
                    'colors' => [
                        'primary' => 'text-primary',
                        'secondary' => 'text-secondary',
                    ],
                    'sizes' => [
                        'sm' => 'text-sm',
                        'md' => 'text-md',
                        'lg' => 'text-lg',
                    ],
                ],
            ],
            ['colors' => 'secondary', 'sizes' => 'md'],
            'font-semibold border rounded text-secondary text-md',
        ];

        yield 'compound variants' => [
            [
                'base' => 'font-semibold border rounded',
                'variants' => [
                    'colors' => [
                        'primary' => 'text-primary',
                        'secondary' => 'text-secondary',
                    ],
                    'sizes' => [
                        'sm' => 'text-sm',
                        'md' => 'text-md',
                        'lg' => 'text-lg',
                    ],
                ],
                'compounds' => [
                    [
                        'colors' => ['primary'],
                        'sizes' => ['sm'],
                        'class' => 'text-red-500',
                    ],
                ],
            ],
            ['colors' => 'primary', 'sizes' => 'sm'],
            'font-semibold border rounded text-primary text-sm text-red-500',
        ];

        yield 'multiple compound variants' => [
            [
                'base' => 'font-semibold border rounded',
                'variants' => [
                    'colors' => [
                        'primary' => 'text-primary',
                        'secondary' => 'text-secondary',
                    ],
                    'sizes' => [
                        'sm' => 'text-sm',
                        'md' => 'text-md',
                        'lg' => 'text-lg',
                    ],
                ],
                'compounds' => [
                    [
                        'colors' => ['primary'],
                        'sizes' => ['sm'],
                        'class' => 'text-red-500',
                    ],
                    [
                        'colors' => ['primary'],
                        'sizes' => ['md'],
                        'class' => 'text-blue-500',
                    ],
                ],
            ],
            ['colors' => 'primary', 'sizes' => 'sm'],
            'font-semibold border rounded text-primary text-sm text-red-500',
        ];

        yield 'compound with multiple variants' => [
            [
                'base' => 'font-semibold border rounded',
                'variants' => [
                    'colors' => [
                        'primary' => 'text-primary',
                        'secondary' => 'text-secondary',
                    ],
                    'sizes' => [
                        'sm' => 'text-sm',
                        'md' => 'text-md',
                        'lg' => 'text-lg',
                    ],
                ],
                'compounds' => [
                    [
                        'colors' => ['primary', 'secondary'],
                        'sizes' => ['sm'],
                        'class' => 'text-red-500',
                    ],
                ],
            ],
            ['colors' => 'primary', 'sizes' => 'sm'],
            'font-semibold border rounded text-primary text-sm text-red-500',
        ];

        yield 'compound doesn\'t match' => [
            [
                'base' => 'font-semibold border rounded',
                'variants' => [
                    'colors' => [
                        'primary' => 'text-primary',
                        'secondary' => 'text-secondary',
                    ],
                    'sizes' => [
                        'sm' => 'text-sm',
                        'md' => 'text-md',
                        'lg' => 'text-lg',
                    ],
                ],
                'compounds' => [
                    [
                        'colors' => ['danger', 'secondary'],
                        'sizes' => ['sm'],
                        'class' => 'text-red-500',
                    ],
                ],
            ],
            ['colors' => 'primary', 'sizes' => 'sm'],
            'font-semibold border rounded text-primary text-sm',
        ];
    }
}
