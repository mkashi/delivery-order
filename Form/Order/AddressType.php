<?php

namespace Delivery\OrderBundle\Form\Order;

use Delivery\ApiBundle\Entity\Localisation\City;
use Delivery\ApiBundle\Entity\Localisation\Department;
use Delivery\ApiBundle\Entity\Order\OrderAddress;
use Delivery\ApiBundle\Repository\CityRepository;
use Delivery\ApiBundle\Repository\DepartmentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddressType
 */
class AddressType extends AbstractType
{
    /**
     * @var CityRepository
     */
    private $cityRepository;

    /**
     * @var DepartmentRepository
     */
    private $departmentRepository;

    /**
     * @param CityRepository $cityRepository
     * @param DepartmentRepository $departmentRepository
     */
    public function __construct(CityRepository $cityRepository, DepartmentRepository $departmentRepository)
    {
        $this->cityRepository = $cityRepository;
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $departments = $this->departmentRepository->getDepartments();

        $builder
            ->add('city', EntityType::class, [
                'class' => City::class,
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ville',
                ],
                'choices' => $departments[0]->getCities(),
            ])
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'choices' => $departments,
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Departement',
                ]
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom / nom',
                ],
            ])
            ->add('address', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Addresse précise: numéro, voie...',
                ],
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Numéro de téléphone (ex: 06.12.34.56.78.90)',
                ],
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $e) {
            $data = $e->getData();
            $form = $e->getForm();
            $departmentId = $data['department'];

            $form->remove('city');

            // dump($this->departmentRepository->find($departmentId)->getCities()->toArray());exit;
            $form->add('city', EntityType::class, [
                'class' => City::class,
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ville',
                ],
                'choices' => $this->departmentRepository->find($departmentId)->getCities(),
            ]);
        });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderAddress::class,
        ]);
    }

    /**
     * @return null
     */
    public function getBlockPrefix()
    {
        return;
    }
}