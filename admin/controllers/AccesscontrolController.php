<?php

namespace admin\controllers;

use Yii;
use yii\db\Query;
use admin\models\Usercontroller;
use admin\models\Accesscontroller;
use admin\models\Authassignment;
use admin\models\Admin;
use admin\models\AccesscontrolSearch;
use yii\web\Controller;
use admin\models\Authitem;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
* AccesscontrolController implements the CRUD actions for Accesscontrol model.
*/
class AccesscontrolController extends Controller
{
    public function init()
    {
        parent::init();
        
        if (Yii::$app->user->isGuest) { 
            $url = Yii::$app->urlManager->createUrl(['admin/site/login']);
            Yii::$app->getResponse()->redirect($url);
        }
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['create', 'update', 'index', 'view', 'delete', 'authitem', 'loadcontroller', 'loadadmin'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
    * Lists all Accesscontrol models.
    *
    * @return mixed
    */
    public function actionIndex()
    {
	   $access = Authitem::AuthitemCheck('4', '29');

        if (yii::$app->user->can($access)) {
            $searchModel = new AccesscontrolSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        
        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');
            return $this->redirect(['site/index']);
        }
    }

    /**
    * Displays a single Accesscontrol model.
    *
    * @param int $id
    *
    * @return mixed
    */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
    * Creates a new Accesscontrol model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    *
    * @return mixed
    */
    public function actionCreate()
    {
        $access = Authitem::AuthitemCheck('1', '29');
        $request = Yii::$app->request;

        if (yii::$app->user->can($access)) {
            $model = new Accesscontroller();
            $controller = Usercontroller::loadcontroller();
            $admin = Admin::admin();
            $authitem = Authitem::Authitem();

            if ($request->isPost) {

                $model->load(Yii::$app->request->post());
                $id = explode('_', $model->admin_id);
                $adminid = $id[0];
                $roleid = $id[1];
                $command = Accesscontroller::deleteAll(['admin_id' => $adminid]);
                $command1 = Authassignment::deleteAll(['user_id' => $adminid]);
                $ar = array('controller_id', 'create', 'update', 'delete', 'manage', 'view');
                $p = 1;

                foreach ($model->controller as $key => $val) {
                    if (count($val) > 1) {
                        $controller_id = $create = $update = $view = $delete = $manage = '';
                        $auth_assign = new Authassignment;
                        foreach ($val as $k => $v) {
							$timenow = date('Y-m-d h:i:sa');
                            switch ($k)
                            {
                                case 'controller_id':
                                    $controller_id = $v;
                                    break;
                                case 'create':
                                    $create = $v;
                                    if ($controller_id != '') {
    									$auth_assign->item_name = $create;
    									$auth_assign->user_id = $adminid;
    									$auth_assign->controller_id = $controller_id;
    									$auth_assign->save();
                                    }
                                    break;
                                case 'update':
                                    $update = $v;
                                    if ($controller_id != '') {
                                        $auth_assign->item_name = $v;
        								$auth_assign->user_id = $adminid;
        								$auth_assign->controller_id = $controller_id;
        								$auth_assign->save();
                                    }
                                    break;
                                case 'delete':
                                    $delete = $v;
                                    if ($controller_id != '') {
                                        $auth_assign->item_name = $v;
        								$auth_assign->user_id = $adminid;
        								$auth_assign->controller_id = $controller_id;
        								$auth_assign->save();
                                    }
                                    break;
                                case 'manage':
                                    $manage = $v;
                                    if ($controller_id != '') {
                                        $auth_assign->item_name = $v;
        								$auth_assign->user_id = $adminid;
        								$auth_assign->controller_id = $controller_id;
        								$auth_assign->save();
                                    }
                                    break;
                                case 'view':
                                    $view = $v;
                                    if ($controller_id != '') {
                                    $auth_assign->item_name = $v;
    								$auth_assign->user_id = $adminid;
    								$auth_assign->controller_id = $controller_id;
    								$auth_assign->save();
                                    }
                                    break;
                            }
                        }
                        $timenow = date('Y-m-d h:i:sa');
                        $userid = Admin::getAdmin('id');
                        if ($controller_id != '') {
    						$access_ctrl=new Accesscontroller;
                            $access_ctrl->role_id = $roleid;
    						$access_ctrl->admin_id = $adminid;
    						$access_ctrl->controller = $controller_id;
                            $access_ctrl->create = $create;
                             $access_ctrl->update = $update;
    						 $access_ctrl->delete = $delete;
    						 $access_ctrl->manage = $manage;
                             $access_ctrl->view = $view;
    						 $access_ctrl->created_by = $userid;
    						 $access_ctrl->created_datetime = $timenow;
    						$access_ctrl->validate();
    						$access_ctrl->save();
                        }
                    }
                }
                
                Yii::$app->session->setFlash('success', 'Access controller created successfully!');
                return $this->redirect(['index']);

            } else {
                return $this->render('create', [
                    'model' => $model, 'admin' => $admin, 'authitem' => $authitem, 'controller' => $controller,
                ]);
            }

        } else {

            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');
            return $this->redirect(['site/index']);
        }
    }

    /**
    * Updates an existing Accesscontroller model.
    * If update is successful, the browser will be redirected to the 'view' page.
    *
    * @param int $id
    *
    * @return mixed
    */
    public function actionUpdate($id)
    {
        $access = Authitem::AuthitemCheck('2', '29');
        
        if (yii::$app->user->can($access)) {
            $model = $this->findModel($id);
            $controller = Usercontroller::loadcontroller($model->admin_id, $model->role_id);
            $admin = Admin::adminupdate();
            $authitem = Authitem::Authitem();
            $admin_id = $model->admin_id;
            $accesslist = \Yii::$app->DB->createCommand("SELECT whitebook_controller.controller,whitebook_controller.id,`create`,`update`,`delete`,`manage`,`view` FROM whitebook_access_control LEFT JOIN whitebook_controller ON whitebook_controller.id = whitebook_access_control.controller WHERE admin_id = $admin_id AND role_id =$model->role_id ORDER BY whitebook_access_control.controller ASC")->queryall();
            $model->admin_id = $model->admin_id.'_'.$model->role_id;
            
            if (Yii::$app->request->isPost) {
                
				$model->load(Yii::$app->request->post());
                $id = explode('_', $model->admin_id);
                $adminid = $id[0];
                $roleid = $id[1];
                $command =Accesscontroller::deleteAll(['admin_id' => $admin_id,'role_id' => $model->role_id]);
                $command =Authassignment::deleteAll(['user_id' => $admin_id]);
                $model->load(Yii::$app->request->post());
                $ar = array('controller_id', 'create', 'update', 'delete', 'manage', 'view');
                foreach ($model->controller as $key => $val) {
                    if (count($val) > 1 && isset($val['controller_id'])) {
                        $controller_id = $create = $update = $delete = $view = $manage = '';
                        $auth_assign = new Authassignment;
                        foreach ($val as $k => $v) {
                            switch ($k) {
                                case 'controller_id':
                                $controller_id = $v;
                                break;

                                case 'create':
									$create = $v;
									$auth_assign->item_name = $create;
									$auth_assign->user_id = $adminid;
									$auth_assign->controller_id = $controller_id;
									$auth_assign->save();

                                break;
                                case 'update':
                                $update = $v;
                                $auth_assign->item_name = $v;
								$auth_assign->user_id = $adminid;
								$auth_assign->controller_id = $controller_id;
								$auth_assign->save();
                                break;
                                case 'delete':
                                $delete = $v;
                                $auth_assign->item_name = $v;
								$auth_assign->user_id = $adminid;
								$auth_assign->controller_id = $controller_id;
								$auth_assign->save();

                                break;
                                case 'manage':
                                $manage = $v;
								$auth_assign->item_name = $v;
								$auth_assign->user_id = $adminid;
								$auth_assign->controller_id = $controller_id;
								$auth_assign->save();
                                break;
                                case 'view':
                                $view = $v;
								$auth_assign->item_name = $v;
								$auth_assign->user_id = $adminid;
								$auth_assign->controller_id = $controller_id;
								$auth_assign->save();
                                break;
                            }
                        }
                        $timenow = date('Y-m-d h:i:sa');
                        $userid = Admin::getAdmin('id');
                        $access_ctrl=new Accesscontroller();
                        $access_ctrl->role_id = $roleid;
						$access_ctrl->admin_id = $adminid;
						$access_ctrl->controller = $controller_id;
                        $access_ctrl->create = $create;
                        $access_ctrl->update = $update;
						$access_ctrl->delete = $delete;
						$access_ctrl->manage = $manage;
                        $access_ctrl->view = $view;
						$access_ctrl->created_by = $userid;
						$access_ctrl->created_datetime = $timenow;
						$access_ctrl->save();
                    }
                }
                
                Yii::$app->session->setFlash('success', 'Access controller Updated successfully!');
                return $this->redirect(['index']);

            } else {
                
                return $this->render('update', [
                    'model' => $model, 'admin' => $admin, 'authitem' => $authitem, 'controller' => $controller, 'accesslist' => $accesslist,
                ]);
            }

        } else {
            
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
    * Deletes an existing Accesscontroller model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    *
    * @param int $id
    *
    * @return mixed
    */
    public function actionDelete($id)
    {
        $access = Authitem::AuthitemCheck('3', '29');

        if (yii::$app->user->can($access)) {
            $admin_id = Accesscontroller::find()->select('admin_id,controller')->where(['access_id' => $id])->one();
            $admin_id = $admin_id['admin_id'];

            $command =Accesscontroller::deleteAll(['admin_id' => $admin_id]);
            $command =Authassignment::deleteAll(['user_id' => $admin_id]);

            Yii::$app->session->setFlash('success', 'Access controller deleted successfully!');

            return $this->redirect(['index']);

        } else {
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

            return $this->redirect(['site/index']);
        }
    }

    /**
    * Finds the Accesscontroller model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    *
    * @param int $id
    *
    * @return Accesscontroller the loaded model
    *
    * @throws NotFoundHttpException if the model cannot be found
    */
    protected function findModel($id)
    {
        if (($model = Accesscontroller::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLoadadmin()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }

		$subQuery = (new Query())
                ->select('*')
                ->from('{{%access_control}} t')
                ->where('t.admin_id = p2.id');

		$query = (new Query())
                ->select('*')
                ->from('{{%admin}} p2')
                ->where(['exists', $subQuery]);

        $command = $query->createCommand();
		$city=($command->queryall());

        if (!empty($city)) {
            echo  '<option value="">Select</option>';
            foreach ($city as $key => $val) {
                echo  '<option value="'.$val['id'].'">'.$val['admin_name'].'</option>';
            }
        } else {
            return 1;
        }
    }

    public function actionLoadcontroller()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }

        $admin_id = $data['admin_id'];
        
        $control_id = Authassignment::find()
            ->select('controller_id')
            ->where(['user_id' => $admin_id])
            ->groupBy(['controller_id'])
		    ->all();

        if (!empty($control_id)) {
            foreach ($control_id as $controll_id) {
                $controller_id[] = "'".$controll_id['controller_id']."'";
            }
            $controller_id = implode(',', $controller_id);
        }
        
        if (!empty($controller_id)) {
            $command = \Yii::$app->DB->createCommand('SELECT id,controller FROM whitebook_controller where id NOT IN ('.$controller_id.')');
        } else {
            $command = \Yii::$app->DB->createCommand('SELECT id,controller FROM whitebook_controller');
        }
        
        $role = $command->queryall();
        
        echo '<input type="checkbox" onclick="checkall(this.checked);">Select all';
        foreach ($role as $key => $val) {
            echo '<label><input type="checkbox" name="Accesscontroller[controller][]" value="'.$val['id'].'" class="checkbox_all">'.$val['controller'].'</label><br>';
        }
    }

    public function actionAuthitem()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
        
        $data = Yii::$app->request->post();

        $admin_id = $data['admin_id'];
        $controller_id = $data['controller_id'];

		$subQuery = (new Query())
            ->select('item_name')
            ->from('{{%auth_assignment}} t')
            ->where('t.item_name = p2.name')
            ->andwhere('t.item_name = p2.name')
            ->andwhere('t.user_id = '.$admin_id.'')
            ->andwhere('t.controller_id = '.$controller_id.'');
		
        $role = (new Query())
            ->select('name')
            ->from('{{%auth_item}} p2')
            ->where(['exists', $subQuery]);

        $command = $role->createCommand();
		
        $role=($command->queryall());

        foreach ($role as $key => $val) {
            echo  '<option value="'.$val['name'].'">'.$val['name'].'</option>';
        }
    }
}
