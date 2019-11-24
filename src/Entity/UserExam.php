<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 23.11.2019
 * Time: 12:16
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserExamRepository")
 */

class UserExam extends Entity  implements  EquatableInterface
{

    /**
     * @Assert\NotBlank
     * @Assert\Type("Datetime")
     * @ORM\Column(type="datetime")
     */
    private $date_of_resolve_exam;
    /**
     * @Assert\Type("Datetime")
     * @ORM\Column(type="datetime")
     */
    private $start_access_time;
    /**
     * @Assert\Type("Datetime")
     * @ORM\Column(type="datetime")
     */
    private $end_access_time;

    /**
     * @return mixed
     */
    public function getDateOfResolveExam()
    {
        return $this->date_of_resolve_exam;
    }

    /**
     * @param mixed $date_of_resolve_exam
     */
    public function setDateOfResolveExam($date_of_resolve_exam): void
    {
        $this->date_of_resolve_exam = $date_of_resolve_exam;
    }

    /**
     * @return mixed
     */
    public function getStartAccessTime()
    {
        return $this->start_access_time;
    }

    /**
     * @param mixed $start_access_time
     */
    public function setStartAccessTime($start_access_time): void
    {
        $this->start_access_time = $start_access_time;
    }

    /**
     * @return mixed
     */
    public function getEndAccessTime()
    {
        return $this->end_access_time;
    }

    /**
     * @param mixed $end_access_time
     */
    public function setEndAccessTime($end_access_time): void
    {
        $this->end_access_time = $end_access_time;
    }

    /**
     * @return mixed
     */
    public function getNumberApproach()
    {
        return $this->number_approach;
    }

    /**
     * @param mixed $number_approach
     */
    public function setNumberApproach($number_approach): void
    {
        $this->number_approach = $number_approach;
    }
    /**
     * @Assert\Type("Integer")
     * @ORM\Column(type="integer")
     */
    private $number_approach;
    /**
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        // TODO: Implement isEqualTo() method.
    }
}