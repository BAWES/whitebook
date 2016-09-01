<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_order_status".
 *
 * @property integer $order_status_id
 * @property string $name
 * @property string $name_ar
 */
class OrderStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_order_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_ar'], 'required'],
            [['name', 'name_ar'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_status_id' => 'Order Status ID',
            'name' => 'Name',
            'name_ar' => 'Name - Arabic',
        ];
    }

    //return array for form dropdown list 
    public function get_dpdwn_list(){

        $results = [];

        foreach(OrderStatus::find()->all() as $row){
            
            if(Yii::$app->language == 'en') {
                $results[$row->order_status_id] = $row->name;
            }else{
                $results[$row->order_status_id] = $row->name_ar;
            }
        }

        return $results;
    }
}
