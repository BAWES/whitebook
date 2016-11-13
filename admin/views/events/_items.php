<tr>
    <td><?=\yii\helpers\Html::a($model->itemDetail->item_name,['vendor-item/view','id'=>$model->itemDetail->item_id])?></td>
    <td><?=date('d-M-Y',strtotime($model->link_datetime))?></td>
    <td><?=\yii\helpers\Html::a('<i class="fa fa-eye" aria-hidden="true"></i>',['vendor-item/view','id'=>$model->itemDetail->item_id])?></td>
</tr>