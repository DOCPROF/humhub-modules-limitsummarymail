<?php

namespace humhub\modules\limitsummarymail;

use Yii;
use yii\base\Event;
use yii\base\View;

class Events
{
    private static $registered = false;

    public static function onBeforeRequest($event)
    {
        if (self::$registered) {
            return;
        }
        self::$registered = true;

        Yii::info('LimitSummaryMail: Registering view interceptor', 'limitsummarymail');

        Event::on(View::class, View::EVENT_AFTER_RENDER, [self::class, 'onAfterRenderView']);
        
        Yii::info('LimitSummaryMail: View interceptor registered', 'limitsummarymail');
    }

    public static function onAfterRenderView($event)
    {
        $viewFile = $event->viewFile ?? '';
        
        Yii::info('LimitSummaryMail: View rendered - ' . $viewFile, 'limitsummarymail');

        $isMailView = (strpos($viewFile, '/mail/') !== false || 
                      strpos($viewFile, '\\mail\\') !== false ||
                      strpos($viewFile, 'views/mail') !== false);
        
        $isActivityView = (strpos($viewFile, 'activity') !== false ||
                          strpos($viewFile, 'summary') !== false);
        
        if (!$isMailView && !$isActivityView) {
            return;
        }

        Yii::info('LimitSummaryMail: Detected mail/activity view', 'limitsummarymail');

        $module = Yii::$app->getModule('limitsummarymail');
        if (!$module) {
            Yii::error('LimitSummaryMail: Module not found', 'limitsummarymail');
            return;
        }

        $html = $event->output;
        if (empty($html)) {
            Yii::warning('LimitSummaryMail: Empty HTML in view', 'limitsummarymail');
            return;
        }

        Yii::info('LimitSummaryMail: Processing view, HTML length: ' . strlen($html), 'limitsummarymail');

        $charLimit = $module->getCharLimit();
        $processor = new components\MailSummaryProcessor($charLimit);
        $processedHtml = $processor->processHtmlContent($html);

        if ($processedHtml !== $html) {
            $event->output = $processedHtml;
            Yii::info('LimitSummaryMail: View HTML modified successfully', 'limitsummarymail');
        } else {
            Yii::warning('LimitSummaryMail: View HTML not modified', 'limitsummarymail');
        }
    }
}
