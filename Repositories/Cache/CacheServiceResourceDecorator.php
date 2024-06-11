<?php

namespace Modules\Ibooking\Repositories\Cache;

use Modules\Core\Icrud\Repositories\Cache\BaseCacheCrudDecorator;
use Modules\Ibooking\Repositories\ServiceResourceRepository;

class CacheServiceResourceDecorator extends BaseCacheCrudDecorator implements ServiceResourceRepository
{
    public function __construct(ServiceResourceRepository $serviceresource)
    {
        parent::__construct();
        $this->entityName = 'ibooking.serviceresources';
        $this->repository = $serviceresource;
    }
}
