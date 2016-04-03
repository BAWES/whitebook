<?php
namespace backend\models;
use backend\models\Addresstype;
use Yii;

/**
 * This is the model class for table "whitebook_address_question".
 *
 * @property integer $ques_id
 * @property integer $address_type_id
 * @property string $question
 * @property string $status
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_date
 * @property string $modified_date
 * @property string $trash
 */
class AddressQuestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_address_question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'address_type_id'], 'required'],
            [['ques_id', 'address_type_id', 'created_by', 'modified_by'], 'integer'],
            [['status', 'trash'], 'string'],
            [['created_datetime', 'modified_datetime'], 'safe'],
          //  [['question'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ques_id' => 'Ques ID',
            'address_type_id' => 'Address Type	',
            'question' => 'Question',
            'status' => 'Status',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Date',
            'modified_datetime' => 'Modified Date',
            'trash' => 'Trash',
        ];
    }
    
    	public static function  getAddresstype($id)
    {		
		$model = Addresstype::find()->where(['type_id'=>$id])->one();
        return $model->type_name;
    }
    
        	public static function  loadAddressquestion($addresstypeid)
    {		
		$question = AddressQuestion::find()
		->select(['ques_id','address_type_id','question'])
		->where(['address_type_id'=>$addresstypeid])->all();
       return $question;
    }
    
       public static function  loadquestion($addresstypeid)
    {		
		$question = AddressQuestion::find()
		->select(['question'])
		->where(['address_type_id'=>$addresstypeid])->all();		
		foreach ($question as $q)
		{
			$ques[]=$q['question'];			
		}		
		$ques=implode ('<br>',$ques);
		return($ques);
    }
        public static function statusImageurl($img_status)
	{
		if($img_status == 'Active')		
		return \Yii::$app->urlManagerBackEnd->createAbsoluteUrl('themes/default/img/active.png');
		return \Yii::$app->urlManagerBackEnd->createAbsoluteUrl('themes/default/img/inactive.png');
	}
}
