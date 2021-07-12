<?php

namespace App\Form;

use App\Entity\Encyclopreneur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EncyclopreneurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"Nom de famille", 'autocomplete'=>"off"],
                'label'=>""
            ])
            ->add('prenoms', TextType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"Prenoms", 'autocomplete'=>"off"],
                'label'=>""
            ])
            ->add('biographie')
            ->add('media1', FileType::class,[
                'attr'=>['class'=>"dropify", 'data-preview' => ".preview"],
                'label' => "Télécharger la photo 1",
                'mapped' => false,
                'multiple' => false,
                'constraints' => [
                    new File([
                        'maxSize' => "1000000k",
                        'mimeTypes' =>[
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => "Votre fichier doit être de type image"
                    ])
                ],
                'required' => false
            ])
            ->add('media2', FileType::class,[
                'attr'=>['class'=>"dropify", 'data-preview' => ".preview"],
                'label' => "Télécharger la photo 2",
                'mapped' => false,
                'multiple' => false,
                'constraints' => [
                    new File([
                        'maxSize' => "1000000k",
                        'mimeTypes' =>[
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => "Votre fichier doit être de type image"
                    ])
                ],
                'required' => false
            ])
            ->add('projets', TextType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"Liste des projets", 'data-role'=>"tagsinput"],
                'label'=>""
            ])
            ->add('tags', TextType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"Les mots clés", 'data-role'=>"tagsinput"],
                'label'=>""
            ])
            ->add('website', TextType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"le lien du site internet", 'autocomplete'=>"off"],
                'label'=>"Site internet",
                'required'=>false
            ])
            ->add('twitter', TextType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"Le compte twitter", 'autocomplete'=>"off"],
                'label'=>"",
                'required'=>false
            ])
            ->add('instagram', TextType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"Le compte instagram", 'autocomplete'=>"off"],
                'label'=>"",
                'required'=>false
            ])
            ->add('facebook', TextType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"Le compte facebook", 'autocomplete'=>"off"],
                'label'=>"",
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Encyclopreneur::class,
        ]);
    }
}
