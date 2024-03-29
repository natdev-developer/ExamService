<?php

namespace App\Repository\Admin;

use App\Entity\Admin\Exam;

class ExamRepository {

    protected $reference;

    public function __construct() {
        $database = new DatabaseConnection();
        $this->reference = $database->getReference('Exam');
    }

    public function getExam(int $examId) {
        if ($this->reference->getSnapshot()->hasChild($examId)) {
            return $this->reference->getChild($examId)->getValue();
        } else
            return 0;
    }

    public function insert(array $data) {
        if (empty($data))
            return false;

        if($_SESSION['role'] == "ROLE_ADMIN") {
            $userId = -1;
        } else {
            $userId = $_SESSION['user_id'];
        }

       $maxNumber = $this->nextExamId();

        $this->reference
        ->getChild($maxNumber)->set([
            'exam_id' => $maxNumber,
            'name' => $data[0],
            'learning_required' => $data[1],
            'additional_information' => $data[2],
            'max_questions' => $data[3],
            'max_attempts' => $data[4],
            'start_date' => $data[5],
            'end_date' => $data[6],
            'created_by' => $userId,
            'duration_of_exam' => $data[7],
            'percentage_passed_exam' => $data[9],
        ]);
        return true;
    }

    public function update(array $data, int $id) {
        if (empty($data))
            return false;

        $this->reference
            ->getChild($id)->update([
                'name' => $data[0],
                'learning_required' => $data[1],
                'additional_information' => $data[2],
                'max_questions' => $data[3],
                'max_attempts' => $data[4],
                'start_date' => $data[5],
                'end_date' => $data[6],
                'duration_of_exam' => $data[7],
                'percentage_passed_exam' => $data[8],
            ]);
        return true;
    }

    public function delete(int $examId) {
        if ($this->reference->getSnapshot()->hasChild($examId)) {
            $this->reference->getChild($examId)->remove();
            return true;
        } else
            return false;
    }

    public function getQuantity() { return $this->reference->getSnapshot()->numChildren(); }

    public function getIdExams() {
        if($this->reference->getSnapshot()->hasChildren()==NULL){
            return 0;
        } else
            return  $this->reference->getChildKeys();
    }

    public function find(int $examId) {
        $information = $this->reference->getChild($examId)->getValue();
        $exam = new Exam([]);
        $exam->setName($information['name']);
        $exam->setId($information['exam_id']);
        $exam->setAdditionalInformation($information['additional_information']);
        $exam->setCreatedBy($information['created_by']);
        $exam->setDurationOfExam($information['duration_of_exam']);
        $exam->setEndDate(new \DateTime($information['end_date']['date']));
        $exam->setStartDate(new \DateTime($information['start_date']['date']));
        $exam->setMaxQuestions($information['max_questions']);
        $exam->setMaxAttempts($information['max_attempts']);
        $exam->setLearningRequired($information['learning_required']);
        $exam->setPercentagePassedExam($information['percentage_passed_exam']);
        return $exam;
    }

    public function nextExamId() {
        $examsId= $this->getIdExams();
        if($examsId!=0){
            $examsAmount = count($examsId);
        } else {
            $examsAmount=0;
        }
        switch ($examsAmount) {
            case 0:{
                $maxNumber = 0;
                break;
            }
            case 1:{
                $maxNumber=$examsAmount[0]+1;
                break;
            }
            default:{
                $maxNumber=$examsId[0];
                for($i=1;$i<$examsAmount;$i++){
                    if($maxNumber<=$examsId[$i]){
                        $maxNumber =$examsId[$i];
                    }
                }
                $maxNumber=$maxNumber+1;
            }
        }
        return $maxNumber;
    }
}