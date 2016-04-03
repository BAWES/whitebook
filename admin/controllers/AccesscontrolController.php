<?php

namespace admin\controllers;

use Yii;
use common\models\Usercontroller;
use common\models\Accesscontroller;
use common\models\Admin;
use common\models\AccesscontrolSearch;
use yii\web\Controller;
use common\models\Authitem;
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
        if (Yii::$app->user->isGuest) { // chekck the admin logged in
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
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

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
        if (yii::$app->user->can($access)) {
            $model = new Accesscontroller();
            $controller = Usercontroller::loadcontroller();
            $admin = Admin::admin();
            $authitem = Authitem::Authitem();
            if ($_POST) {
                $model->load(Yii::$app->request->post());
                $id = explode('_', $model->admin_id);
                $adminid = $id[0];
                $roleid = $id[1];
                $command = \Yii::$app->DB->createCommand("DELETE FROM whitebook_access_control WHERE admin_id = $adminid");
                $command->execute();

                $command = \Yii::$app->DB->createCommand("DELETE FROM whitebook_auth_assignment WHERE user_id = $adminid");
                $command->execute();
                $ar = array('controller_id', 'create', 'update', 'delete', 'manage', 'view');
                $p = 1;
                foreach ($model->controller as $key => $val) {
                    if (count($val) > 1) {
                        $controller_id = $create = $update = $view = $delete = $manage = '';
                        foreach ($val as $k => $v) {
                            switch ($k) {
                            case 'controller_id':
                            $controller_id = $v;
                            break;
                            case 'create':
                             $create = $v;
                             if ($controller_id != '') {
                                 $sql = 'INSERT whitebook_auth_assignment(`item_name`,`user_id`,`controller_id`) values ("'.$create.'",'.$adminid.','.$controller_id.')';
                                 $command = \Yii::$app->DB->createCommand($sql);
                                 $command->execute();
                             }
                            break;
                            case 'update':
                                $update = $v;
                                if ($controller_id != '') {
                                    $sql = 'INSERT whitebook_auth_assignment(`item_name`,`user_id`,`controller_id`) values ("'.$update.'",'.$adminid.','.$controller_id.')';
                                    $command = \Yii::$app->DB->createCommand($sql);
                                    $command->execute();
                                }
                            break;
                            case 'delete':
                                $delete = $v;
                                if ($controller_id != '') {
                                    $sql = 'INSERT whitebook_auth_assignment(`item_name`,`user_id`,`controller_id`) values ("'.$delete.'",'.$adminid.','.$controller_id.')';
                                    $command = \Yii::$app->DB->createCommand($sql);
                                    $command->execute();
                                }
                            break;
                            case 'manage':
                                $manage = $v;
                                if ($controller_id != '') {
                                    $sql = 'INSERT whitebook_auth_assignment(`item_name`,`user_id`,`controller_id`) values ("'.$manage.'",'.$adminid.','.$controller_id.')';
                                    $command = \Yii::$app->DB->createCommand($sql);
                                    $command->execute();
                                }
                            break;

                            case 'view':
                                $view = $v;
                                if ($controller_id != '') {
                                    $sql = 'INSERT whitebook_auth_assignment(`item_name`,`user_id`,`controller_id`) values ("'.$view.'",'.$adminid.','.$controller_id.')';
                                    $command = \Yii::$app->DB->createCommand($sql);
                                    $command->execute();
                                }
                            break;
                }
                        }
                        $timenow = date('Y-m-d h:i:sa');
                        $userid = Admin::getAdmin('id');
                        if ($controller_id != '') {
                            $sql = 'INSERT whitebook_access_control(`role_id`,`admin_id`,`controller`,`create`,`update`,`delete`,`manage`,`view`,`created_by`,`created_datetime`) values ("'.$roleid.'","'.$adminid.'","'.$controller_id.'","'.$create.'","'.$update.'","'.$delete.'","'.$manage.'","'.$view.'","'.$userid.'","'.$timenow.'")';
                            $command = \Yii::$app->DB->createCommand($sql);
                            $command->execute();
                        }
                    }
                }
                echo Yii::$app->session->setFlash('success', 'Access controller created successfully!');

                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                'model' => $model, 'admin' => $admin, 'authitem' => $authitem, 'controller' => $controller,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

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
            if ($_POST) {
                $id = explode('_', $model->admin_id);
                $adminid = $id[0];
                $roleid = $id[1];
                $command = \Yii::$app->DB->createCommand("DELETE FROM whitebook_access_control WHERE admin_id = $admin_id AND role_id = $model->role_id ");
                $command->execute();

                $command = \Yii::$app->DB->createCommand("DELETE FROM whitebook_auth_assignment WHERE user_id = $admin_id");
                $command->execute();

                $model->load(Yii::$app->request->post());
                $ar = array('controller_id', 'create', 'update', 'delete', 'manage', 'view');
                foreach ($model->controller as $key => $val) {
                    if (count($val) > 1 && isset($val['controller_id'])) {
                        $controller_id = $create = $update = $delete = $view = $manage = '';
                        foreach ($val as $k => $v) {
                            switch ($k) {
                            case 'controller_id':
                             $controller_id = $v;
                            break;

                            case 'create':
                             $create = $v;
                             $command = \Yii::$app->DB->createCommand('INSERT whitebook_auth_assignment(`item_name`,`user_id`,`controller_id`) values ("'.$create.'",'.$adminid.','.$controller_id.')');
                             $command->execute();
                            break;
                            case 'update':
                             $update = $v;
                            $command = \Yii::$app->DB->createCommand('INSERT whitebook_auth_assignment(`item_name`,`user_id`,`controller_id`) values ("'.$update.'",'.$adminid.','.$controller_id.')');
                            $command->execute();
                            break;
                            case 'delete':
                            $delete = $v;
                            $command = \Yii::$app->DB->createCommand('INSERT whitebook_auth_assignment(`item_name`,`user_id`,`controller_id`) values ("'.$delete.'",'.$adminid.','.$controller_id.')');
                            $command->execute();
                            break;
                            case 'manage':
                            $manage = $v;
                            $command = \Yii::$app->DB->createCommand('INSERT whitebook_auth_assignment(`item_name`,`user_id`,`controller_id`) values ("'.$manage.'",'.$adminid.','.$controller_id.')');
                            $command->execute();
                            break;
                            case 'view':
                            $view = $v;
                            $command = \Yii::$app->DB->createCommand('INSERT whitebook_auth_assignment(`item_name`,`user_id`,`controller_id`) values ("'.$view.'",'.$adminid.','.$controller_id.')');
                            $command->execute();
                            break;
                }
                        }
                        $timenow = date('Y-m-d h:i:sa');
                        $userid = Admin::getAdmin('id');
                        $command = \Yii::$app->DB->createCommand('INSERT whitebook_access_control(`role_id`,`admin_id`,`controller`,`create`,`update`,`delete`,`manage`,`view`,`created_by`,`created_datetime`) values ("'.$roleid.'","'.$adminid.'","'.$controller_id.'","'.$create.'","'.$update.'","'.$delete.'","'.$manage.'","'.$view.'","'.$userid.'","'.$timenow.'")');
                        $command->execute();
                    }
                }
                echo Yii::$app->session->setFlash('success', 'Access controller Updated successfully!');

                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                'model' => $model, 'admin' => $admin, 'authitem' => $authitem, 'controller' => $controller, 'accesslist' => $accesslist,
            ]);
            }
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

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
            $command = \Yii::$app->DB->createCommand(
            'DELETE FROM `whitebook_auth_assignment` WHERE `user_id`="'.$admin_id.'"');
            $deletevalue = $command->execute();

            $command = \Yii::$app->DB->createCommand(
            'DELETE FROM `whitebook_access_control` WHERE `admin_id`="'.$admin_id.'"');
            $deleteaccess = $command->execute();
            echo Yii::$app->session->setFlash('success', 'Access controller deleted successfully!');

            return $this->redirect(['index']);
        } else {
            echo Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');

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
        $command = \Yii::$app->DB->createCommand('SELECT id,admin_name FROM whitebook_admin where role_id="'.$data['id'].'" and not exists (SELECT null FROM whitebook_access_control where admin_id = whitebook_admin.id)');
        $city = $command->queryall();
        if ($city) {
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
        $command = \Yii::$app->DB->createCommand('SELECT controller_id FROM whitebook_auth_assignment
		 where user_id = "'.$admin_id.'" group by controller_id');
        $control_id = $command->queryall();
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
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $admin_id = $data['admin_id'];
        $controller_id = $data['controller_id'];
        $command = \Yii::$app->DB->createCommand('SELECT name FROM whitebook_auth_item
where NOT EXISTS (
select item_name from whitebook_auth_assignment where whitebook_auth_item.name=whitebook_auth_assignment.item_name and
whitebook_auth_assignment.user_id="'.$admin_id.'" and
whitebook_auth_assignment.controller_id="'.$controller_id.'")');
        $role = $command->queryall();
        foreach ($role as $key => $val) {
            echo  '<option value="'.$val['name'].'">'.$val['name'].'</option>';
        }
    }
}
