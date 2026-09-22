<?php declare(strict_types=1);

namespace AnalyticsSnippetTest\Tracker;

use Omeka\Api\Exception\NotFoundException;

/**
 * Minimal replacement for Omeka\Api\Manager: only read('sites') is used.
 */
class FakeApiManager
{
    /**
     * @var bool
     */
    protected $found;

    public function __construct(bool $found = true)
    {
        $this->found = $found;
    }

    public function read($resource, $data = [], $fileData = [], array $options = [])
    {
        if (!$this->found) {
            throw new NotFoundException(sprintf('Resource "%s" not found.', $resource));
        }
        return null;
    }
}
