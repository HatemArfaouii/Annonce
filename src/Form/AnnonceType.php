<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Subcategory;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AnnonceType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $this->entityManager->getRepository(Subcategory::class)->findAll();
        $builder
            ->add('libelle', TextType::class, array('label' => false, 'required' => true))
            ->add('phone', TextType::class, array('label' => false, 'required' => false))
            //            ->add('ville', TextType::class, array('label' => false, 'required' => true))

            ->add('type', ChoiceType::class, [
                'label' => false,
                "required" => false,
                'placeholder' => 'Choisir une type d\'annonce ',
                'choices'  => [
                    'Vendre' => 'Vendre',
                    'Location' => 'Location',
                ],
            ])
            ->add('ville', ChoiceType::class, [
                'label' => false,
                "required" => true,
                'placeholder' => 'Choisir une ville ',
                'choices'  => [
                    'Tunis' => 'Tunis',
                    'Ariana' => 'Ariana',
                    'Manouba' => 'Manouba',
                    'Benarous' => 'Ben arous',
                    'Kef' => 'Kef',
                    'Siliana' => 'Siliana',
                    'Jandouba' => 'Jandouba',
                    'Beja' => 'Beja',
                    'Nabeul' => 'Nabeul',
                    'Monastir' => 'Monastir',
                    'Sousse' => 'Sousse',
                    'Mahdia' => 'Mahdia',
                    'Sfax' => 'Sfax',
                    'Kairouan' => 'Kairouan',
                    'Gabes' => 'Gabes',
                    'Mednin' => 'Mednin',
                    'Tataouine' => 'Tataouine',
                    'Kbeli' => 'Gbeli',
                    'Touzer' => 'Touzer',
                    'Gafsa' => 'Gafsa',
                    'Kasserine' => 'Gassrine',
                    'Sidi bouzid' => 'Sidi bouzid',
                    'Bizerte' => 'Bizerte',
                    'Zaghouan' => 'Zaghouan',
                ],
            ])

            ->add('adresse', TextType::class, array('label' => false, 'required' => false))
            ->add('description', TextareaType::class, array('label' => false, 'required' => true))
            ->add('imageFile', FileType::class, ['label' => false, "required" => false])
            ->add('imageFile2', FileType::class, ['label' => false, "required" => false])
            ->add('imageFile3', FileType::class, ['label' => false, "required" => false])
            ->add('imageFile4', FileType::class, ['label' => false, "required" => false])
            ->add('prix', TextType::class, array('label' => false, 'required' => true))
            ->add('verifannonce', HiddenType::class, array('label' => false))
            ->add(
                'subcategory',
                ChoiceType::class,
                [
                    'label' => false,
                    "required" => false,
                    'placeholder' => 'Choisir une catégorie ',
                    'choices' => $data,
                    'choice_label' => function ($subcategory, $key, $value) {
                        /** @var Subcategory $subcategory */
                        return strtoupper($subcategory->getTitre());
                    },
                    'choice_attr' => function ($subcategory, $key, $value) {
                        return ['class' => 'subcategory' . strtolower($subcategory->getTitre())];
                    },
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
            'csrf_protection' => false,
        ]);
    }
}
