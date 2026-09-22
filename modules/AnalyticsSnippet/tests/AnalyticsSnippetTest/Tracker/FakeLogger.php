<?php declare(strict_types=1);

namespace AnalyticsSnippetTest\Tracker;

/**
 * Minimal replacement for Omeka\Logger: records the errors for assertions.
 */
class FakeLogger
{
    /**
     * @var array
     */
    protected $errors = [];

    public function err($message, $extra = []): void
    {
        $this->errors[] = (string) $message;
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
