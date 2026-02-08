<?php

namespace humhub\modules\limitsummarymail\controllers;

use Yii;
use humhub\modules\admin\components\Controller;
use humhub\modules\limitsummarymail\models\ConfigureForm;

/**
 * AdminController
 */
class AdminController extends Controller
{
    public $adminOnly = true;
    public $subLayout = "@humhub/modules/admin/views/layouts/main";

    public function actionIndex()
    {
        $model = new ConfigureForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('LimitsummarymailModule.base', 'Settings saved successfully!'));
            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
