<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="App\Repository\SurveySubmissionImageRepository")
 */
class SurveySubmissionImage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Serializer\Expose
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $fake;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SurveySubmission", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $submission;

    /**
     * @Serializer\Expose
     * @ORM\ManyToOne(targetEntity="App\Entity\SurveyImage")
     * @ORM\JoinColumn(nullable=false)
     */
    private $image;

    /**
     * @Serializer\Expose
     * @ORM\Column(type="string", length=255)
     */
    private $stage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFake(): ?bool
    {
        return $this->fake;
    }

    public function setFake(bool $fake): self
    {
        $this->fake = $fake;

        return $this;
    }

    public function getSubmission(): ?SurveySubmission
    {
        return $this->submission;
    }

    public function setSubmission(?SurveySubmission $submission): self
    {
        $this->submission = $submission;

        return $this;
    }

    public function getImage(): ?SurveyImage
    {
        return $this->image;
    }

    public function setImage(?SurveyImage $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getStage(): ?string
    {
        return $this->stage;
    }

    public function setStage(string $stage): self
    {
        $this->stage = $stage;

        return $this;
    }
}
