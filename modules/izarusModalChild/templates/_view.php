<?php echo include_partial('izarusModalChild/table',array(
  'cols'=>$cols,
  'collection'=>$collection,
  'messages'=>$messages,
  'class'=>$class,
  'form_class'=>$form_class,
  'buttons'=>$buttons,
  'enabled_actions'=>$enabled_actions,
  'buttons_size'=>$buttons_size,
)); ?>
<?php echo include_partial('izarusModalChild/modal',array(
  'messages'=>$messages,
  'class'=>$class,
  'form_class'=>$form_class,
  'obj_parent_id'=>$obj_parent_id,
  'parent_id'=>$parent_id,
  'cols'=>$cols,
  'call_js_function'=>$call_js_function,
  'modal_size'=>$modal_size,
)); ?>
