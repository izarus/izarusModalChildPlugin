<?php

class izarusModalChildActions extends sfActions
{
  public function executeProcessForm(sfWebRequest $request){
    if(!$request->getParameter('imcd'))
      die;
    $secret = json_decode(base64_decode(strrev(str_replace('_','a',$request->getParameter('imcd')))),true);
    if(!isset($secret['c']) || !isset($secret['f']) || !isset($secret['opi']) || !isset($secret['pi']))
      die;
    $clase = $secret['c'];
    $table_name = $clase.'Table';
    $form_name = $secret['f'];
    $form = new $form_name();
    $obj_name = $form->getName();

    $clase = $secret['c'];
    $del_id = explode('-',$request->getParameter('id'));
    $id = $request->getParameter('id');

    if(count($del_id)==2){
      //eliminacion "del-id"
      $id = $del_id[1];
    }else if($request->isMethod('post')){
      $id = $request->getParameter($obj_name);
      $id = $id['id'];
    }

    if($id=='+' || !$id){
      $form = new $form_name();
    }else{
      $obj = $table_name::getInstance()->findOneById($id);
      if(!$obj || ($obj && $obj->get($secret['opi'])!= $secret['pi']))
        return $this->renderText(json_encode(array('status'=>'Forbidden','data'=>'Forbidden')));
      $form = new $form_name($obj);
    }

    if($request->isMethod('post')){
      $form->bind($request->getParameter($obj_name));
      $form->updateObject();

      try{
        //Eliminar
        if($del_id[0]=='del' && !empty($id)){
          if($obj && $obj->get($secret['opi'])==$secret['pi']){
            $obj->delete();
            $componet_data = $this->getUser()->getAttribute('izarusModalChild'.$clase.$form_name);
            return $this->renderText(json_encode(array('status'=>'OK','data'=>$this->getPartial('izarusModalChild/table',array(
              'cols'=>$componet_data['c'],
              'collection'=>$table_name::getInstance()->createQuery()->where($secret['opi'].' = ?',$secret['pi'])->execute(),
              'messages'=>$componet_data['m'],
              'class'=>$clase,
            )))));
          }
          return $this->renderText(json_encode(array('status'=>'ERROR')));

        //Insertar o Actualizar
        }else{
          if($form->isValid()){
            $form->updateObject();
            $obj = $form->getObject();
            $obj->set($secret['opi'],$secret['pi']);
            $obj->save();
            
            $componet_data = $this->getUser()->getAttribute('izarusModalChild'.$clase.$form_name);           
            return $this->renderText(json_encode(array('status'=>'OK','data'=>$this->getPartial('izarusModalChild/table',array(
              'cols'=>$componet_data['c'],
              'collection'=>$table_name::getInstance()->createQuery()->where($secret['opi'].' = ?',$secret['pi'])->execute(),
              'messages'=>$componet_data['m'],
              'class'=>$clase,
            )))));
          }
        }
      }catch(Exception $e){
        return $this->renderText(json_encode(array('status'=>'ERROR')));
      }

    }else{
      //Eliminación
      if(count($del_id)==2){
        $data = array();
        $data['form'] = new $form_name($obj);
        return $this->renderText(json_encode(array('status'=>'FORM','data'=>$this->getPartial('izarusModalChild/deleteModal',$data))));
      }
    }

    return $this->renderText(json_encode(array('status'=>'FORM','data'=>$form.'')));
  }

}