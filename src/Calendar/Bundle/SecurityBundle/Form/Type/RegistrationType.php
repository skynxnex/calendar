<?php

    namespace Calendar\SecurityBundle\Form\Type;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;

    class RegistrationType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('user', new UserType());
            $builder->add(
                'terms',
                'checkbox',
                array('property_path' => 'termsAccepted')
            );
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(
                array(
                     'data_class' => 'Acme\AccountBundle\Entity\User'
                )
            );
        }

        public function getName()
        {
            return 'registration';
        }
    }