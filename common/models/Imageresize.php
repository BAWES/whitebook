<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%image_resize}}".
 *
 * @property integer $id
 * @property integer $logo_width
 * @property integer $logo_height
 * @property integer $favicon_width
 * @property integer $favicon_height
 * @property integer $noimage_width
 * @property integer $noimage_height
 * @property integer $item_cart_width
 * @property integer $item_cart_height
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Imageresize extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image_resize}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['logo_width', 'logo_height','item_list_width','item_list_height','item_detail_width','item_detail_height','item_cart_width','item_cart_height'], 'required'],
            [['logo_width', 'logo_height','item_list_width','item_list_height','item_detail_width','item_detail_height','item_cart_width','item_cart_height'], 'integer'],
            [['created_by', 'modified_by','created_datetime', 'modified_datetime'], 'safe'],
            [['trash'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'logo_width' => 'Logo width (Pixels)',
            'logo_height' => 'Logo height (Pixels)', 
            'item_list_width' =>'Item list width (Pixels)',
            'item_list_height' =>'Item list height (Pixels)',          
            'item_detail_width' =>'Item detail width (Pixels)',
            'item_detail_height' => 'Item detail height (Pixels)',
			'item_cart_width' =>'Item cart width (Pixels)',
            'item_cart_height' => 'Item cart height (Pixels)',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }
}
