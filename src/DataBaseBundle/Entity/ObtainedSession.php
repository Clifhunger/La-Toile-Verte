<?php

namespace DataBaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ObtainedSession
 *
 * @ORM\Table(name="obtained_session")
 * @ORM\Entity(repositoryClass="DataBaseBundle\Repository\ObtainedSessionRepository")
 */
class ObtainedSession
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="QuizSession")
     * @ORM\JoinColumn(name="quiz_session_code", referencedColumnName="id")
     */
    private $quizSession;

    /**
     * @ORM\Column(type="string")
     */
    private $percent;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateObtained;

    /** @ORM\Column(type="string") */
    private $timezone;


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
     * Set percent
     *
     * @param string $percent
     *
     * @return ObtainedSession
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
    
        return $this;
    }

    /**
     * Get percent
     *
     * @return string
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Set dateObtained
     *
     * @param \DateTime $dateObtained
     *
     * @return ObtainedSession
     */
    public function setDateObtained($dateObtained)
    {
        $this->timezone = $dateObtained->getTimeZone()->getName();
        $dateObtained->setTimeZone(new \DateTimeZone('UTC'));
        $this->dateObtained = $dateObtained;
    
        return $this;
    }

    /**
     * Get dateObtained
     *
     * @return \DateTime
     */
    public function getDateObtained()
    {
        if ($this->dateObtained)
            $this->dateObtained->setTimeZone(new \DateTimeZone($this->timezone));
        return $this->dateObtained;
    }

    /**
     * Set user
     *
     * @param \DataBaseBundle\Entity\User $user
     *
     * @return ObtainedSession
     */
    public function setUser(\DataBaseBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \DataBaseBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set quizSession
     *
     * @param \DataBaseBundle\Entity\QuizSession $quizSession
     *
     * @return ObtainedSession
     */
    public function setQuizSession(\DataBaseBundle\Entity\QuizSession $quizSession = null)
    {
        $this->quizSession = $quizSession;
    
        return $this;
    }

    /**
     * Get quizSession
     *
     * @return \DataBaseBundle\Entity\QuizSession
     */
    public function getQuizSession()
    {
        return $this->quizSession;
    }
}
