<?php
/**
 * Created by PhpStorm.
 * User: dipet
 * Date: 2017. 01. 14.
 * Time: 17:50
 */

namespace AppBundle\Form;


use AppBundle\Entity\BlogPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_imgURI', FileType::class, [
                'label' => 'Image (JPEG or PNG file)',
                'required' => false
            ])
            ->add('_title', TextType::class, ['attr'=> ['placeholder' => 'Add title...' ]])
            ->add('_tags',TextType::class,['attr'=> ['placeholder' => 'Add tags separated by commas...' ]])
            ->add('_content', TextareaType::class, [
                'attr'=> [
                    'rows' => 10,
                    'placeholder' => 'Add content...',
                    'class' => 'ckeditor'
                ]
            ])
            ->add('Save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'=>BlogPost::class
        ));
    }
}