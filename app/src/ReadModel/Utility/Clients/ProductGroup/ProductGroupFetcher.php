<?php

declare(strict_types=1);

namespace App\ReadModel\Utility\Clients\ProductGroup;

use App\ReadModel\Utility\Clients\ProductGroup\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ProductGroupFetcher
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
     * ProductGroupFetcher constructor.
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
                'name',
                'guid'
            )
            ->from('utility_clients_product_groups')
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
                'name',
                'guid'
            )
            ->from('utility_clients_product_groups')
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
                'pg.id',
                'pg.name',
                'pg.guid',
                'pg.client_id',
                'c.name client_name',
                '(SELECT COUNT(ch.id) FROM utility_clients_product_groups_charges ch WHERE ch.product_group_id = pg.id) AS charges_count'
            )
            ->from('utility_clients_product_groups', 'pg')
            ->innerJoin('pg', 'utility_clients_clients', 'c', 'pg.client_id = c.id');

        if ($filter->id) {
            $qb->andWhere($qb->expr()->eq('pg.id', ':id'));
            $qb->setParameter(':id', $filter->id);
        }

        if ($filter->client) {
            $qb->andWhere('pg.client_id = :client');
            $qb->setParameter(':client', $filter->client);
        }

        if ($filter->name) {
            $qb->andWhere($qb->expr()->like('LOWER(pg.name)', ':name'));
            $qb->setParameter(':name', '%' . mb_strtolower($filter->name) . '%');
        }

        if ($filter->guid) {
            $qb->andWhere($qb->expr()->like('LOWER(pg.guid)', ':guid'));
            $qb->setParameter(':guid', '%' . mb_strtolower($filter->guid) . '%');
        }

        if ($filter->sites) {
            $qb->innerJoin('pg', 'utility_clients_sites_product_groups', 'mm', 'mm.product_group_id = pg.id');
            $qb->andWhere('mm.site_id IN (:sites)');
            $qb->setParameter(':sites', $filter->sites, Connection::PARAM_INT_ARRAY);
        }

        if (!\in_array($sort, ['id', 'name', 'created_at'], true)) {
            throw new \UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb->orderBy('pg.' . $sort, $direction === 'desc' ? 'desc' : 'asc');

        $pagination = $this->paginator->paginate($qb, $page, $size);

        $productGroups = (array) $pagination->getItems();
        $sites = $this->batchLoadSites(array_column($productGroups, 'id'));

        $pagination->setItems(array_map(static function (array $productGroup) use ($sites) {
            return array_merge($productGroup, [
                'sites' => array_filter($sites, static function (array $site) use ($productGroup) {
                    return $site['product_group_id'] === $productGroup['id'];
                }),
            ]);
        }, $productGroups));

        return $pagination;
    }

    /**
     * @param array $ids
     * @return array
     */
    private function batchLoadSites(array $ids): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                's.id',
                's.name',
                'mm.product_group_id'
            )
            ->from('utility_clients_sites', 's')
            ->innerJoin('s', 'utility_clients_sites_product_groups', 'mm', 'mm.site_id = s.id')
            ->andWhere('mm.product_group_id IN (:product_group_ids)')
            ->setParameter(':product_group_ids', $ids, Connection::PARAM_INT_ARRAY)
            ->orderBy('name')
            ->execute();

        return $stmt->fetchAll(FetchMode::ASSOCIATIVE);
    }
}
