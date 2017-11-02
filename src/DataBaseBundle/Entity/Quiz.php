<?php

namespace DataBaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 *
 * @ORM\Table(name="quiz")
 * @ORM\Entity(repositoryClass="DataBaseBundle\Repository\QuizRepository")
 */
class Quiz
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
     * @ORM\Column(name="label", type="string")
     */
    private $label;

    /**
     *
     * @ORM\OneToMany(targetEntity="Question", mappedBy="quiz")
     */
    protected $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
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
     * Add question
     *
     * @param \DataBaseBundle\Entity\Question $question
     *
     * @return Quiz
     */
    public function addQuestion(\DataBaseBundle\Entity\Question $question)
    {
        $this->questions[] = $question;
    
        return $this;
    }

    /**
     * Remove question
     *
     * @param \DataBaseBundle\Entity\Question $question
     */
    public function removeQuestion(\DataBaseBundle\Entity\Question $question)
    {
        $this->questions->removeElement($question);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Quiz
     */
    public function setLabel($label)
    {
        $this->label = $label;
    
        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}
