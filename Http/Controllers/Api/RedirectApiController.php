<?php

namespace Modules\Iredirect\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Icrud\Controllers\BaseCrudController;
use Modules\Iredirect\Entities\Redirect;
use Modules\Iredirect\Repositories\RedirectRepository;

class RedirectApiController extends BaseCrudController
{
  public $model;
  public $modelRepository;

  public function __construct(Redirect $model, RedirectRepository $modelRepository)
  {
    $this->model = $model;
    $this->modelRepository = $modelRepository;
  }
}
