<?php

namespace Backedin\BackpackTraits;

trait FreezeForm{
 /**
  * Display the specified resource.
  *
  * @param int $id
  *
  * @return Response
  */
 public function show($id)
 {
     $this->crud->hasAccessOrFail('show');

     // get the info for that entry
     $this->data['entry'] = $this->crud->getEntry($id);
     $this->data['crud'] = $this->crud;
     $this->data['saveAction'] = $this->getSaveAction();
     $this->data['fields'] = $fields = $this->crud->getUpdateFields($id);
     $this->data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;

     $this->data['id'] = $id;

     if($this->crud->eagerLoad && !empty($this->crud->eagerLoad))
     foreach($this->crud->eagerLoad as $relation_name)
     {
      if($this->data['entry'] && $rel = $this->data['entry']->{$relation_name})
      {
       foreach($this->data['fields'] as $field_name => $field)
       {
        if(starts_with($field_name, $relation_name))
        {
         $column_name = str_replace("{$relation_name}.", '', $field_name);

         $this->data['fields'][$field_name]['value'] = $rel->{$column_name};
        }
       }
      }
     }

     $this->data['fields'] = collect($this->data['fields'])->map(function($field) {
      $attributes = [];
      $wrapperAttributes = [];

      if(isset($fields['attributes']) && $attributes = $fields['attributes']){}
      if(isset($fields['wrapperAttributes']) && $wrapperAttributes = $fields['wrapperAttributes']){}

      if($field['type']=='wysiwyg')
       $field['type'] = 'render_html';

      if($field['type']=='upload')
        $field['type'] = 'text';

      if($field['type']=='bootstrap_multiple')
       $field['type'] = 'hidden';

      if($field['type']=='select_from_array')
       $field['type'] = 'select2_from_array';

      $attributes['disabled'] = 'disabled';
      $wrapperAttributes['class'] = 'form-group col-md-12 freezed-form';

      $field['attributes'] = $attributes;
      $field['wrapperAttributes'] = $wrapperAttributes;

      return $field;
     });

     // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
     return view($this->crud->getShowView(), $this->data);
 }
}
