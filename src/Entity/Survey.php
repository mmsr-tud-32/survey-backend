<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
/**
 * @Serializer\ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="App\Repository\SurveyRepository")
 */
class Survey
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Serializer\Expose
     * @ORM\Column(type="guid")
     */
    private $uuid;

    /**
     * @Serializer\Expose
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @Serializer\Expose
     * @ORM\OneToMany(targetEntity="App\Entity\SurveyImage", mappedBy="survey")
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SurveySubmission", mappedBy="survey")
     */
    private $submissions;

    /**
     * @Serializer\Expose
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $num_practise;

    /**
     * @ORM\Column(type="integer")
     */
    private $num_question_short;

    /**
     * @ORM\Column(type="integer")
     */
    private $num_question_long;

    /**
     * @Serializer\Expose
     * @ORM\Column(type="integer")
     */
    private $timeout_short;

    /**
     * @Serializer\Expose
     * @ORM\Column(type="integer")
     */
    private $timeout_long;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->submissions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|SurveyImage[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(SurveyImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setSurvey($this);
        }

        return $this;
    }

    public function removeImage(SurveyImage $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getSurvey() === $this) {
                $image->setSurvey(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SurveySubmission[]
     */
    public function getSubmissions(): Collection
    {
        return $this->submissions;
    }

    public function addSubmission(SurveySubmission $submission): self
    {
        if (!$this->submissions->contains($submission)) {
            $this->submissions[] = $submission;
            $submission->setSurvey($this);
        }

        return $this;
    }

    public function removeSubmission(SurveySubmission $submission): self
    {
        if ($this->submissions->contains($submission)) {
            $this->submissions->removeElement($submission);
            // set the owning side to null (unless already changed)
            if ($submission->getSurvey() === $this) {
                $submission->setSurvey(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNumPractise(): ?int
    {
        return $this->num_practise;
    }

    public function setNumPractise(int $num_practise): self
    {
        $this->num_practise = $num_practise;

        return $this;
    }

    public function getNumQuestionShort(): ?int
    {
        return $this->num_question_short;
    }

    public function setNumQuestionShort(int $num_question_short): self
    {
        $this->num_question_short = $num_question_short;

        return $this;
    }

    public function getNumQuestionLong(): ?int
    {
        return $this->num_question_long;
    }

    public function setNumQuestionLong(int $num_question_long): self
    {
        $this->num_question_long = $num_question_long;

        return $this;
    }

    public function getTimeoutShort(): ?int
    {
        return $this->timeout_short;
    }

    public function setTimeoutShort(int $timeout_short): self
    {
        $this->timeout_short = $timeout_short;

        return $this;
    }

    public function getTimeoutLong(): ?int
    {
        return $this->timeout_long;
    }

    public function setTimeoutLong(int $timeout_long): self
    {
        $this->timeout_long = $timeout_long;

        return $this;
    }
}
