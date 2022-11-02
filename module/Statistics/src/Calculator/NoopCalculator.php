<?php

declare(strict_types = 1);

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

class NoopCalculator extends AbstractCalculator
{
    /**
     * @inheritDoc
     */
    protected const UNITS = 'posts';

    /**
     * @var int
     */
    private $userCount = 0;

    /**
     * @var int
     */
    private $postCount = 0;

/**
     * @var [string]
     */
    private $users = array();


    /**
     * @param SocialPostTo $postTo
     */

    protected function doAccumulate(SocialPostTo $postTo): void
    {
        // Noops!
        $this->postCount++;
        $authorid = $postTo->getAuthorId();
        if (!array_key_exists($authorid, $this->users)) {
            $this->userCount++;
            $this->users[$authorid] = 1;
        }     
    }

    /**
     * @inheritDoc
     */
    protected function doCalculate(): StatisticsTo
    {
        $value = $this->userCount > 0
            ? $this->postCount / $this->userCount
            : 0;
        return (new StatisticsTo())->setValue(round($value,2));
    }
}
