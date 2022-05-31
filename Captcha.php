<?php

/**
 * @package    phpListings
 * @author     phpListings Team <info@phplistings.com>
 * @copyright  2022 phpListings.com
 * @license    https://www.phplistings.com/eula
 */

namespace App\Src\Form\Type;

class Captcha
    extends Type
{

    public function __construct($name, array $options = [], \App\Src\Form\Builder $form = null)
    {
        parent::__construct($name, $options);

        layout()->addJs('<script src="https://js.hcaptcha.com/1/api.js" async defer></script>');
    }

    public function setValue($value)
    {
        $this->value = '<div class="h-captcha" data-sitekey="' . e(config()->general->captcha_site_key) . '"></div>';

        return $this;
    }

    public function resetValue()
    {
        return null;
    }

    public function getConstraints()
    {
        return [function($value, $context = null) {
            if (isset($context['h-captcha-response'])) {
                if (false !== $response = file_get_contents('https://hcaptcha.com/siteverify?secret=' . config()->general->captcha_secret_key . '&response=' . $context['h-captcha-response'])) {
                    $response = json_decode($response, true);
                    if ($response["success"] === true) {
                        return;
                    }
                }
            }

            throw new \App\Src\Validation\ValidatorException(__('form.validation.captcha'));
        }];
    }

    public function getOutputableValue($schema = false)
    {
        return null;
    }

    public function exportValue()
    {
        return '';
    }

    public function importValue($value, $fieldModel, $locale)
    {
        return '';
    }

    public function render()
    {
        return view('form/field/custom', $this);
    }

}
