<?php
/**
 * Created by PhpStorm.
 * User: dipet
 * Date: 2016. 12. 30.
 * Time: 18:13
 */

namespace AppBundle\Form;


use AppBundle\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['always_empty']) {
            $view->vars['value'] = '';
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_content',TextareaType::class,['label' => false])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'always_empty' => true,
            'trim' => false,
            'data_class'=>Comment::class
        ));
    }
}