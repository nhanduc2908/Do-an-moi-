<?php

return [
    'domains' => [
        ['name' => 'Access Control', 'code' => 'AC', 'weight' => 15],
        ['name' => 'Cryptography', 'code' => 'CR', 'weight' => 10],
        ['name' => 'Physical Security', 'code' => 'PS', 'weight' => 10],
        ['name' => 'Network Security', 'code' => 'NS', 'weight' => 15],
        ['name' => 'Application Security', 'code' => 'AS', 'weight' => 15],
        ['name' => 'Incident Response', 'code' => 'IR', 'weight' => 10],
        ['name' => 'Compliance', 'code' => 'CM', 'weight' => 15],
        ['name' => 'Risk Management', 'code' => 'RM', 'weight' => 10],
    ],
    'scoring' => ['method' => 'weighted_average', 'passing_threshold' => 70],
];