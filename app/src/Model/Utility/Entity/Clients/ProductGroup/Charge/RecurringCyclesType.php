<?php

declare(strict_types=1);

namespace App\Model\Utility\Entity\Clients\ProductGroup\Charge;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class RecurringCyclesType extends JsonType
{
    public const NAME = 'utility_clients_product_groups_charge_recurring_cycles';

    /**
     * @param $value
     * @param AbstractPlatform $platform
     * @return false|mixed|string|null
     * @throws \Doctrine\DBAL\Types\ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof ArrayCollection) {
            $data = array_map([self::class, 'deserialize'], $value->toArray());
        } else {
            $data = $value;
        }

        return parent::convertToDatabaseValue($data, $platform);
    }

    /**
     * @param $value
     * @param AbstractPlatform $platform
     * @return ArrayCollection|mixed|null
     * @throws \Doctrine\DBAL\Types\ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (!is_array($data = parent::convertToPHPValue($value, $platform))) {
            return $data;
        }

        return new ArrayCollection(array_filter(array_map([self::class, 'serialize'], $data)));
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @param RecurringCycle $recurringCycle
     * @return array
     */
    private static function deserialize(RecurringCycle $recurringCycle): array
    {
        return $recurringCycle->toArray();
    }

    /**
     * @param array $recurringCycleArray
     * @return RecurringCycle|null
     */
    private static function serialize(array $recurringCycleArray): ?RecurringCycle
    {
        return RecurringCycle::fromArray($recurringCycleArray);
    }

    /**
     * @param AbstractPlatform $platform
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }
}
