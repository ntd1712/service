<?php

namespace Chaos\Service;

/**
 * Interface ServiceInterface.
 *
 * A service can call multiple repositories.
 * And one service can also call another service, but will need to manually initialize its used repositories.
 *
 * <code>
 * public function __construct(LookupRepository $lookupRepository,
 *                             DashboardRepository $dashboardRepository,
 *                             DashboardService $dashboardService)
 * {
 *   parent::__construct(
 *     $this->repository = $lookupRepository,
 *     $this->dashboardRepository = $dashboardRepository
 *   );
 *
 *   $this->dashboardService = $dashboardService($this->getContainer(), null);
 * }
 * </code>
 *
 * @author t(-.-t) <ntd1712@mail.com>
 *
 * @property \Chaos\Support\Messenger\EventSubscriberInterface $subscriber
 */
interface ServiceInterface
{
    /**
     * The default `search` method, you can override this in the derived class.
     *
     * @param array $criteria Criteria.
     *
     * @return array
     */
    public function search(array $criteria);

    /**
     * The default `read` method, you can override this in the derived class.
     *
     * @param mixed $id Identity.
     *
     * @return object
     */
    public function read($id);

    /**
     * The default `create` method, you can override this in the derived class.
     *
     * @param array $input _POST input.
     * @param array $options Options, like: ['autocommit' => true]
     *
     * @return object
     */
    public function create(array $input, array $options = []);

    /**
     * The default `update` method, you can override this in the derived class.
     *
     * @param mixed $id Identity.
     * @param array $input _PUT input.
     * @param array $options Options, like: ['autocommit' => true]
     *
     * @return object
     */
    public function update($id, array $input, array $options = []);

    /**
     * The default `delete` method, you can override this in the derived class.
     *
     * @param mixed $id Identity(s).
     * @param array $options Options, like: ['autocommit' => true]
     *
     * @return object
     */
    public function delete($id, array $options = []);
}
