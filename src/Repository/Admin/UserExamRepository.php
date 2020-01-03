<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 03.12.2019
 * Time: 13:14
 */

namespace App\Repository\Admin;


use Kreait\Firebase\Exception\ApiException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class UserExamRepository
{
    private $dbname= "UserExam";
    public function __construct()
    {
        $serviceAccount = ServiceAccount::fromJsonFile('C:\xampp\htdocs\examServiceProject\secret\examservicedatabase-88ff116bf2b0.json');

        $factory = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://examservicedatabase.firebaseio.com/');

        $this->database = $factory->createDatabase();
        $this->reference = $this->database->getReference($this->dbname);
    }

    public function getUserExam(int $userExamId)
    {
        try {
            if ($this->reference->getSnapshot()->hasChild($userExamId)) {
                return $this->reference->getChild($userExamId)->getValue();
            } else {
                return 0;
            }
        } catch (ApiException $e) {

        }
    }

    public function insert(array $data)
    {
        if (empty($data) /*|| isset($data)*/) {
            return false;
        }
        $actualUserExamId = $this->getQuantity();
        $this->reference
            ->getChild($actualUserExamId)->set([
                'user_exam_id' => $actualUserExamId,
                'user_id' => $data[0],
                'exam_id' => $data[1],
                //'$date_of_resolve_exam' => NULL,
                'start_access_time' => $data[3],
                'end_access_time' => $data[4],
            ]);
        return true;
    }

    public function delete(int $userExamId)
    {
        try {
            if ($this->reference->getSnapshot()->hasChild($userExamId)) {
                $this->reference->getChild($userExamId)->remove();
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
    public function isUserExamForExamId(int $examId)
    {
        for ($i = 0; $i < $this->getQuantity(); $i++) {
            $userExam = $this->getUserExam($i);
            if ($userExam['exam_id'] == $examId) {
                return true;
            } else {
                return false;
            }
        }
    }
}