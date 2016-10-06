<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Vendor;

class DirectoryController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Directory';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

        if(Yii::$app->language == "en") {
            $sort = 'vendor_name';
        } else {
            $sort = 'vendor_name_ar';
        }


        $today = date('Y-m-d H:i:s');
        $query = Vendor::find()
            ->leftJoin('{{%vendor_packages}}', '{{%vendor}}.vendor_id = {{%vendor_packages}}.vendor_id')
            ->leftJoin('{{%vendor_category}}', '{{%vendor}}.vendor_id = {{%vendor_category}}.vendor_id')
            ->where(['<=','{{%vendor_packages}}.package_start_date', $today])
            ->andWhere(['>=','{{%vendor_packages}}.package_end_date', $today])
            ->andWhere(['{{%vendor}}.trash'=>'Default'])
            ->andWhere(['{{%vendor}}.approve_status'=>'Yes'])
            ->andWhere(['{{%vendor}}.vendor_status'=>'Active']);

            # for filteration
            if (Yii::$app->request->isAjax) {
                $category_id = Yii::$app->request->post('slug');
                if ($category_id != 'All') {
                    $query->andWhere(['{{%vendor_category}}.category_id' => $category_id]);
                }
            }

            $directory = $query->orderby(['{{%vendor}}.'.$sort => SORT_ASC])
            ->groupby(['{{%vendor}}.vendor_id'])
            ->asArray()
            ->all();

        $prevLetter = '';

        $result = array();

        foreach ($directory as $d) {

            if(Yii::$app->language == "en") {
                $firstLetter = mb_substr($d['vendor_name'], 0, 1, 'utf8');
            }else{
                $firstLetter = mb_substr($d['vendor_name_ar'], 0, 1, 'utf8');
            }

            if ($firstLetter != $prevLetter) {
                $result[] = strtoupper($firstLetter);
            }

            $prevLetter = $firstLetter;
        }

        $result = array_unique($result);


        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request;
            if ($request->post('ajaxdata') == 0) {

                return $this->renderPartial('_listing', [
                    'directory' => $directory,
                    'first_letter' => $result,
                ]);

            } else {

                return $this->renderPartial('_m_listing', [
                    'directory' => $directory,
                    'first_letter' => $result
                ]);
            }
        }

        return $this->render('index', [
            'directory' => $directory,
            'first_letter' => $result,
        ]);
    }
}



