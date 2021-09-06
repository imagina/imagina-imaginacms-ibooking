<?php

namespace Modules\Ibooking\Repositories\Cache;

use Modules\Ibooking\Repositories\ResourceRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheResourceDecorator extends BaseCacheDecorator implements ResourceRepository
{
    public function __construct(ResourceRepository $resource)
    {
        parent::__construct();
        $this->entityName = 'ibooking.resources';
        $this->repository = $resource;
    }
}
