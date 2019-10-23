<?php

declare(strict_types=1);

namespace App\ReadModel\Utility\Clients\Client;

use App\ReadModel\Utility\Clients\Client\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ClientFetcher
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * ClientFetcher constructor.
     * @param Connection $connection
     * @param PaginatorInterface $paginator
     */
    public function __construct(Connection $connection, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->paginator = $paginator;
    }

    /**
     * @return FullView[]|array
     */
    public function allList(): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'name',
                'secret_key',
                'created_at'
            )
            ->from('utility_clients_clients')
            ->execute();

        return $stmt->fetchAll(FetchMode::CUSTOM_OBJECT, FullView::class);
    }

    /**
     * @param Filter $filter
     * @param int $page
     * @param int $size
     * @param string $sort
     * @param string $direction
     * @return PaginationInterface
     */
    public function all(Filter $filter, int $page, int $size, string $sort, string $direction): PaginationInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'c.id',
                'c.name',
                'c.secret_key',
                'c.created_at'
            )
            ->from('utility_clients_clients', 'c');

        if ($filter->id) {
            $qb->andWhere($qb->expr()->eq('c.id', ':id'));
            $qb->setParameter(':id', $filter->id);
        }

        if ($filter->name) {
            $qb->andWhere($qb->expr()->like('LOWER(c.name)', ':name'));
            $qb->setParameter(':name', '%' . mb_strtolower($filter->name) . '%');
        }

        if ($filter->secretKey) {
            $qb->andWhere($qb->expr()->like('LOWER(c.secret_key)', ':secretKey'));
            $qb->setParameter(':secretKey', '%' . mb_strtolower($filter->secretKey) . '%');
        }

        if (!\in_array($sort, ['id', 'name', 'created_at'], true)) {
            throw new \UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($qb, $page, $size);
    }
}
