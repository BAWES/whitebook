<?php foreach ($user_event_list as $key => $value) { ?>
<li>
<div class="delet_icons_new" onclick="deletefiltering1('<?php echo $value['event_id'];?>');"></div>
<a href="<?php echo Yii::$app->params['BASE_URL'].'/event-details/'.$value['slug']; ?>" id="<?php echo $value['event_id'];?>"  title="<?= $value['event_name']; ?>">
<div class="thing_inner_items">
<h3><?php if(strlen($value['event_name'])>12){echo substr($value['event_name'], 0, 12).' ...';}else{ echo$value['event_name'];} ?></h3>
<p><?= $value['event_date']; ?></p>
<p><?= $value['event_type']; ?><br/>
</p>
</div>
</a>
</li>                                    
<?php }  ?>  
<li>
<div class="thing_inner_items border_none">
<a href="#" data-toggle="modal" data-target="#EventModal" title="">&nbsp;</a>
</div>
</li>

