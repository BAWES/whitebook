<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use common\models\Events;
use common\models\Package;
use common\models\EventItemlink;
use common\models\VendorItemToPackage;
use frontend\models\Users;

/**
* Packages controller.
*/
class PackagesController extends BaseController
{	
	public function actionIndex()
    {
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'] .' | Event Packages';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => 'Event Packages in The White Book - Event Planning Platform']);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => 'Event Packages in The White Book - Event Planning Platform']);

    	$packages = Package::find()
    		->where(['status' => 1])
    		->all();

    	return $this->render('index', [
            'packages' => $packages,
        ]);
    }

    public function actionDetail($slug) 
    {
        $package = Package::findOne(['package_slug' => $slug]);

        if (!$package) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'] . ' | ' . $package->package_name;
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $package->package_description]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => $package->package_description]);

        $categories = \frontend\models\Category::find()
            ->where(['category_level' => 0, 'category_allow_sale' =>'Yes', 'trash' =>'Default'])
            ->orderBy(new \yii\db\Expression('FIELD (category_name, "Venues", "Invitations", "Food & Beverages", "Decor", "Supplies", "Entertainment", "Services", "Others", "Gift favors")'))
            ->all();

        if(Yii::$app->user->isGuest) 
        {
            $wishlist_item_ids = [];
            $customer_events = [];
        } 
        else 
        {
            $wishlist = Users::get_customer_wishlist_details(Yii::$app->user->getId());
            $wishlist_item_ids = \yii\helpers\ArrayHelper::getColumn($wishlist, 'item_id'); 
            
            $customer_events = Events::find()
                ->where(['customer_id' => Yii::$app->user->identity->customer_id])
                ->all();            
        }

        return $this->render('detail', [
            'package' => $package,
            'categories' => $categories,
            'wishlist_item_ids' => $wishlist_item_ids,
            'customer_events' => $customer_events
        ]);
    }

    public function actionAddToEvent()
    {
        if (!Yii::$app->request->isAjax || Yii::$app->user->isGuest) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
      
        $data = Yii::$app->request->post();

        $event = Events::find()
            ->where([
                'event_id' => $data['event_id'],
                'customer_id' => Yii::$app->user->getId()
            ])
            ->one();

        if (!$event) {
            return [
                'error' => Yii::t('frontend', 'Event not found!')
            ];
        }

        $package = Package::find()
            ->where([
                'package_id' => $data['package_id'],
                'status' => 1
            ])
            ->one();

        if (!$package) {
            return [
                'error' => Yii::t('frontend', 'Package not found!')
            ];
        }

        $items = VendorItemToPackage::find()
                ->where(['package_id' => $package->package_id])
                ->all();

        foreach ($items as $key => $value) {
            $event_item = new EventItemlink();
            $event_item->event_id = $data['event_id'];
            $event_item->item_id = $value->item_id;
            $event_item->trash = 'Default';
            $event_item->save();
        }

        Yii::$app->response->format = 'json';

        Yii::$app->session->setFlash(
            'success', 
            Yii::t('frontend', '{count} item added to event.', [
                'count' => sizeof($items)
            ])
        );

        return [
            'event_url' => Url::to(['events/detail', 'slug' => $event->slug], true)
        ];
    }

    public function actionAddEvent()
    {
        if (!Yii::$app->request->isAjax || Yii::$app->user->isGuest) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $request = Yii::$app->request;

        $package = Package::find()
            ->where([
                'package_id' => $request->post('package_id'),
                'status' => 1
            ])
            ->one();

        if (!$package) 
        {
            Yii::$app->response->format = 'json';
        
            return [
                'error' => Yii::t('frontend', 'Package not found!')
            ];
        }

        $event_name = $request->post('event_name');
        $event_date = $request->post('event_date');
        $customer_id = Yii::$app->user->identity->customer_id;
        
        //check if event already exists 

        $is_exist = Events::find()
            ->select('event_id')
            ->where(['customer_id' => $customer_id, 'event_name' => $event_name])
            ->count();

        if ($is_exist) 
        {        
            Yii::$app->response->format = 'json';
                    
            return [
                'error' => Yii::t('frontend', 'Event already exist')
            ];
        }

        // add event 

        $event_date1 = date('Y-m-d', strtotime($event_date));
        
        $event_modal = new Events;
        $event_modal->customer_id = $customer_id;
        $event_modal->event_name = $event_name;
        $event_modal->event_date = $event_date1;
        $event_modal->event_type = $request->post('event_type');
        $event_modal->no_of_guests = $request->post('no_of_guests');
        
        if(!$event_modal->save()) 
        {
            Yii::$app->response->format = 'json';
        
            return [
                'error' => $event_modal->getErrors()
            ];
        }

        //add package items to event 

        $items = VendorItemToPackage::find()
                ->where(['package_id' => $package->package_id])
                ->all();

        foreach ($items as $key => $value) {
            $event_item = new EventItemlink();
            $event_item->event_id = $event_modal->event_id;
            $event_item->item_id = $value->item_id;
            $event_item->trash = 'Default';
            $event_item->save();
        }

        Yii::$app->session->setFlash(
            'success', 
            Yii::t('frontend', '{count} item added to event.', [
                'count' => sizeof($items)
            ])
        );

        Yii::$app->response->format = 'json';
        
        return [
            'success' => 1,
            'event_url' => Url::to(['events/detail', 'slug' => $event_modal->slug], true)
        ];
    }
}