<?php
namespace Backedin\BackpackTraits;

trait OriginalRoles{
 /**
  * roles - Overrides User::roles
  * @return Illuminate\Database\Eloquent\Relations\BelongsToMany relation
  */
 public function roles()
 {
  return $this->belongsToMany(
      config('laravel-permission.models.role'),
      config('laravel-permission.table_names.user_has_roles'),
      'user_id', //default: <class lower case name>_id
      'role_id'
  );
 }
}
