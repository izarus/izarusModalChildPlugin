<h3 style="margin:0">¿Desea eliminar este elemento?</h3>
<br>
<table class="table table-stripped table-bordered">
<?php foreach($form AS $f){?> 
<?php if(!$f->isHidden() && $f->getValue()){ ?> 
<tr>
  <th><?php echo $f->renderLabelName()?></th>
  <td><?php 
  $value = $f->getValue();
  if(get_class($f->getWidget()) == 'sfWidgetFormDoctrineChoice'){
    $tabla = $f->getWidget()->getOption('model');
    $tabla = $tabla.'Table';
    $value = $tabla::getInstance()->findOneById($value);
  }else if(get_class($f->getWidget()) == 'sfWidgetFormInputCheckbox'){
    if($value)
      $value='Si';
    else
      $value='No';
  }
  echo $value; ?></td>
</tr>
<?php }} ?>
</table>
<input type="hidden" name="id" value="del-<?php echo $form->getObject()->getId(); ?>">