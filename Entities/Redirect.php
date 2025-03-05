<?php

namespace Modules\Iredirect\Entities;

use Laracasts\Presenter\PresentableTrait;
use Modules\Iredirect\Presenters\RedirectPresenter;
use Modules\Core\Icrud\Entities\CrudModel;

class Redirect extends CrudModel
{
  use  PresentableTrait;

  protected $table = 'iredirect__redirects';
  protected static $entityNamespace = 'asgardcms/redirect';

  public $transformer = 'Modules\Iredirect\Transformers\RedirectApiTransformer';
  public $entity = 'Modules\Iredirect\Entities\Redirect';
  public $repository = 'Modules\Iredirect\Repositories\RedirectRepository';
  public $requestValidation = [
    'create' => 'Modules\Iredirect\Http\Requests\CreateRedirectRequest',
    'update' => 'Modules\Iredirect\Http\Requests\UpdateRedirectRequest',
  ];
  protected $fillable = ['from', 'to', 'redirect_type', 'options'];
  protected $presenter = RedirectPresenter::class;
  protected $fakeColumns = ['options'];
  protected $casts = [
    'options' => 'array',
  ];

  public function getOptionsAttribute($value)
  {
    $response = json_decode($value);

    if (is_string($response)) {
      $response = json_decode($response);
    }

    return $response;
  }

  public function __call($method, $parameters)
  {
    //i: Convert array to dot notation
    $config = implode('.', ['asgard.iredirect.config.relations', $method]);

    //i: Relation method resolver
    if (config()->has($config)) {
      $function = config()->get($config);

      return $function($this);
    }

    //i: No relation found, return the call to parent (Eloquent) to handle it.
    return parent::__call($method, $parameters);
  }
}
