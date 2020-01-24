<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 15.12.2019
 * Time: 20:42
 */

namespace App\Repository\Admin;


use App\Entity\Admin\LearningMaterialsGroupExam;
use Kreait\Firebase\Exception\ApiException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class LearningMaterialsGroupExamRepository
{
    protected $db;
    protected $database;
    protected $dbname = 'LearningMaterialsGroupExam';
    private $entityManager = 'LearningMaterialsGroupExam';
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

    public function getLearningMaterialsGroupExam(int $id)
    {
        try {
            if ($this->reference->getSnapshot()->hasChild($id)) {
                return $this->reference->getChild($id)->getValue();
            } else {
                return 0;
            }
        } catch (ApiException $e) {

        }
    }
    public function insert(array $data)
    {
        if (empty($data)) {
            return false;
        }

        $materialsGroupExamId = $this->getNextId();

        $this->reference
            ->getChild($materialsGroupExamId)->set([
                'id' => $materialsGroupExamId,
                'learning_materials_group_id' => $data[0],
                'exam_id' => $data[1],
            ]);
        return true;
    }
    public function update(array $data,int $id)
    {
        if (empty($data)) {
            return false;
        }

        $this->reference
            ->getChild($id)->update([
                'learning_materials_group_id' => $data[0],
                'exam_id' => $data[1],
            ]);
        return true;
    }
    public function delete(int $materialsGroupExamId)
    {
        try {
            if ($this->reference->getSnapshot()->hasChild($materialsGroupExamId)) {
                $this->reference->getChild($materialsGroupExamId)->remove();
                return true;
            } else {
                return false;
            }
        } catch (ApiException $e) {
        }
    }

    public function getQuantity()
    {
        return $this->reference->getSnapshot()->numChildren();
    }


    public function getIdLearningMaterialsGroupExams()
    {
        if($this->reference->getSnapshot()->hasChildren()==NULL){
            return 0;
        } else {
            return $this->reference->getChildKeys();
        }
    }

    public function find(int $groupId){
        $information = $this->reference->getChild($groupId)->getValue();
        $info = new LearningMaterialsGroupExam([]);
        $info->setLearningMaterialsGroupId($information['learning_materials_group_id']);
        $info->setExamId($information['exam_id']);
        return $info;
    }

    public function findByGroupId(int $learningMaterialsGroupId) {
        $id = $this->getIdLearningMaterialsGroupExams();
        if($id!=0){
            $count = count($id);
        } else {
            $count=0;
        }
        for($i=0;$i<$count;$i++) {
            $information = $this->reference->getChild($id[$i])->getValue();
            if($information['learning_materials_group_id'] == $learningMaterialsGroupId) {
                return true;
            } else {
                return false;
            }

        }
    }

    public function findByExamId(int $examId) {
        $id = $this->getIdLearningMaterialsGroupExams();
        if($id!=0){
            $count = count($id);
        } else {
            $count=0;
        }
        for($i=0;$i<$count;$i++) {
            $information = $this->reference->getChild($id[$i])->getValue();
            if($information['exam_id'] == $examId) {
                return true;
            } else {
                return false;
            }

        }
    }

   /* public function findByExamId(int $examId) {
        $id = $this->getIdLearningMaterialsGroupExams();
        $amount =0;
        for($i=0;$i<count($id);$i++) {
            $information = $this->reference->getChild($id[$i])->getValue();
            if($information['exam_id'] == $examId) {
                $learningMaterialsGroups[$amount] = $information['learning_materials_group_id'];
                $amount++;
            }
        }
        return $learningMaterialsGroups;
    }*/

    public function getNextId() {
        $learningMaterialsGroupExamsId = $this->getIdLearningMaterialsGroupExams();
        if($learningMaterialsGroupExamsId!=0){
            $learningMaterialsGroupExamsAmount = count($learningMaterialsGroupExamsId);
        } else {
            $learningMaterialsGroupExamsAmount=0;
        }
        switch ($learningMaterialsGroupExamsAmount) {
            case 0:{
                $maxNumber = 0;
                break;
            }
            case 1:{
                $maxNumber=$learningMaterialsGroupExamsId[0]+1;
                break;
            }
            default:{
                $maxNumber=$learningMaterialsGroupExamsId[0];
                for($i=1;$i<$learningMaterialsGroupExamsAmount;$i++){
                    if($maxNumber<=$learningMaterialsGroupExamsId[$i]){
                        $maxNumber =$learningMaterialsGroupExamsId[$i];
                    }
                }
                $maxNumber=$maxNumber+1;
            }
        }
        return $maxNumber;
    }
}