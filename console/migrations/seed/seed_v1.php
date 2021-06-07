<?php
return [
    'api_teacher_modules' => [
        'name' => 'rewards',
        'title' => 'Quà tặng',
        'description' => 'Module Quà tặng',
        'icon' => 'reward_module',
        'level' => 1,
        'sequence' => 15,
        'route' => '/rewards/*',
        'package' => ['premium', 'standard', 'basic'],
    ],
];