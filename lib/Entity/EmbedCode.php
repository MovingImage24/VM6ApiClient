<?php

namespace MovingImage\Client\VM6\Entity;

use JMS\Serializer\Annotation\Type;
use MovingImage\Meta\Interfaces\EmbedCodeInterface;

/**
 * Class EmbedCode.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class EmbedCode implements EmbedCodeInterface
{
    const TYPE_JAVASCRIPT = 1;
    const TYPE_IFRAME = 2;
    const TYPE_DIRECT_URL = 3;
    const TYPE_AJAX_JS = 4;
    const TYPE_SWF_URL = 5;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $player_code;

    /**
     * @var string
     */
    private $unlockToken;

    /**
     * @var bool
     */
    private $hiddenControlBar = false;

    /**
     * {@inheritdoc}
     */
    public function getCode($className = 'video-player')
    {
        $code = $this->player_code;

        // HACKHACK: Replace the static width style property with a classname
        $code = preg_replace('#style="(.*?)"#', sprintf('class="%s"', $className), $code);

        // HACKHACK: Use regular expressions to magically append the unlock
        // token to the embed code URL
        if (!is_null($this->unlockToken)) {
            preg_match_all('#https://(.*?)&playerskin=([0-9]+)#', $code, $matches);
            if (count($matches[0]) > 0) {
                $code = str_replace(
                    $matches[0][0],
                    $matches[0][0].'&videounlocktoken2='.$this->unlockToken,
                    $code
                );
            }
        }

        if ($this->hiddenControlBar) {
            $code = $this->setHiddenControlBarAttribute($code);
        }

        return $code;
    }

    public function setUnlockToken($token)
    {
        $this->unlockToken = $token;
    }

    /**
     * If $hidden is true, the returned embedCode from getCode() contains an html attribute
     * which is necessary to hide the control bar in a HTML5 player.
     *
     * Furthermore to make the control bar really hidden,
     * following Javascript object has to be set on the page which contains the player.
     *
     *  var miPlayerPlugins = {
     *      playerAttributeSet: {
     *          enabled: true,
     *          prefix: 'data-'
     *      }
     *  };
     *
     * Method doesn't exist in EmbedCodeInterface, since this method doesn't make sense for VMPro.
     *
     * @param bool $hidden
     */
    public function setHiddenControlBar($hidden)
    {
        $this->hiddenControlBar = $hidden;
    }

    /**
     * Adds an attribute inside the first div which is necessary to hide the control bar.
     *
     * @param string $html
     *
     * @return mixed
     */
    private function setHiddenControlBarAttribute($html)
    {
        return preg_replace('/>/', ' data-control-bar-hidden="true">', $html, 1);
    }
}
