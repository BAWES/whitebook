<?php
namespace backend\controllers;


use Yii;
use yii\base\Model;
use common\models\Vendoritem;
use common\models\Vendoritemthemes;
use common\models\Vendoritemquestion;
use common\models\Vendoritemquestionansweroption;
use common\models\Vendoritemquestionguide;
use common\models\Vendor;
use common\models\Image;
use common\models\Category;
use common\models\SubCategory;
use common\models\VendoritemSearch;
use common\models\Itemtype;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Themes;
use common\models\Featuregroup;
use common\models\Featuregroupitem;
use common\models\ChildCategory;
use common\models\Vendoritempricing;

/**
 * VendoritemController implements the CRUD actions for Vendoritem model.
 */
class VendoritemController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [//allow authenticated users only
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                //    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Vendoritem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendoritemSearch();
        $dataProvider = $searchModel->searchVendor(Yii::$app->request->queryParams);
        $vendr_id=Vendor::getVendor('vendor_id');
		$result= Vendor::find()
			->select(['category_id'])
			->where(['!=','vendor_id', $vendr_id])
			->asArray()
			->All();
        $out1[]= array();
        $out2[]= array();
        foreach ($result as $r)
        {
            if(is_numeric($r['category_id']))
            {
               $out1[]= $r['category_id'];
            }
            if(!is_numeric($r['category_id']))
            {
             $out2[]= explode(',',$r['category_id']);
            }
        }
        $p=array();
        foreach($out2 as $id)
        {
            foreach($id as $key)
            $p[] = $key;
        }
        $k=array();
        if(count ($out1)){
        foreach ($out1 as $o)
        {
            if(!empty($o)){
            $p[]=$o;
            }
        }
        }
        $res= "('" . implode("','", $p) . "')";
        $cat_id1= Category::find()
			->select(['category_id','category_name'])
			->where(['category_level' => '0'])
			->andwhere(['trash' => 'Default'])
			->andwhere(['IN', 'category_id', $res])
			->asArray()->all();
        $cat_val1[]=0;
        foreach($cat_id1 as $key=>$val)
        {
            $cat_val1[] = $val['category_id'];
        }
        $categories1=Category::find()->where(['category_id' => $cat_val1])->all();
        $category1=ArrayHelper::map($categories1,'category_id','category_name');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,'vendor_category'=>$category1,
        ]);
    }

    /**
     * Displays a single Vendoritem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
			$dataProvider1=Priorityitem::find()
			->select(['priority_level','priority_start_date','priority_end_date'])
			->where(new Expression('FIND_IN_SET(:item_id, item_id)'))->addParams([':item_id' => $id])->all();
		$model_question = Vendoritemquestion::find()
		->where(['item_id'=>$id,'answer_id'=>null,'question_answer_type'=>'selection'])
		->orwhere(['item_id'=>$id,'question_answer_type'=>'text','answer_id'=>null])
		->orwhere(['item_id'=>$id,'question_answer_type'=>'image','answer_id'=>null])
		->asArray()->all();

		$imagedata = Image::find()->where('item_id = :id AND module_type = :status', [':id' => $id, ':status' => 'vendor_item'])->orderby(['vendorimage_sort_order'=>SORT_ASC])->all();

        return $this->render('view', [
            'model'=> $this->findModel($id),'dataProvider1'=>$dataProvider1,'model_question'=>$model_question,'imagedata'=>$imagedata,
        ]);
    }

    /**
     * Creates a new Vendoritem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vendoritem();
        $model->vendor_id = Vendor::getVendor('vendor_id');
        $vendor = Vendor::find()->select('category_id')->where(['vendor_id'=>Vendor::getVendor('vendor_id')])->one();
        $cat_id = explode(',',$vendor['category_id']);

		$load_category = Category::find()->select('category_id','category_name')->where(['category_allow_sale'=>'Yes','category_level'=>'0','trash'=>'Default'])
		->andwhere(['IN','category_id',$vendor['category_id']])->asArray()->queryAll();
        $categoryname=ArrayHelper::map($load_category,'category_id','category_name');

        $model1 = new Image();
        $base = Yii::$app->basePath;
    		 $len = rand(1,1000);
     		$itemtype= Itemtype::loaditemtype();
       $vendorname= Vendor::loadvendorname();
     		$subcategory= Subcategory::loadsubcategoryname();

        if($model->load(Yii::$app->request->post()) && $model->validate()) {

			$model->item_for_sale = (Yii::$app->request->post()['Vendoritem']['item_for_sale'])?'Yes':'No';
	    	/* BEGIN  Scenario if item for sale is no not required below four fields all empty*/
			 if($model->item_for_sale == 'No')
			{
				$model->item_amount_in_stock = '';
				$model->item_default_capacity = '';
				$model->item_minimum_quantity_to_order='';
				$model->item_how_long_to_make='';
			}
			/* END Scenario if item for sale is no not required below four fields */
    // get the max sort order
			$max_sort = Vendoritem::find()->select('MAX(`sort`) as sort')->where(['trash'=>'Default','vendor_id'=>$model->vendor_id])->asArray()->all();
			$sort = ($max_sort[0]['sort'] + 1);
			$model->item_status='Deactive';
			$model->sort = $sort;
			if($model->save())
			{
			//BEGIN Manage item pricing table
			$itemid=$model->item_id;
			if(isset($_POST['vendoritem-item_price']['from']) && $_POST['vendoritem-item_price']['from'] !='')
			{

				$from = $_POST['vendoritem-item_price']['from'];
				$to = $_POST['vendoritem-item_price']['to'];
				$price = $_POST['vendoritem-item_price']['price'];
				for($opt=0;$opt<count($from);$opt++){
						$vendor_item_pricing = new Vendoritempricing();
						$vendor_item_pricing->item_id =  $itemid;
						$vendor_item_pricing->range_from = $from[$opt];
						$vendor_item_pricing->range_to = $to[$opt];
						$vendor_item_pricing->pricing_price_per_unit = $price[$opt];
						$vendor_item_pricing->save();
					}

			}
			//END Manage item pricing table
            /* Begin Upload guide image table  */

                        $guide_image = UploadedFile::getInstances($model, 'guide_image');

                        if ($guide_image) {
                         $i = 0;
                         foreach ($guide_image as $files) {
                            if($files instanceof yii\web\UploadedFile){
                            $filename = Yii::$app->security->generateRandomString() . "." . $files->extension;

                            //Resize file using imagine
                            $resize = true;

                            if($resize){
                            $newTmpName = $files->tempName . "." . $files->extension;

                            $imagine = new \Imagine\Gd\Imagine();
                            $image = $imagine->open($files->tempName);
                            $image->resize($image->getSize()->widen(210));
                            $image->save($newTmpName);

                            //Overwrite old filename for S3 uploading
                            $files->tempName = $newTmpName;
                            }

                        //Save to S3
                        $awsResult = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADSALESGUIDE . $filename);
                        if($awsResult){
                            $model->guide_image = $filename;
                        }
						
						$image_tbl = new Image();
						$image_tbl->image_path = $filename;
						$image_tbl->item_id = $itemid;
						$image_tbl->module_type = 'guides';
						$image_tbl->vendorimage_sort_order = $i;
						$image_tbl->image_user_id = Yii::$app->user->getId();
						$image_tbl->save();
                            ++$i;
                            }
                       }
                 }


                /* Begin Upload guide image table  */
                    $product_file = UploadedFile::getInstances($model, 'image_path');
                    if($product_file){
                        $i = 0;
                        foreach ($product_file as $files) {
                            if($files instanceof yii\web\UploadedFile){
                            $filename = Yii::$app->security->generateRandomString() . "." . $files->extension;
                            
                            //Resize file using imagine
                            $resize = true;

                        if($resize){
                            /* Begin Product image resolution 1000 */
                            $newTmpName2 = $files->tempName . "." . $files->extension;
                            $imagine = new \Imagine\Gd\Imagine();
                            $image_1000 = $imagine->open($files->tempName);
                            $image_1000->resize($image_1000->getSize()->widen(1000));
                            $image_1000->save($newTmpName2);

                            //Overwrite old filename for S3 uploading
                            $files->tempName = $newTmpName2;
                            $awsResult1 = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADFOLDER_1000 . $filename);
                            
                            /* Begin Product image resolution 530 */
                            $newTmpName1 = $files->tempName . "." . $files->extension;
                            $image_530 = $imagine->open($files->tempName);
                            $image_530->resize($image_530->getSize()->widen(530));
                            $image_530->save($newTmpName1);

                            //Overwrite old filename for S3 uploading
                            $files->tempName = $newTmpName1;
                            $awsResult1 = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADFOLDER_530 . $filename);

                            /* Begin Product image resolution 210 */
                            $newTmpName = $files->tempName . "." . $files->extension;
                            $image = $imagine->open($files->tempName);
                            $image->resize($image->getSize()->widen(210));
                            $image->save($newTmpName);

                            //Overwrite old filename for S3 uploading
                            $files->tempName = $newTmpName;

                            //Save to S3
                            $awsResult = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADFOLDER_210 . $filename);
                        }
                        
                        if($awsResult){
                            $model->image_path = $filename;
                        }
                        }
                        
                        $image_tbl = new Image();
                        $image_tbl->image_path = $filename;
                        $image_tbl->item_id = $model->item_id;
                        $image_tbl->image_user_id = Yii::$app->user->getId();
                        $image_tbl->module_type ='vendor_item';
                        $image_tbl->image_user_type ='admin';
                        $image_tbl->vendorimage_sort_order = $i;
                        $image_tbl->save();
                        ++$i;
                       }
                    }
            /*  Upload image table End */
		    echo Yii::$app->session->setFlash('success', "Item added successfully. Admin will check and approve it.");
		    Yii::info('[New Item] Admin created new item '.$model->item_name, __METHOD__);
            return $this->redirect(['index']);
			}
        } else {
            return $this->render('create', [
                'model' => $model,'model1' => $model1,'itemtype'=>$itemtype,'vendorname'=>$vendorname,'categoryname'=>$categoryname,'subcategory'=>$subcategory,

            ]);
        }
    }

    /**
     * Updates an existing Vendoritem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		 $model = $this->findModel($id);
		 $model1 = new Image();
		 $base = Yii::$app->basePath;
		 $len = rand(1,1000);
		 $item_id=$model->item_id;
		// Item image path values
		 $imagedata = Image::find()->where('item_id = :id AND module_type = :status', [':id' => $id, ':status' => 'vendor_item'])->orderby(['vendorimage_sort_order'=>SORT_ASC])->all();

		// Item image path SALES and  RENTAL values
		$guideimagedata = Image::find()->where('item_id = :id AND module_type = :status', [':id' => $id, ':status' => 'guides'])->orderBy(['vendorimage_sort_order' => SORT_ASC])->all();

      	/* question and answer */
      	$model_question = Vendoritemquestion::find()
		->where(['item_id'=>$id,'answer_id'=>Null,'question_answer_type'=>'selection'])
		->orwhere(['item_id'=>$id,'question_answer_type'=>'text','answer_id'=>Null])
		->orwhere(['item_id'=>$id,'question_answer_type'=>'image','answer_id'=>Null])
		->asArray()->all();

		 $cat_id=$model->category_id;
		 $subcat_id=$model->subcategory_id;
		 $itemtype= Itemtype::loaditemtype();
		 $vendorname= Vendor::loadvendorname();
		 $categoryname= Category::vendorcategory(Yii::$app->user->getId());
		 $subcategory= Subcategory::loadsubcategory($cat_id);
		 $childcategory= Childcategory::loadchildcategory($subcat_id);

		 $loadpricevalues= Vendoritempricing::loadpricevalues($item_id);
  if($model->load(Yii::$app->request->post()) && $model->validate()) {
   	$model->item_for_sale = (Yii::$app->request->post()['Vendoritem']['item_for_sale'])?'Yes':'No';
	   /* BEGIN  Scenario if item for sale is no not required below four fields all empty*/
			 if($model->item_for_sale == 'No')
			{
				$model->item_amount_in_stock = '';
				$model->item_default_capacity = '';
				$model->item_minimum_quantity_to_order='';
				$model->item_how_long_to_make='';
			}
			/* END Scenario if item for sale is no not required below four fields */

			$model->item_status='Deactive'; /* Vendor make it any changes item status should be deactivaed */
			if($model->save())
			{
				$itemid=$model->item_id;
				$file = UploadedFile::getInstances($model, 'image_path');
			 	/* Begin Upload guide image table  */
                        $guide_image = UploadedFile::getInstances($model, 'guide_image');

                        if ($guide_image) {
                         $i = 0;
                         foreach ($guide_image as $files) {
                            if($files instanceof yii\web\UploadedFile){
                            $filename = Yii::$app->security->generateRandomString() . "." . $files->extension;

                            //Resize file using imagine
                            $resize = true;

                            if($resize){
                            $newTmpName = $files->tempName . "." . $files->extension;

                            $imagine = new \Imagine\Gd\Imagine();
                            $image = $imagine->open($files->tempName);
                            $image->resize($image->getSize()->widen(210));
                            $image->save($newTmpName);

                            //Overwrite old filename for S3 uploading
                            $files->tempName = $newTmpName;
                            }

                        //Save to S3
                        $awsResult = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADSALESGUIDE . $filename);
                        if($awsResult){
                            $model->guide_image = $filename;
                        }

                        $image_tbl = new Image();
						$image_tbl->image_path = $filename;
						$image_tbl->item_id = $itemid;
						$image_tbl->module_type = 'guides';
						$image_tbl->vendorimage_sort_order = $i;
						$image_tbl->image_user_id = Yii::$app->user->getId();
						$image_tbl->save();
                        ++$i;
                            }
                       }
                 }
      /* Begin Upload guide image table  */

			 	/* Delete item price table records if its available any price for item type rental or service */
			 	if($model->type_id == 2)
			 	{
			 		Vendoritempricing::deleteAll('item_id = :item_id', [':item_id' => $model->item_id]);
			 	}

                /* Begin Upload guide image table  */
                    $product_file = UploadedFile::getInstances($model, 'image_path');
                    if($product_file){
                        $i = 0;
                        foreach ($product_file as $files) {
                            if($files instanceof yii\web\UploadedFile){
                            $filename = Yii::$app->security->generateRandomString() . "." . $files->extension;
                            
                            //Resize file using imagine
                            $resize = true;

                        if($resize){
                            /* Begin Product image resolution 1000 */
                            $newTmpName2 = $files->tempName . "." . $files->extension;
                            $imagine = new \Imagine\Gd\Imagine();
                            $image_1000 = $imagine->open($files->tempName);
                            $image_1000->resize($image_1000->getSize()->widen(1000));
                            $image_1000->save($newTmpName2);

                            //Overwrite old filename for S3 uploading
                            $files->tempName = $newTmpName2;
                            $awsResult1 = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADFOLDER_1000 . $filename);
                            
                            /* Begin Product image resolution 530 */
                            $newTmpName1 = $files->tempName . "." . $files->extension;
                            $image_530 = $imagine->open($files->tempName);
                            $image_530->resize($image_530->getSize()->widen(530));
                            $image_530->save($newTmpName1);

                            //Overwrite old filename for S3 uploading
                            $files->tempName = $newTmpName1;
                            $awsResult1 = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADFOLDER_530 . $filename);

                            /* Begin Product image resolution 210 */
                            $newTmpName = $files->tempName . "." . $files->extension;
                            $image = $imagine->open($files->tempName);
                            $image->resize($image->getSize()->widen(210));
                            $image->save($newTmpName);

                            //Overwrite old filename for S3 uploading
                            $files->tempName = $newTmpName;

                            //Save to S3
                            $awsResult = Yii::$app->resourceManager->save($files, Vendoritem::UPLOADFOLDER_210 . $filename);
                        }
                        
                        if($awsResult){
                            $model->image_path = $filename;
                        }
                        }
            
						$image_tbl = new Image();
						$image_tbl->image_path = $filename;
						$image_tbl->item_id = $model->item_id;
						$image_tbl->module_type = 'vendor_item';
						$image_tbl->image_user_type = 'admin';
						$image_tbl->vendorimage_sort_order = $i;
						$image_tbl->image_user_id = Yii::$app->user->getId();
						$image_tbl->save();
						
                       ++$i;
                       }
                    }
            /*  Upload image table End */
         }

               //BEGIN Manage item pricing table
				if(isset($_POST['vendoritem-item_price']['from']) && $_POST['vendoritem-item_price']['from'] !='')
				{
					Vendoritempricing::deleteAll('item_id = :item_id', [':item_id' => $item_id]);
					$from = $_POST['vendoritem-item_price']['from'];
					$to = $_POST['vendoritem-item_price']['to'];
					$price = $_POST['vendoritem-item_price']['price'];
					for($opt=0;$opt<count($from);$opt++){
							$vendor_item_pricing = new Vendoritempricing();
							$vendor_item_pricing->item_id =  $itemid;
							$vendor_item_pricing->range_from = $from[$opt];
							$vendor_item_pricing->range_to = $to[$opt];
							$vendor_item_pricing->pricing_price_per_unit = $price[$opt];
							$vendor_item_pricing->save();
						}

				}
				//END Manage item pricing table
 		echo Yii::$app->session->setFlash('success', "Item updated successfully.Admin will check and approve it.");
		Yii::info('[Item Updated] Vendor updated '.$model->item_name.' item information '. $id, __METHOD__);
		return $this->redirect(['index']);
		}
		else {
            return $this->render('update', [
                'model' => $model,'itemtype'=>$itemtype,'vendorname'=>$vendorname,'categoryname'=>$categoryname,'subcategory'=>$subcategory,
                'guideimagedata'=>$guideimagedata,'imagedata'=>$imagedata,'model1' => $model1,'childcategory'=>$childcategory,'loadpricevalues'=>$loadpricevalues,'model_question' => $model_question,
            ]);
		}
    }

    /**
     * Deletes an existing Vendoritem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
		echo Yii::$app->session->setFlash('success', "Item deleted successfully!");
        return $this->redirect(['index']);
    }

    public function actionCheck($image_id)
        {
			$user = Image::findOne($image_id);
			$user->delete();
		}

    /**
     * Finds the Vendoritem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Vendoritem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vendoritem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	public function actionBlock()
    {
		if(Yii::$app->request->isAjax)
		$data = Yii::$app->request->post();
		$status = ($data['status'] == 'Active' ? 'Deactive' : 'Active');
		$command=Vendoritem::updateAll(['item_status' => $status],['item_id= '.$data['id']]);
		if($status == 'Active')
			{
				echo Yii::$app->session->setFlash('success', "Category status updated!");
				return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
		 	}
			else
			{
					echo Yii::$app->session->setFlash('success', "Category status updated!");
					return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
			}
	}

	public function actionImagedelete()
	{

		if(Yii::$app->request->isAjax)
		{
		$data = Yii::$app->request->post();
		$id = explode(',',$data['id']);
		$ids = implode('","',$id);
		$command=Image::deleteAll(['IN','image_id',$ids]);
		if($command){
		   echo 'Deleted';	//die;
		}
		foreach($images as $img)
		{
			unlink(Yii::getAlias('@vendor_images').$img);
		}

		$images = explode(',',$data['loc']);
			if(isset($data['scenario']))
			{
				if($data['scenario']=="top"){
				foreach($images as $img)
				{
					unlink(Yii::getAlias('@top_category').$img);
				}
				   echo 'Deleted';	die;
				}
				else if($data['scenario']=="bottom"){
				foreach($images as $img)
				{
					unlink(Yii::getAlias('@bottom_category').$img);
				}
				   echo 'Deleted';	die;
				}
				else if($data['scenario']=="home"){
				foreach($images as $img)
				{
					unlink(Yii::getAlias('@home_ads').$img);
				}
				   echo 'Deleted';	die;
				}
			}
		}

	}


	public function actionSort_vendor_item()
	{
		$sort=$_POST['sort_val'];
		$item_id=$_POST['item_id'];
		$command=Vendoritem::updateAll(['sort' => $sort],['item_id= '.$item_id]);
		if($command)
		{
			Yii::$app->session->setFlash('success', "Item sort order updated successfully!");
			echo 1;exit;
		}
		else
		{
			echo 0;exit;
		}
	}

	/* Vendor item gridview status changes */
	public function actionStatus()
	{
		if(Yii::$app->request->isAjax)
		$data = Yii::$app->request->post();
		$ids = implode('","',$data['keylist']);
		if($data['status'] == 'Delete')
		{
			$command=Vendoritem::deleteAll(['IN','item_id',$ids]);
			if($command)
			{
				echo Yii::$app->session->setFlash('success', "Item deleted successfully!");
			}	else
			{
				echo Yii::$app->session->setFlash('danger', "Something went wrong");
			}
		}
		else if($data['status'] == 'Reject')
		{
			$command=Vendoritem::updateAll(['item_approved' => 'rejected'],['IN','item_id',$ids]);
			if($command)
			{
				echo Yii::$app->session->setFlash('success', "Item rejected successfully!");
			}	else
			{
				echo Yii::$app->session->setFlash('danger', "Something went wrong");
			}
		} else {
		$command=Vendoritem::updateAll(['item_status' => $data['status']],['IN','item_id',$ids]);	
		if($command)
			{
				echo Yii::$app->session->setFlash('success', "Item status updated!");
		 	}
			else
			{
					echo Yii::$app->session->setFlash('danger', "Something went wrong");
			}
		}
	}

	/* Vendor Item Image Drag SORT Order*/
	public function actionImageorder()
    {

    if(Yii::$app->request->isAjax)
    	$data = Yii::$app->request->post();
    	 $i =1;
		foreach($data['id'] as $order=>$value)
		{
		 $ids = explode('images_',$value);
		 $command=Image::updateAll(['vendorimage_sort_order' => $i],['image_id'=>$ids[1]]);
		 $i++;
		}
	}

	public function actionRenderquestion()
	{
		if(Yii::$app->request->isAjax){
		$data = Yii::$app->request->post();
		$question = Vendoritemquestion::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();

		if($question[0]['question_answer_type']=='image')
		{

			$answers = Vendoritemquestionguide::find()->where(['question_id' =>$data['q_id']])->asArray()->all();
		}
		else
		{
			$answers = Vendoritemquestionansweroption::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();
		}
		return $this->renderPartial('questionanswer',['question'=>$question, 'answers'=>$answers]);
		die; /* ALL DIE STATEMENT IMPORTANT FOR VENDOR PANEL*/
		}
	}
	public function actionViewrenderquestion()
	{
		if(Yii::$app->request->isAjax){
		$data = Yii::$app->request->post();
		$question = Vendoritemquestion::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();

		if($question[0]['question_answer_type']=='image')
		{

			$answers = Vendoritemquestionguide::find()->where(['question_id' =>$data['q_id']])->asArray()->all();
		}
		else
		{
			$answers = Vendoritemquestionansweroption::find()->where('question_id = "'.$data['q_id'].'"')->asArray()->all();
		}
		return $this->renderPartial('viewquestionanswer',['question'=>$question, 'answers'=>$answers]);
		}
	}

            // Delete item image
        public function actionDeleteitemimage()
        {
            $model1 = new Image();
            if (Yii::$app->request->isAjax) {
                $data = Yii::$app->request->post();
                if (isset($data['key']) &&  $data['key'] != '') {
                    $image_path = Image::loadguideimageids($data['key']);
                    Vendoritem::deleteFiles($image_path);
               		$image=Image::deleteAll(['image_id'=>$image_id]);
                    die; // dont remove die, action used by vendor module also.
                }
            }
        }

}
