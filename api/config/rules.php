<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'city',
        'except' => ['view', 'create', 'update', 'delete']
    ],
];