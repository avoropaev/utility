<?php

declare(strict_types=1);

namespace App\ReadModel\Utility\Clients\Site;

use App\ReadModel\Utility\Clients\Site\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class SiteFetcher
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
     * SiteFetcher constructor.
     * @param Connection $connection
     * @param PaginatorInterface $paginator
     */
    public function __construct(Connection $connection, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->paginator = $paginator;
    }

    /**
     * @param int $clientId
     * @return FullView[]|array
     */
    public function listByClient(int $clientId): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'name'
            )
            ->from('utility_clients_sites')
            ->andWhere('client_id = :client_id')
            ->setParameter(':client_id', $clientId)
            ->execute();

        return $stmt->fetchAll(FetchMode::CUSTOM_OBJECT, FullView::class);
    }

    /**
     * @return FullView[]|array
     */
    public function allList(): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'name'
            )
            ->from('utility_clients_sites')
            ->execute();

        return $stmt->fetchAll(FetchMode::CUSTOM_OBJECT, FullView::class);
    }

    /**
     * @param Filter $filter
     * @param int $page
     * @param int $size
     * @param string|null $sort
     * @param string|null $direction
     * @return PaginationInterface
     */
    public function all(Filter $filter, int $page, int $size, ?string $sort, ?string $direction): PaginationInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                's.id',
                's.name',
                's.client_id',
                'c.name client_name',
            )
            ->from('utility_clients_sites', 's')
            ->innerJoin('s', 'utility_clients_clients', 'c', 's.client_id = c.id');

        if ($filter->id) {
            $qb->andWhere($qb->expr()->eq('s.id', ':id'));
            $qb->setParameter(':id', $filter->id);
        }

        if ($filter->client) {
            $qb->andWhere('s.client_id = :client');
            $qb->setParameter(':client', $filter->client);
        }

        if ($filter->name) {
            $qb->andWhere($qb->expr()->like('LOWER(s.name)', ':name'));
            $qb->setParameter(':name', '%' . mb_strtolower($filter->name) . '%');
        }

        if ($filter->productGroups) {
            $qb->innerJoin('s', 'utility_clients_sites_product_groups', 'mm', 'mm.site_id = s.id');
            $qb->andWhere('mm.product_group_id IN (:product_groups_ids)');
            $qb->setParameter(':product_groups_ids', $filter->productGroups, Connection::PARAM_INT_ARRAY);
        }

        if (!\in_array($sort, ['id', 'name', 'created_at'], true)) {
            throw new \UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb->orderBy('s.' . $sort, $direction === 'desc' ? 'desc' : 'asc');

        $pagination = $this->paginator->paginate($qb, $page, $size);

        $sites = (array) $pagination->getItems();
        $productGroups = $this->batchLoadProductGroups(array_column($sites, 'id'));

        $pagination->setItems(array_map(static function (array $site) use ($productGroups) {
            return array_merge($site, [
                'product_groups' => array_filter($productGroups, static function (array $productGroup) use ($site) {
                    return $productGroup['site_id'] === $site['id'];
                }),
            ]);
        }, $sites));

        return $pagination;
    }

    /**
     * @param array $ids
     * @return array
     */
    private function batchLoadProductGroups(array $ids): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'pg.id',
                'pg.name',
                'mm.site_id'
            )
            ->from('utility_clients_product_groups', 'pg')
            ->innerJoin('pg', 'utility_clients_sites_product_groups', 'mm', 'mm.product_group_id = pg.id')
            ->andWhere('mm.site_id IN (:sites_ids)')
            ->setParameter(':sites_ids', $ids, Connection::PARAM_INT_ARRAY)
            ->orderBy('name')
            ->execute();

        return $stmt->fetchAll(FetchMode::ASSOCIATIVE);
    }
}
