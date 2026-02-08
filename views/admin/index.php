<?php

use humhub\modules\limitsummarymail\models\ConfigureForm;
use yii\bootstrap\ActiveForm;
use humhub\libs\Html;

/* @var $this yii\web\View */
/* @var $model ConfigureForm */

$this->title = Yii::t('LimitsummarymailModule.base', 'Limit Summary Mail Settings');
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <strong><?= Html::encode($this->title) ?></strong>
    </div>
    <div class="panel-body">
        <p>
            <?= Yii::t('LimitsummarymailModule.base', 'Configure the maximum number of characters to display in summary emails. Content exceeding this limit will be truncated with "..."') ?>
        </p>
        
        <br>

        <?php $form = ActiveForm::begin(['id' => 'configure-form']); ?>

        <div class="form-group">
            <?= $form->field($model, 'enabled')->checkbox() ?>
        </div>

        <div class="form-group" id="charlimit-field" style="<?= $model->enabled ? '' : 'display:none;' ?>">
            <?= $form->field($model, 'charLimit')
                ->textInput([
                    'type' => 'number',
                    'min' => 1,
                    'max' => 500,
                    'class' => 'form-control',
                ])
                ->hint(Yii::t('LimitsummarymailModule.base', 'Enter a value between 1 and 500 characters'));
            ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton(
                Yii::t('LimitsummarymailModule.base', 'Save'), 
                ['class' => 'btn btn-primary', 'data-ui-loader' => '']
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$this->registerJs('
    $("#configureform-enabled").on("change", function() {
        if ($(this).is(":checked")) {
            $("#charlimit-field").show();
        } else {
            $("#charlimit-field").hide();
        }
    });
');
?>
