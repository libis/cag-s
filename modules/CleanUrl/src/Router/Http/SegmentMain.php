<?php
namespace CleanUrl\Router\Http;

use const CleanUrl\SLUG_MAIN_SITE;

use Zend\Router\Http\Segment;

/**
 * Segment route with a check for main site to remove "/s/site-slug".
 */
class SegmentMain extends Segment
{
    public function assemble(array $params = [], array $options = [])
    {
        if($_SERVER['HTTP_HOST'] == 'cagnet.be'):
          $sitepath = 'start';
        else:
          $sitepath = 'bulskampveld';
        endif;
        return $sitepath && isset($params['site-slug']) && $params['site-slug'] === $sitepath
            ? ''
            : parent::assemble($params, $options);
    }
}
