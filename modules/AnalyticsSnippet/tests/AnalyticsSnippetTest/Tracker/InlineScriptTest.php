<?php declare(strict_types=1);

namespace AnalyticsSnippetTest\Tracker;

use AnalyticsSnippet\Tracker\InlineScript;

class InlineScriptTest extends TrackerTestCase
{
    const SNIPPET = '<script>/* main */</script>';
    const SNIPPET_SITE = '<script>/* site */</script>';
    const SNIPPET_ADMIN = '<script>/* admin */</script>';

    const PAGE = '<!DOCTYPE html><html><head><title>T</title></head><body><p>C</p></body></html>';

    protected function track(array $options, string $content = self::PAGE, string $type = 'html'): string
    {
        $services = $this->buildServices($options);
        $viewEvent = $this->buildViewEvent($content);

        $tracker = new InlineScript();
        $tracker->setServiceLocator($services);
        $tracker->track('http://example.org/s/test/page', $type, $viewEvent);

        return (string) $viewEvent->getResponse()->getContent();
    }

    public function testSnippetIsAppendedBeforeHeadEnd(): void
    {
        $result = $this->track([
            'settings' => ['analyticssnippet_inline_public' => self::SNIPPET],
        ]);

        $this->assertStringContainsString(self::SNIPPET . '</head>', $result);
        $this->assertStringNotContainsString(self::SNIPPET . '</body>', $result);
    }

    public function testSnippetIsAppendedBeforeBodyEnd(): void
    {
        $result = $this->track([
            'settings' => [
                'analyticssnippet_inline_public' => self::SNIPPET,
                'analyticssnippet_position' => 'body_end',
            ],
        ]);

        $this->assertStringContainsString(self::SNIPPET . '</body>', $result);
        $this->assertStringNotContainsString(self::SNIPPET . '</head>', $result);
    }

    public function testNoSnippetKeepsContentUnchanged(): void
    {
        $result = $this->track([]);

        $this->assertSame(self::PAGE, $result);
    }

    public function testSiteSettingOverridesMainSetting(): void
    {
        $result = $this->track([
            'settings' => ['analyticssnippet_inline_public' => self::SNIPPET],
            'site_settings' => ['analyticssnippet_inline_public' => self::SNIPPET_SITE],
        ]);

        $this->assertStringContainsString(self::SNIPPET_SITE, $result);
        $this->assertStringNotContainsString(self::SNIPPET, $result);
    }

    public function testEmptySiteSettingFallsBackToMainSetting(): void
    {
        $result = $this->track([
            'settings' => ['analyticssnippet_inline_public' => self::SNIPPET],
            'site_settings' => ['analyticssnippet_inline_public' => ''],
        ]);

        $this->assertStringContainsString(self::SNIPPET, $result);
    }

    public function testSitePositionIsUsedWithSiteSnippet(): void
    {
        $result = $this->track([
            'settings' => ['analyticssnippet_position' => 'head_end'],
            'site_settings' => [
                'analyticssnippet_inline_public' => self::SNIPPET_SITE,
                'analyticssnippet_position' => 'body_end',
            ],
        ]);

        $this->assertStringContainsString(self::SNIPPET_SITE . '</body>', $result);
    }

    public function testUnknownSiteSlugFallsBackToMainSetting(): void
    {
        $result = $this->track([
            'settings' => ['analyticssnippet_inline_public' => self::SNIPPET],
            'site_settings' => ['analyticssnippet_inline_public' => self::SNIPPET_SITE],
            'site_exists' => false,
        ]);

        $this->assertStringContainsString(self::SNIPPET, $result);
        $this->assertStringNotContainsString(self::SNIPPET_SITE, $result);
    }

    public function testNoRouteMatchUsesMainSetting(): void
    {
        $result = $this->track([
            'settings' => ['analyticssnippet_inline_public' => self::SNIPPET],
            'site_settings' => ['analyticssnippet_inline_public' => self::SNIPPET_SITE],
            'route_match' => null,
        ]);

        $this->assertStringContainsString(self::SNIPPET, $result);
        $this->assertStringNotContainsString(self::SNIPPET_SITE, $result);
    }

    public function testAdminRouteUsesAdminSnippet(): void
    {
        $result = $this->track([
            'settings' => [
                'analyticssnippet_inline_public' => self::SNIPPET,
                'analyticssnippet_inline_admin' => self::SNIPPET_ADMIN,
            ],
            'route_match' => ['__ADMIN__' => true],
        ]);

        $this->assertStringContainsString(self::SNIPPET_ADMIN, $result);
        $this->assertStringNotContainsString(self::SNIPPET, $result);
    }

    public function testAdminRouteWithoutAdminSnippetKeepsContentUnchanged(): void
    {
        $result = $this->track([
            'settings' => ['analyticssnippet_inline_public' => self::SNIPPET],
            'route_match' => ['__ADMIN__' => true],
        ]);

        $this->assertSame(self::PAGE, $result);
    }

    public function testMissingEndTagLogsErrorAndKeepsContentUnchanged(): void
    {
        $content = '<!DOCTYPE html><html><p>No end tag</p>';
        $result = $this->track([
            'settings' => ['analyticssnippet_inline_public' => self::SNIPPET],
        ], $content);

        $this->assertSame($content, $result);
        $errors = $this->logger->errors();
        $this->assertCount(1, $errors);
        $this->assertStringContainsString('http://example.org/s/test/page', $errors[0]);
    }

    /**
     * @dataProvider providerNotHtmlTypes
     */
    public function testNotHtmlTypeIsIgnored(string $type): void
    {
        $result = $this->track([
            'settings' => ['analyticssnippet_inline_public' => self::SNIPPET],
        ], self::PAGE, $type);

        $this->assertSame(self::PAGE, $result);
    }

    public function providerNotHtmlTypes(): array
    {
        return [
            'json' => ['json'],
            'xml' => ['xml'],
            'undefined' => ['undefined'],
            'error' => ['error'],
        ];
    }
}
