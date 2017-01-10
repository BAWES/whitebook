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
     * @return array
     */
    public function actionCategoryListing()
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
