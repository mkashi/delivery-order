<?php

namespace Delivery\OrderBundle\Form\Order;

use Delivery\OrderBundle\Domain\ContactDomain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ContactType
 */
class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Téléphone / Email pour vous recontacter',]
            ])
            ->add('subject', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Sujet']
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Message']
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactDomain::class,
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