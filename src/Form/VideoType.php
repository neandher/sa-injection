<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class VideoType extends AbstractType
{
    /**
     * Modelo correto a se aplicar
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Seu Nome',
            ])
            ->add('video', FileType::class, [
                'label' => 'Envie seu vídeo no formato .mp4',
                'constraints' => [
                    new NotBlank(),
                    new File([
                        'mimeTypes' => 'video/mp4'
                    ])
                ]
            ])
        ;
    }

    /**
     * Modelo vulnerável a Injection
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    /*public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Seu Nome',
            ])
            ->add('videoFile', FileType::class, [
                'label' => 'Envie seu vídeo',
                'constraints' => [
                    new NotBlank(),
                ],
                'mapped' => false
            ]);
    }*/

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class
        ]);
    }
}
