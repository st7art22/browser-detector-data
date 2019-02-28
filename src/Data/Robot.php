<?php
/**
 * @author Serhii Nekhaienko <sergey.nekhaenko@gmail.com>
 * @license GPL
 * @copyright Serhii Nekhaienko &copy 2018
 * @version 4.0.0
 * @project endorphin-studio/browser-detector
 */

namespace EndorphinStudio\Detector\Data;

/**
 * Class Robot
 * Result of robot detection
 * @package EndorphinStudio\Detector\Data
 */
class Robot extends AbstractData
{
    /**
     * Get robot owner
     * @return string
     */
    public function getOwner(): string
    {
        return $this->owner;
    }

    /**
     * Set robot owner
     * @param string $owner
     */
    public function setOwner(string $owner)
    {
        $this->owner = $owner;
    }

    /** @var string Owner robot */
    protected $owner;

    /**
     * Get robot homepage
     * @return string
     */
    public function getHomepage(): string
    {
        return $this->homepage;
    }

    /**
     * Get bad flag
     * @return bool
     */
    public function isBad(): bool
    {
        return $this->isBad;
    }

    /**
     * Set Bad flag
     * @param bool $isBad
     */
    public function setIsBad(bool $isBad)
    {
        $this->isBad = $isBad;
    }

    /**
     * Set robot homepage
     * @param string $homepage
     */
    public function setHomepage(string $homepage)
    {
        $this->homepage = $homepage;
    }

    /** @var string Robot homepage */
    protected $homepage;

    /**
     * @var bool Flag which say us is this bot is bad
     */
    protected $isBad = false;
}