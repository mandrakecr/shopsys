<?php

namespace Shopsys\FrameworkBundle\Form;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormRenderingConfigurationExtension extends AbstractTypeExtension
{
    public const DISPLAY_FORMAT_MULTIDOMAIN_ROWS_NO_PADDING = 'multidomain_form_rows_no_padding';

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        $view->vars['macro'] = $options['macro'];
        $view->vars['icon_title'] = $options['icon_title'];
        $view->vars['display_format'] = $options['display_format'];
        $view->vars['js_container'] = $options['js_container'];
        $view->vars['is_plugin_data_group'] = $options['is_plugin_data_group'] ?? false;
        $view->vars['render_form_row'] = $options['render_form_row'];

        if (array_key_exists('is_plugin_data_group', $options)) {
            $message = 'Using the "is_plugin_data_group" option in forms has been deprecated since Shopsys Framework 7.2 and it will be removed eventually.';

            trigger_error($message, E_USER_DEPRECATED);
        }
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined('is_plugin_data_group')
            ->setDefaults([
                'macro' => null,
                'icon_title' => null,
                'display_format' => null,
                'js_container' => null,
                'render_form_row' => true,
            ]);
    }

    /**
     * @return string
     */
    public function getExtendedType()
    {
        return FormType::class;
    }
}
