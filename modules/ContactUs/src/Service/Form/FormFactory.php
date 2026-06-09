<?php
namespace ContactUs\Service\Form;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class FormFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, ?array $options = null)
    {
        $options = $options ?? [];

        // Inject recaptcha keys so the form doesn't need $services
        $settings = $services->get('Omeka\Settings');
        $options['recaptcha_site_key'] = $settings->get('recaptcha_site_key');
        $options['recaptcha_secret_key'] = $settings->get('recaptcha_secret_key');
        
        $form = new $requestedName(null, $options ?? []);
        return $form;
    }
}
