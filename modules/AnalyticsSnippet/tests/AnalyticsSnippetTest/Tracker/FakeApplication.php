<?php declare(strict_types=1);

namespace AnalyticsSnippetTest\Tracker;

use Laminas\Mvc\MvcEvent;

/**
 * Minimal replacement for the Application service: only getMvcEvent() is used.
 */
class FakeApplication
{
    /**
     * @var MvcEvent
     */
    protected $mvcEvent;

    public function __construct(MvcEvent $mvcEvent)
    {
        $this->mvcEvent = $mvcEvent;
    }

    public function getMvcEvent(): MvcEvent
    {
        return $this->mvcEvent;
    }
}
