<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InsectEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // utilisateur des TextType et SubmitType
        $builder->add('age',NumberType::class)
            ->add('famille',TextType::class)
            ->add('race',TextType::class)
            ->add('nourriture',TextType::class)
            ->add('submit',SubmitType::class,
            ["label"=>"Modifier",
                "attr" => ["class" => "btn btn-primary"]
            ]);
    }
/*
 * en commentaire car j'ai fais le choix d'utiliser ce formulaire pour la modification.
 * donc sans les champs user, mail et mdp
   public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }
*/
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Insect'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_insect';
    }


}
