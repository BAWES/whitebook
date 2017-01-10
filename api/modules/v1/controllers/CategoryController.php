<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use \common\models\Category;
/**
 * Auth controller provides the initial access token that is required for further requests
 * It initially authorizes via Http Basic Auth using a base64 encoded username and password
 */
class CategoryController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();

        // Return Header explaining what options are available for next request
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }


    /**
     * Perform validation on the agent account (check if he's allowed login to platform)
     * If everything is alright,
     * Returns the BEARER access token required for futher requests to the API
     * @return array
     */
    public function actionIndex()
    {
        return Category::find()
            ->select(['category_id','category_name','category_name_ar','icon'])
            ->where([
                'category_level' => 1,
                'trash' => 'Default'
            ])->orderby('sort asc')
            ->all();
    }
}
