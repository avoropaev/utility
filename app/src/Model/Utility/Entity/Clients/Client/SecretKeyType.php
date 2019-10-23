<?php

declare(strict_types=1);

namespace App\Model\Utility\Entity\Clients\Client;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class SecretKeyType extends StringType
{
    public const NAME = 'utility_clients_client_secret_key';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return string|mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof SecretKey ? $value->getValue() : $value;
    }

    /**
     * @param $value
     * @param AbstractPlatform $platform
     * @return SecretKey|string|mixed|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new SecretKey($value) : null;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
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
