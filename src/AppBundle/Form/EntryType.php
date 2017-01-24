<?php
/**
 * Created by PhpStorm.
 * User: dipet
 * Date: 2017. 01. 19.
 * Time: 0:57
 */

namespace AppBundle\Form;


use AppBundle\Entity\Entry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntryType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Reply',
                'attr' => [
                    'placeholder' => 'Leave a reply...',
                    'class' => 'ckeditor',
                    'rows' => '20',
                    'cols' => '80',
                ]

            ])
            ->add('Submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entry::class
        ]);
    }
}