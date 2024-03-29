<?php

namespace App\Entity\Admin;

use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Admin\AnswerRepository")
 */

class Answer extends Entity {
    /**
     * @Assert\Type("Integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @Assert\Type("Integer")
     * @ORM\Column(type="integer")
     */
    private $exam_id;
    /**
     * @Assert\Type("Integer")
     * @ORM\Column(type="integer")
     */
    private $question_id;

    /**
     * @Assert\NotBlank
     * @Assert\Type("String")
     * @ORM\Column(type="string")
     */
    private $content;
    /**
     * @Assert\Type("Boolean")
     * @ORM\Column(type="boolean")
     */
    private $is_true;

    /**
     * @return mixed
     */
    public function getExamId() {
        return $this->exam_id;
    }

    /**
     * @param mixed $exam_id
     */
    public function setExamId($exam_id): void {
        $this->exam_id = $exam_id;
    }

    /**
     * @return mixed
     */
    public function getQuestionId() {
        return $this->question_id;
    }

    /**
     * @param mixed $question_id
     */
    public function setQuestionId($question_id): void {
        $this->question_id = $question_id;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getisTrue() {
        return $this->is_true;
    }

    /**
     * @param mixed $is_true
     */
    public function setIsTrue($is_true): void {
        $this->is_true = $is_true;
    }

    /**
     * @return mixed
     */

    public function getAllInformation() {
        $data = [$this->content,$this->is_true];
        return $data;
    }
}