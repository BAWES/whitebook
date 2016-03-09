<?php
namespace common\components;
 
 
use Yii;
use yii\base\Component;
use frontend\models\Website;
 
class Common extends Component
{
	public $website_model;
	public function init(){
		Yii::$app->language = 'en-EN';
		$this->website_model = new Website();
	}
	// GET SEO DATA
	public function SEOdata($table_name='',$field='',$value='',$data=''){ 
		if(is_array($data)){
			$select = implode(',',$data);
		}
		if($table_name && $field && $value && $data){
			return $this->website_model->getSEOdata($table_name,$field,$value,$select);
		}else{
			return;
		}
	}
}
	
	
?>
