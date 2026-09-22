<?php declare(strict_types=1);

namespace AnalyticsSnippetTest\Tracker;

/**
 * Minimal replacement for Omeka\Settings\Settings and Omeka\Settings\Site.
 */
class FakeSettings
{
    /**
     * @var array
     */
    protected $settings;

    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    public function get($id, $default = null)
    {
        return $this->settings[$id] ?? $default;
    }

    public function set($id, $value): void
    {
        $this->settings[$id] = $value;
    }
}
