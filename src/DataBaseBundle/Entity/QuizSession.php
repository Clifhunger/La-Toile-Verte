<?php

namespace DataBaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuizSession
 *
 * @ORM\Table(name="quiz_session")
 * @ORM\Entity(repositoryClass="DataBaseBundle\Repository\QuizSessionRepository")
 */
class QuizSession
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetimetz")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetimetz")
     */
    private $endDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="beginDate", type="datetimetz")
     */
    private $beginDate;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=8, unique=true)
     */
    private $code;

    /**
     * @ORM\OneToOne(targetEntity="Quiz")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    private $quiz;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", unique=false)
     */
    private $creator;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return QuizSession
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    
        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return QuizSession
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    
        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set beginDate
     *
     * @param \DateTime $beginDate
     *
     * @return QuizSession
     */
    public function setBeginDate($beginDate)
    {
        $this->beginDate = $beginDate;
    
        return $this;
    }

    /**
     * Get beginDate
     *
     * @return \DateTime
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return QuizSession
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set quiz
     *
     * @param \DataBaseBundle\Entity\Quiz $quiz
     *
     * @return QuizSession
     */
    public function setQuiz(\DataBaseBundle\Entity\Quiz $quiz = null)
    {
        $this->quiz = $quiz;
    
        return $this;
    }

    /**
     * Get quiz
     *
     * @return \DataBaseBundle\Entity\Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Set creator
     *
     * @param \DataBaseBundle\Entity\User $creator
     *
     * @return QuizSession
     */
    public function setCreator(\DataBaseBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;
    
        return $this;
    }

    /**
     * Get creator
     *
     * @return \DataBaseBundle\Entity\User
     */
    public function getCreator()
    {
        return $this->creator;
    }
}
