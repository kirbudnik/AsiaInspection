<?php

namespace AI\ResponsiveBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class loginType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('username','text',array('required'=>true))
            ->add('password','password',array('required'=>true))
        ;
    }
    
 

    /**
     * @return string
     */
    public function getName()
    {
        return 'ai_responsivebundle_login';
    }

 }