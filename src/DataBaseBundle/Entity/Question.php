<?php

namespace DataBaseBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="DataBaseBundle\Repository\QuestionRepository")
 */
class Question
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
     * @ORM\ManyToOne(targetEntity="Quiz", inversedBy="questions")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    protected $quiz;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="hint", type="string", length=255, nullable=true)
     */
    private $hint;

    /**
     *
     * @ORM\OneToMany(targetEntity="MyOption", mappedBy="question")
     */
    protected $options;

    public function __construct()
    {
        $this->options = new ArrayCollection();
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
     * Set label
     *
     * @param string $label
     *
     * @return Question
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

    /**
     * Set quiz
     *
     * @param \DataBaseBundle\Entity\Quiz $quiz
     *
     * @return Question
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
     * Add option
     *
     * @param \DataBaseBundle\Entity\MyOption $option
     *
     * @return Question
     */
    public function addOption(\DataBaseBundle\Entity\MyOption $option)
    {
        $this->options[] = $option;
    
        return $this;
    }

    /**
     * Remove option
     *
     * @param \DataBaseBundle\Entity\MyOption $option
     */
    public function removeOption(\DataBaseBundle\Entity\MyOption $option)
    {
        $this->options->removeElement($option);
    }

    /**
     * Get options
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set desc
     *
     * @param string $description
     *
     * @return Question
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get desc
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set hint
     *
     * @param string $hint
     *
     * @return Question
     */
    public function setHint($hint)
    {
        $this->hint = $hint;
    
        return $this;
    }

    /**
     * Get hint
     *
     * @return string
     */
    public function getHint()
    {
        return $this->hint;
    }
}
