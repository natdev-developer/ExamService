<?php

namespace App\Form\Admin;

use App\Entity\Admin\Answer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('content', TextareaType::class)
            ->add('is_true', ChoiceType::class,
                [
                    'choices'  => [
                        'Yes' => true,
                        'No' => false,
                    ]])
            ->add('save', SubmitType::class, ['label' => 'Add Answer'])
            ->setMethod('POST')
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Answer::class,
        ]);
    }

    /**
     * @return string
     */
    public function getName() {
        return 'answer_add';
    }
}