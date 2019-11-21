<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 20.11.2019
 * Time: 10:57
 */

namespace App\Form;


use App\Entity\Exam;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('learning_required', ChoiceType::class)
            ->add('min_questions', IntegerType::class)
            ->add('max_attempts', IntegerType::class)
            ->add('start_date', DateType::class)
            ->add('end_date', DateType::class)
            ->add('additional_information', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Add Exam'])
            ->setMethod('POST')
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Exam::class,
        ]);
    }

    /**
     * @return string
     */
    public function getName() {
        return 'exam_add';
    }
}