<?php use_helper('izarusModalChild'); ?>
<div id="izarusModalChild<?php echo $class.$form_class; ?>Load">
   <table class="table table-bordered table-stripped">
    <thead>
      <tr>
<?php foreach($cols AS $col_name=>$text): ?>
        <th><?php echo $col_name; ?></th>
<?php endforeach; ?>
<?php if(!in_array($sf_context->getActionName(),array('show','new'))){ ?>
        <th></th>
<?php } ?>
      </tr>
    </thead>
    <tbody>
<?php if($sf_context->getActionName()=='new'): ?>
      <tr>
        <td colspan="<?php echo count($cols)+1; ?>"><?php echo isset($messages['new'])?$messages['new']:''; ?></td>
      </tr>
<?php elseif(!count($collection)): ?>
      <tr>
        <td colspan="<?php echo count($cols)+1; ?>"><?php echo $messages['empty']?></td>
      </tr>
<?php else: ?>
<?php foreach($sf_data->getRaw('collection') AS $c): ?>
      <tr>
<?php foreach($cols AS $col_name=>$text): ?>
        <td><?php
        if(strpos($text,'_')===FALSE || strpos($text,'_')>0)
          echo imc_parse_text($text,$c);
        else{
          preg_match_all('/\p{Lu}\p{Ll}*\P{Lu}/', get_class($c), $m);
          include_partial(substr($text,1),array(strtolower(implode('_',$m[0]))=>$c));
        }
        ?></td>
<?php endforeach; ?>
<?php if(!in_array($sf_context->getActionName(),array('show','new'))){ ?>
        <td class="text-right" style="width:1px; white-space:nowrap">
<?php foreach($buttons AS $b){ ?>
<?php
$params = array();
foreach($b['param'] AS $name=>$value)
  $params[] = $name.'='.$c[$value];
?>
          <a href="<?php echo url_for($b['redirect'].'?'.implode('&',$params)); ?>" class="btn btn-<?php echo $b['color']; ?> btn-<?php echo $buttons_size; ?>"><small class="glyphicon glyphicon-<?php echo $b['icon']; ?>"></small></a>
<?php } ?>

<?php if($enabled_actions['edit']){ ?>
          <a data-href="<?php echo $c->getId(); ?>" class="btn btn-primary btn-<?php echo $buttons_size; ?>" data-toggle="modal" data-target="#izarusModalChild<?php echo $class.$form_class; ?>Modal"><small class="glyphicon glyphicon-pencil"></small></a>
<?php } ?>
<?php if($enabled_actions['delete']){ ?>
          <a data-href="del-<?php echo $c->getId(); ?>" class="btn btn-danger btn-<?php echo $buttons_size; ?>" data-toggle="modal" data-target="#izarusModalChild<?php echo $class.$form_class; ?>Modal"><small class="glyphicon glyphicon-remove"></small></a>
<?php } ?>
        </td>
<?php } ?>
      </tr>
<?php endforeach; ?>
<?php endif; ?>
    </tbody>
<?php if(!in_array($sf_context->getActionName(),array('show','new')) && $enabled_actions['add']){ ?>
    <tfoot>
      <tr>
        <td colspan="<?php echo count($cols)+1; ?>"><a href="" data-href="+" data-toggle="modal" data-target="#izarusModalChild<?php echo $class.$form_class; ?>Modal"><i class="glyphicon glyphicon-plus"></i> <?php echo $messages['add']; ?></a></td>
      </tr>
    </tfoot>
<?php } ?>
  </table>
</div>