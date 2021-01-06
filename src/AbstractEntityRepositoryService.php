<?php

namespace Chaos\Service;

use Chaos\Support\Messenger\Event;

use function Chaos\dispatcher;
use function Chaos\serializer;

/**
 * Class AbstractEntityRepositoryService.
 *
 * @author t(-.-t) <ntd1712@mail.com>
 */
abstract class AbstractEntityRepositoryService implements EntityRepositoryServiceInterface
{
    // <editor-fold defaultstate="collapsed" desc="Magic methods">

    /**
     * {@inheritDoc}
     *
     * @param \Psr\Container\ContainerInterface $container The Container instance.
     * @param object $instance Optional.
     *
     * @return $this
     */
    public function __invoke($container, $instance)
    {
        foreach ($this as $repository) { // iterate through properties
            if (method_exists($repository, '__invoke')) {
                $repository($container, null);
            }
        }

        return $this;
    }

    // </editor-fold>

    /**
     * {@inheritDoc}
     *
     * @param array $criteria Criteria.
     *
     * @return array
     */
    public function paginate(array $criteria)
    {
        $paginator = $this->repository->paginate($criteria);

        $result = [
            'offset' => $paginator->getQuery()->getFirstResult(),
            'perPage' => $paginator->getQuery()->getMaxResults(),
            'total' => $paginator->count()
        ];
        $result['items'] = empty($result['total']) ? [] : $paginator->getIterator()->getArrayCopy();

        return $result;
    }

    /**
     * {@inheritDoc}
     *
     * @param array $criteria Criteria.
     *
     * @return array
     */
    public function search(array $criteria)
    {
        $iterator = $this->repository->search($criteria);

        $result = [
            'total' => $iterator->count()
        ];
        $result['items'] = empty($result['total']) ? [] : $iterator->getArrayCopy();

        return $result;
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $id Identity.
     *
     * @return object
     */
    public function read($id)
    {
        $object = $this->repository->find($id);

        if (empty($object)) {
            throw new Exception\ModelNotFoundException();
        }

        return $object;
    }

    /**
     * {@inheritDoc}
     *
     * @param array $input _POST input.
     * @param array $options Options, like: ['autocommit' => true]
     *
     * @throws \Exception
     *
     * @return object
     */
    public function create(array $input, array $options = ['autocommit' => true])
    {
        $object = serializer()->fromArray($input, $this->repository->getClassName());
        $dispatcher = dispatcher();
        $argv = ['target' => $this, 'object' => $object, 'input' => $input, 'options' => $options];

        $dispatcher->dispatch(new Event('postLoad', $argv));
        $dispatcher->dispatch(new Event('preCreate', $argv));
        $argv['createdRows'] = $this->repository->create($object, $options);
        $dispatcher->dispatch(new Event('postCreate', $argv));

        return $object;
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $id Identity.
     * @param array $input _PUT input.
     * @param array $options Options, like: ['autocommit' => true]
     *
     * @throws \Exception
     *
     * @return object
     */
    public function update($id, array $input, array $options = ['autocommit' => true])
    {
        foreach ($this->repository->identifier as $identifier) {
            if (!isset($input[$identifier])) {
                $input[$identifier] = $id;
            }
        }

        $object = serializer()->fromArray($input, $this->repository->getClassName());
        $dispatcher = dispatcher();
        $argv = ['target' => $this, 'object' => $object, 'input' => $input, 'options' => $options];

        $dispatcher->dispatch(new Event('postLoad', $argv));
        $dispatcher->dispatch(new Event('preUpdate', $argv));
        $argv['updatedRows'] = $this->repository->update($object, $options);
        $dispatcher->dispatch(new Event('postUpdate', $argv));

        return $object;
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $id Identity(s).
     * @param array $options Options, like: ['autocommit' => true]
     *
     * @throws \Exception
     *
     * @return object
     */
    public function delete($id, array $options = ['autocommit' => true])
    {
        $object = is_array($id) ? $this->repository->findBy($id) : $this->repository->find($id);

        if (empty($object)) {
            throw new Exception\ModelNotFoundException();
        }

        $dispatcher = dispatcher();
        $argv = ['target' => $this, 'object' => $object, 'input' => $id, 'options' => $options];

        $dispatcher->dispatch(new Event('preDelete', $argv));
        $argv['deletedRows'] = $this->repository->delete($object, $options);
        $dispatcher->dispatch(new Event('postDelete', $argv));

        return $object;
    }
}
