<?php
namespace App\Form;

use App\Entity\Admin\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 18.11.2019
 * Time: 20:14
 */

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("email",EmailType::class)
            ->add("password",PasswordType::class)
            ->add("submit",SubmitType::class, array('label' => 'Sign in'))
            ->setMethod('POST')
            ->getForm();
    }
    public function getName()
    {
        return 'login';
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
//php -S 127.0.0.1:8000 -t public
//yarn encore dev --watch
//yarn encore dev
// php bin/console cache:pool:clear cache.global_clearer
//php bin/console cache:pool:list