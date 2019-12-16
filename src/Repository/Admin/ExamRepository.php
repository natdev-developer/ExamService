<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 23.11.2019
 * Time: 12:35
 */

namespace App\Repository\Admin;


use App\Entity\Admin\Exam;
use Kreait\Firebase\Exception\ApiException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class ExamRepository
{

    protected $db;
    protected $database;
    protected $dbname = 'Exam';
    private $entityManager = 'Exam';
    protected $reference;

    public function __construct()
    {
        $serviceAccount = ServiceAccount::fromJsonFile('C:\xampp\htdocs\examServiceProject\secret\examservicedatabase-88ff116bf2b0.json');

        $factory = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://examservicedatabase.firebaseio.com/');

        $this->database = $factory->createDatabase();
        $this->reference = $this->database->getReference($this->dbname);
    }

    public function getExam(int $examId)
    {
        try {
            if ($this->reference->getSnapshot()->hasChild($examId)) {
                return $this->reference->getChild($examId)->getValue();
            } else {
                return 0;
            }
        } catch (ApiException $e) {

        }
    }


    public function getAllExams()
    {
        $examId = $this->getQuantity();
        if (empty($examId) /*|| isset($userId)*/) {
            return 0;
        }
        for ($i = 0; $i < $examId; $i++) {
            try {
                if ($this->reference->getSnapshot()->hasChild($i)) {
                    $data[$i] = $this->reference->getChild($i)->getValue();
                    return $data;
                } else {
                    return 0;
                }
            } catch (ApiException $e) {}
        }
    }

    public function insert(array $data)
    {
        if (empty($data) /*|| isset($data)*/) {
            return false;
        }

        $actualExamId = $this->getQuantity();

        $this->reference
        ->getChild($actualExamId)->set([
            //$actualUserId => [
            'exam_id' => $actualExamId,
            'name' => $data[0],
            'learning_required' => $data[1],
            'additional_information' => $data[2],
            'max_questions' => $data[3],
            'max_attempts' => $data[4],
            'start_date' => $data[5],
            'end_date' => $data[6],
            'created_by' => 0, //todo: user_id ktory jest zalogowany
            'duration_of_exam' => $data[7]
        ]);
        return true;
    }
    public function update(array $data, int $id) {
        if (empty($data) /*|| isset($data)*/) {
            return false;
        }

        $this->reference
            ->getChild($id)->update([
                'name' => $data[0],
                'learning_required' => $data[1],
                'additional_information' => $data[2],
                'max_questions' => $data[3],
                'max_attempts' => $data[4],
                'start_date' => $data[5],
                'end_date' => $data[6],
                'duration_of_exam' => $data[7]
            ]);
        return true;
    }

    public function delete(int $examId)
    {
        try {
            if ($this->reference->getSnapshot()->hasChild($examId)) {
                $this->reference->getChild($examId)->remove();
                return true;
            } else {
                return false;
            }
        } catch (ApiException $e) {
        }
    }

    public function getQuantity()
    {
        try {
            return $this->reference->getSnapshot()->numChildren();
        } catch (ApiException $e) {
        }
    }
    public function find(int $examId){
        $information = $this->reference->getChild($examId)->getValue();
        $exam = new Exam([]);
        $exam->setName($information['name']);
        $exam->setId($information['exam_id']);
        $exam->setAdditionalInformation($information['additional_information']);
        $exam->setCreatedBy($information['created_by']);
    //    $exam->setDurationOfExam($information['duration_of_exam']);
      //  $exam->setEndDate($information['end_date']);
    //    $exam->setStartDate($information['start_date']);
        $exam->setMaxQuestions($information['max_questions']);
        $exam->setMaxAttempts($information['max_attempts']);
        $exam->setLearningRequired($information['learning_required']);
        return $exam;
    }

}