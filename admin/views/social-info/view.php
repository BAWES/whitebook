<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Socialinfo */

$this->title = $model->store_social_id;
$this->params['breadcrumbs'][] = ['label' => 'Socialinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="socialinfo-view">
		<?= Html::a('Manage', ['index', ], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Update', ['update', 'store_social_id' => $model->store_social_id, 'store_id' => $model->store_id], ['class' => 'btn btn-primary']) ?>
        
		<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'store_social_id',
            'store_id',
            'store_facebook_share:ntext',
            'store_twitter_share:ntext',
            'store_google_share:ntext',
            'store_linkedin_share:ntext',
            'google_analytics:ntext',
            'live_script:ntext',
        ],
    ]) ?>

</div>
