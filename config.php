<?php

return [
    'id' => 'limitsummarymail',
    'class' => 'humhub\modules\limitsummarymail\Module',
    'namespace' => 'humhub\modules\limitsummarymail',
    'events' => [
        [
            'class' => \yii\base\Application::class,
            'event' => \yii\base\Application::EVENT_BEFORE_REQUEST,
            'callback' => ['humhub\modules\limitsummarymail\Events', 'onBeforeRequest']
        ],
    ],
    'urlManagerRules' => [
        'limitsummarymail/admin' => '/limitsummarymail/admin/index',
    ],
];
