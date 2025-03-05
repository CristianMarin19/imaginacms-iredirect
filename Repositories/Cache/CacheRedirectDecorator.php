<?php

namespace Modules\Iredirect\Repositories\Cache;

use Modules\Core\Icrud\Repositories\Cache\BaseCacheCrudDecorator;
use Modules\Iredirect\Repositories\Collection;
use Modules\Iredirect\Repositories\RedirectRepository;

class CacheRedirectDecorator extends BaseCacheCrudDecorator implements RedirectRepository
{
  public function __construct(RedirectRepository $redirect)
  {
    parent::__construct();
    $this->entityName = 'redirects';
    $this->repository = $redirect;
  }
}
