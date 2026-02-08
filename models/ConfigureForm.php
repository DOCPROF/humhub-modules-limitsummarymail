<?php

namespace humhub\modules\limitsummarymail\models;

use Yii;
use yii\base\Model;

class ConfigureForm extends Model
{
    public $charLimit;
    public $enabled;

    public function rules()
    {
        return [
            [['charLimit'], 'required'],
            [['charLimit'], 'integer', 'min' => 1, 'max' => 500],
            [['enabled'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'charLimit' => Yii::t('LimitsummarymailModule.base', 'Character Limit'),
            'enabled' => Yii::t('LimitsummarymailModule.base', 'Enable truncation'),
        ];
    }

    public function attributeHints()
    {
        return [
            'charLimit' => Yii::t('LimitsummarymailModule.base', 'Number of characters to display before truncating (1-500)'),
            'enabled' => Yii::t('LimitsummarymailModule.base', 'Enable or disable content truncation in summary emails'),
        ];
    }

    public function init()
    {
        parent::init();
        
        $module = Yii::$app->getModule('limitsummarymail');
        if ($module) {
            $this->charLimit = $module->getCharLimit();
            $enabled = $module->settings->get('enabled');
            $this->enabled = ($enabled === null) ? true : (bool)$enabled;
        } else {
            $this->charLimit = 500;
            $this->enabled = true;
        }
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $module = Yii::$app->getModule('limitsummarymail');
        if ($module) {
            $module->setCharLimit($this->charLimit);
            $module->settings->set('enabled', $this->enabled ? 1 : 0);
            return true;
        }

        return false;
    }
}
