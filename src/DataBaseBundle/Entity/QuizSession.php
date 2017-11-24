<?php

namespace DataBaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var \Date
     *
     * @ORM\Column(name="creationDate", type="date")
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
     * @ORM\ManyToOne(targetEntity="Quiz")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    private $quiz;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $creator;

    /** @ORM\Column(type="string") */
    private $timezone;

    /**
     *
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="quiz_session_users_done",
     *      joinColumns={@ORM\JoinColumn(name="quiz_session_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", unique=true)}
     *      )
     */
    protected $doneUsers;

    public function __construct()
    {
        $this->doneUsers = new ArrayCollection();
    }

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
        $this->creationDate->setTimeZone(new \DateTimeZone($this->timezone));
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
        $this->timezone = $endDate->getTimeZone()->getName();
        $endDate->setTimeZone(new \DateTimeZone('UTC'));
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
        if ($this->endDate)
            $this->endDate->setTimeZone(new \DateTimeZone($this->timezone));
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
        $this->timezone = $beginDate->getTimeZone()->getName();
        $beginDate->setTimeZone(new \DateTimeZone('UTC'));
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
        if ($this->beginDate)
            $this->beginDate->setTimeZone(new \DateTimeZone($this->timezone));
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

    /**
     * Set timezone
     *
     * @param string $timezone
     *
     * @return QuizSession
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    
        return $this;
    }

    /**
     * Get timezone
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Add doneUser
     *
     * @param \DataBaseBundle\Entity\User $doneUser
     *
     * @return QuizSession
     */
    public function addDoneUser(\DataBaseBundle\Entity\User $doneUser)
    {
        $this->doneUsers[] = $doneUser;
    
        return $this;
    }

    /**
     * Remove doneUser
     *
     * @param \DataBaseBundle\Entity\User $doneUser
     */
    public function removeDoneUser(\DataBaseBundle\Entity\User $doneUser)
    {
        $this->doneUsers->removeElement($doneUser);
    }

    /**
     * Get doneUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDoneUsers()
    {
        return $this->doneUsers;
    }
}
