<?php

namespace humhub\modules\limitsummarymail;

use Yii;
use humhub\components\Module as BaseModule;

class Module extends BaseModule
{
    public $resourcesPath = 'resources';

    const DEFAULT_CHAR_LIMIT = 500;
    const MIN_CHAR_LIMIT = 1;
    const MAX_CHAR_LIMIT = 500;
    const SETTING_CHAR_LIMIT = 'charLimit';
    const SETTING_ENABLED = 'enabled';

    public function disable()
    {
        $this->settings->delete(self::SETTING_CHAR_LIMIT);
        $this->settings->delete(self::SETTING_ENABLED);
        parent::disable();
    }

    public function getCharLimit()
    {
        $limit = $this->settings->get(self::SETTING_CHAR_LIMIT);
        
        if ($limit === null) {
            return self::DEFAULT_CHAR_LIMIT;
        }

        return max(self::MIN_CHAR_LIMIT, min(self::MAX_CHAR_LIMIT, (int)$limit));
    }

    public function setCharLimit($limit)
    {
        $limit = max(self::MIN_CHAR_LIMIT, min(self::MAX_CHAR_LIMIT, (int)$limit));
        $this->settings->set(self::SETTING_CHAR_LIMIT, $limit);
    }

    public function getConfigUrl()
    {
        return ['/limitsummarymail/admin/index'];
    }
}
