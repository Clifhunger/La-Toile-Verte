<?php

namespace DataBaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MyOption
 *
 * @ORM\Table(name="my_option")
 * @ORM\Entity(repositoryClass="DataBaseBundle\Repository\MyOptionRepository")
 */
class MyOption
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
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="options")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    protected $question;

     /**
     * @var boolean
     *
     * @ORM\Column(name="answer", type="boolean")
     */
    private $answer;


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
     * Set value
     *
     * @param string $value
     *
     * @return MyOption
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set answer
     *
     * @param boolean $answer
     *
     * @return MyOption
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    
        return $this;
    }

    /**
     * Get answer
     *
     * @return boolean
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set question
     *
     * @param \DataBaseBundle\Entity\Question $question
     *
     * @return MyOption
     */
    public function setQuestion(\DataBaseBundle\Entity\Question $question = null)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return \DataBaseBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
