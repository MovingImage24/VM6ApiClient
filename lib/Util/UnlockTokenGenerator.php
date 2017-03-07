<?php

namespace MovingImage\Client\VM6\Util;

/**
 * Class UnlockTokenGenerator.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class UnlockTokenGenerator
{
    /**
     * @var string
     */
    private $signingKey;

    /**
     * @var int
     */
    private $defaultExpiration;

    /**
     * UnlockTokenGenerator constructor.
     *
     * @param string $signingKey
     * @param int    $defaultExpiration
     */
    public function __construct($signingKey, $defaultExpiration = 300)
    {
        $this->signingKey = $signingKey;
        $this->defaultExpiration = $defaultExpiration;
    }

    /**
     * @param int      $videoId
     * @param int      $ipRestriction
     * @param int|null $expiration
     *
     * @return string
     */
    public function generate($videoId, $ipRestriction = 0, $expiration = null)
    {
        $expiration = !is_null($expiration)
            ? $expiration
            : $this->defaultExpiration;

        $expirationTime = time() + $expiration;

        return $expirationTime.
            '_'.$ipRestriction.
            '_'.md5($this->signingKey.
            '_'.$videoId.
            '_'.$expirationTime.
            '_'.$ipRestriction);
    }
}
