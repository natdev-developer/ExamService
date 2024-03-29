<?php

namespace App\Form\Admin;

use App\Entity\Admin\LearningMaterialsGroupExam;
use App\Repository\Admin\ExamRepository;
use App\Repository\Admin\LearningMaterialsGroupRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LearningMaterialsGroupExamType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $exam = new ExamRepository();
        $group = new LearningMaterialsGroupRepository();

        $examsId = $exam->getIdExams();
        if($examsId==0){
            $examsCount=0;
        } else
            $examsCount = count($examsId);

        $learningMaterialsGroupsId = $group->getLearningMaterialsGroupId();
        if($learningMaterialsGroupsId==0){
            $learningMaterialsGroupsCount = 0;
        } else
            $learningMaterialsGroupsCount = count($learningMaterialsGroupsId);

        for ($i = 0; $i < $examsCount; $i++) {
            $values = $exam->getExam($examsId[$i]);
            $examArray[$values['exam_id'].' - '.$values["name"]] = $values['exam_id'];
        }
        for ($i = 0; $i < $learningMaterialsGroupsCount; $i++) {
            $valuesGroup = $group->getLearningMaterialsGroup($learningMaterialsGroupsId[$i]);
            $groupArray[$valuesGroup['learning_materials_group_id'].' - '.$valuesGroup['name_of_group']] = $valuesGroup['learning_materials_group_id'];
        }

        $builder
            ->add('learning_materials_group_id', ChoiceType::class, [
                'choices' => $groupArray
            ])
               ->add('exam_id', ChoiceType::class, [
                   'choices' => $examArray
               ])
            ->add('save', SubmitType::class, ['label' => 'Add Learning Materials Group Exam'])
            ->setMethod('POST')
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => LearningMaterialsGroupExam::class,
        ]);
    }

    /**
     * @return string
     */
    public function getName() {
        return 'learning_materials_group_exam_add';
    }
}