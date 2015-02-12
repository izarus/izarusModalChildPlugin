     <div class="modal fade cloneAtEnd" id="izarusModalChild<?php echo $class; ?>Modal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button>
            <h4 class="modal-title" id="myModalLabel"></h4>
          </div>
          [form class="form-horizontal ajaxform" method="post" action="<?php echo url_for('izarusModalChild/processForm?imcd='.str_replace('a','_',strrev(base64_encode(json_encode(array('c'=>$class,'f'=>$form_class,'opi'=>$obj_parent_id,'pi'=>$parent_id)))))); ?>" form]
            <div class="modal-body"></div>
            <div class="modal-footer">
              <div class="botones">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <button type="submit" class="btn btn-danger">Eliminar</button>
                <button type="submit" class="btn btn-success">Agregar</button>
              </div>
              <div class="espere" style="display:none;">
                <i class="fa fa-spin fa-spinner"></i> Espere...
              </div>
            </div>
          [/form]
        </div>
      </div>
    </div>
<script>
function imf_<?php echo $class?>_updateClics(){
  $('#izarusModalChild<?php echo $class; ?>Load [data-toggle="modal"]').off('click').click(function(e){
    var id = $(this).attr('data-target');
    var param = {};
    var obj_id = $(this).attr('data-href');
    if(obj_id){
      param = {'id':obj_id};
    }
    $(id+' .modal-footer .btn-primary').prop('disabled',true);
    $(id+' .modal-footer .btn-danger').prop('disabled',true);
    $(id+' .modal-footer .btn-success').prop('disabled',true);
    if(obj_id=='+'){
      $(id+' .modal-header .modal-title').html('<?php echo html_entity_decode($messages['add_title']); ?>');
      $(id+' .modal-footer .btn-danger').hide();
      $(id+' .modal-footer .btn-primary').hide();
      $(id+' .modal-footer .btn-success').show();
    }else if(obj_id.indexOf('del-')>=0){
      $(id+' .modal-header .modal-title').html('<?php echo html_entity_decode($messages['delete_title']); ?>');
      $(id+' .modal-footer .btn-danger').show();
      $(id+' .modal-footer .btn-primary').hide();
      $(id+' .modal-footer .btn-success').hide();
    }else{
      $(id+' .modal-header .modal-title').html('<?php echo html_entity_decode($messages['edit_title']); ?>');
      $(id+' .modal-footer .btn-danger').hide();
      $(id+' .modal-footer .btn-primary').show();
      $(id+' .modal-footer .btn-success').hide();
    }
    $(id+' .modal-body').html('<i class="fa fa-spin fa-spinner"></i> Cargando...');
    $.get($(id+' form').attr('action'),param,function(data){
      response = JSON.parse(data);
      $(id+' .modal-body').html(response.data);
      $(id+' .modal-footer .btn-primary').prop('disabled',false);
      $(id+' .modal-footer .btn-danger').prop('disabled',false);
      $(id+' .modal-footer .btn-success').prop('disabled',false);
    });
  });
}

function imf_<?php echo $class?>_setActions(){
$('#izarusModalChild<?php echo $class; ?>Modal').on('hidden.bs.modal', function () {
    var id = $(this).attr('id');
    $('#'+id+' .modal-body').html('<i class="fa fa-spin fa-spinner"></i> Cargando...');
  })

  $.each($('.ajaxform'),function(index,value){
    $(value).ajaxForm({beforeSubmit: function(){
      $(value).children('.modal-footer').children('.espere').show();
      $(value).children('.modal-footer').children('.botones').hide();
    },success: function(responseText){
      response = JSON.parse(responseText);
      $(value).children('.modal-footer').children('.espere').hide();
      $(value).children('.modal-footer').children('.botones').show();
      if(response.status=='ERROR'){
        $(value).children('.modal-body').prepend('<div class="alert alert-danger">Ocurrió un error al intentar realizar esa acción.</div>');
      }else if(response.status=='OK'){
        $(value).parent().parent().parent().modal('hide');
        $('#izarusModalChild<?php echo $class; ?>Load').html(response.data);
          imf_<?php echo $class?>_updateClics();
      }else{
        $(value).children('.modal-body').html(response.data);
      }
    }});
  });
}


$(function(){
  var modal = $('<div>').html($('#izarusModalChild<?php echo $class; ?>Modal.cloneAtEnd').clone());
  modal.children('#izarusModalChild<?php echo $class; ?>Modal').removeClass('cloneAtEnd');
  $('body').append(modal.html().replace('[form','<form').replace('form]','>').replace('[/form]','</form>'));
  $('#izarusModalChild<?php echo $class; ?>Modal.cloneAtEnd').remove();   

  imf_<?php echo $class?>_updateClics();
  imf_<?php echo $class?>_setActions();  
});
</script>