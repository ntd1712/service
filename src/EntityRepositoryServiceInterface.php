<?php

namespace Chaos\Service;

/**
 * Interface EntityRepositoryServiceInterface.
 *
 * @author t(-.-t) <ntd1712@mail.com>
 *
 * @property \Chaos\Repository\AbstractEntityRepository|\Chaos\Repository\EntityRepositoryInterface $repository
 */
interface EntityRepositoryServiceInterface extends ServiceInterface
{
    /**
     * The default `paginate` method, you can override this in the derived class.
     * Please note that all inputs are sanitised before they are passed in.
     *
     * @param array $criteria Criteria.
     *
     * @return array
     */
    public function paginate(array $criteria);
}
