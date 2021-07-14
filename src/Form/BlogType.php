<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\Thematique;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"Le titre du service", 'autocomplete'=>"off"],
                'label'=>"Titre"
            ])
            ->add('contenu')
            ->add('media', FileType::class,[
                'attr'=>['class'=>"dropify", 'data-preview' => ".preview"],
                'label' => "Télécharger l'illustration",
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
            ->add('tags', TextType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"Les mots clés", 'data-role'=>"tagsinput"],
                'label'=>""
            ])
            //->add('slug')
            //->add('createdAt')
            //->add('updatedAt')
            //->add('createdBy')
            //->add('updatedBy')
            ->add('thematique', null,[
                'attr'=>['class' => 'form-control'],
                'class' => Thematique::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->liste();
                },
                'choice_label' => 'titre',
                'label' => 'Thématique'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
