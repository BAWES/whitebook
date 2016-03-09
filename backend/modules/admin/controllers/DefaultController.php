<?php

namespace backend\modules\admin\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect(['site/login']);
    }

    public function actionError()
    {
        return $this->redirect(['site/error']);
    }
}
