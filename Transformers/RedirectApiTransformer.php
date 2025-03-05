<?php

namespace Modules\Iredirect\Transformers;

use Modules\Core\Icrud\Transformers\CrudResource;

class RedirectApiTransformer extends CrudResource
{
  /**
   * Method to merge values with response
   */
  public function modelAttributes($request)
  {
    return [];
  }
}
