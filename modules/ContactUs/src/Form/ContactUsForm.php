<?php
namespace ContactUs\Form;

use Zend\Filter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Validator;
use Zend\Http\PhpEnvironment\RemoteAddress;

class ContactUsForm extends Form
{
    public function init()
    {
        $question = $this->getOption('question');
        $checkAnswer = $this->getOption('checkAnswer');

        $_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // "To" is used instead of "email" to avoid some basic spammers.
        $this->add([
            'name' => 'from',
            'type' => Element\Email::class,
            'options' => [
                'label' => 'E-mail: (verplicht)', // @translate
            ],
            'attributes' => [
                'id' => 'from',
                'required' => true,
            ],
        ]);

        $this->add([
            'name' => 'name',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Naam: (verplicht)', // @translate
            ],
            'attributes' => [
                'id' => 'name',
                'required' => false,
            ],
        ]);

        $value = '';$aanvraag = false;
        if(isset($_GET['id']) && isset($_GET['aanvraag'])):
          if($_GET['aanvraag']):
            $aanvraag = true;
            $value = "Graag, had ik een hoge resolutieversie van object ".$_GET['id']." en motiveer deze aanvraag als volgt: (publicatie, verzameling, commercieel, niet-commercieel…). Door deze aanvraag te verzenden, beloof ik de wetgeving over intellectuele eigendomsrechten te respecteren.";
          endif;
        endif;

        $this->add([
            'name' => 'message',
            'type' => Element\Textarea::class,
            'options' => [
                'label' => 'Bericht', // @translate
            ],
            'attributes' => [
                'id' => 'message',
                'required' => true,
                'value' => $value
            ],
        ]);

        if($aanvraag):
          $this->add([
              'name' => 'motivation',
              'type' => Element\MultiCheckbox::class,

              'options' => [
                  'label' => 'Motivatie: (verplicht)', // @translate
                  'value_options' => array(
                    'publicatie' => 'Publicatie',
                    'verzameling' => 'Verzameling',
                    'niet-commercieel' => 'Niet-commercieel',
                    'commercieel' => 'Commercieel',
                    'andere' => 'Andere',
                  ),
              ],
              'attributes' => [
                  'class' => 'motivation'
              ],
          ]);

          $this->add([
              'name' => 'andere-motivatie',
              'type' => Element\Text::class,
              'options' => [
                  'label' => 'Andere:', // @translate
              ],
              'attributes' => [
                  'id' => 'andere',
                  'required' => false,
              ],
          ]);
        endif;

        $this->add([
            'name' => 'newsletter',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Ik wil graag de nieuwsbrief en/of andere activiteiten van CAG en ICAG ontvangen via mail', // @translate
            ],
            'attributes' => [
                'id' => 'newsletter',
                'required' => false,
            ],
        ]);

        $this->add([
            'name' => 'privacy',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Ik ga akkoord met de privacyverklaring', // @translate
            ],
            'attributes' => [
                'id' => 'privacy',
                'required' => false,
            ],
        ]);

        $mngr = $this->getFormFactory()->getFormElementManager();
        $settings = $mngr->getServiceLocator()->get('Omeka\Settings');
        $siteKey = $settings->get('recaptcha_site_key');
        $secretKey = $settings->get('recaptcha_secret_key');

        $element = $mngr->get('Omeka\Form\Element\Recaptcha', [
                  'site_key' => $siteKey,
                  'secret_key' => $secretKey,
                  'remote_ip' => (new RemoteAddress)->getIpAddress(),
                ]);
        $this->add($element);


        if ($question) {
            $this->add([
                'name' => 'answer',
                'type' => Element\Text::class,
                'options' => [
                    'label' => $question,
                ],
                'attributes' => [
                    'id' => 'answer',
                    'required' => true,
                ],
            ]);

            $this->add([
                'name' => 'check',
                'type' => Element\Hidden::class,
                'attributes' => [
                    'value' => substr(md5($question), 0, 16),
                ],
            ]);
        }

        $this->add([
            'name' => 'submit',
            'type' => Element\Submit::class,
            'attributes' => [
                'id' => 'submit',
                'class' => 'btn',
                'value' => 'Verzenden', // @translate
            ],
        ]);

        $inputFilter = $this->getInputFilter();
        $inputFilter->add([
            'name' => 'message',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);
        if ($question) {
            $inputFilter->add([
                'name' => 'answer',
                'required' => true,
                'filters' => [
                    ['name' => Filter\StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => Validator\Callback::class,
                        'options' => [
                            'callback' => function ($answer) use ($checkAnswer) {
                                return $answer === $checkAnswer;
                            },
                            'callbackOptions' => [
                                'checkAnswer' => $checkAnswer,
                            ],
                        ],
                    ],
                ],
            ]);
        }
    }
}
