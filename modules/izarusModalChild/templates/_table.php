<?php use_helper('izarusModalChild'); ?>
<div id="izarusModalChild<?php echo $class; ?>Load">   
   <table class="table table-bordered table-stripped">
    <thead>
      <tr>
<?php foreach($cols AS $col_name=>$text): ?>
        <th><?php echo $col_name; ?></th>
<?php endforeach; ?>
        <th></th>
      </tr>
    </thead>
    <tbody>
<?php if(!count($collection)): ?>
      <tr>
        <td colspan="<?php echo count($cols)+1; ?>"><?php echo $messages['empty']?></td>
      </tr>
<?php else: ?>
<?php foreach($sf_data->getRaw('collection') AS $c): ?>
      <tr>
<?php foreach($cols AS $col_name=>$text): ?>
        <td><?php 
          echo imc_parse_text($text,$c);
        ?></td>
<?php endforeach; ?>
        <td class="text-right" style="width:80px;">
          <a data-href="<?php echo $c->getId(); ?>" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#izarusModalChild<?php echo $class; ?>Modal">&nbsp;<small class="glyphicon glyphicon-pencil"></small>&nbsp;</a>
          <a data-href="del-<?php echo $c->getId(); ?>" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#izarusModalChild<?php echo $class; ?>Modal">&nbsp;<small class="glyphicon glyphicon-remove"></small>&nbsp;</a>
        </td>
      </tr>
<?php endforeach; ?>
<?php endif; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="<?php echo count($cols)+1; ?>"><a href="" data-href="+" data-toggle="modal" data-target="#izarusModalChild<?php echo $class; ?>Modal"><i class="glyphicon glyphicon-plus"></i> <?php echo $messages['add']; ?></a></td>
      </tr>
    </tfoot>
  </table>
</div>