<?php

namespace App\Controller;

use App\Entity\Survey;
use App\Entity\SurveyImage;
use App\Entity\SurveySubmission;
use App\Entity\SurveySubmissionImage;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SurveySubmissionController extends BaseController {
    /**
     * @Route("/submission", name="submission_create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function createSubmission(Request $request) {
        $surveyUuid = $request->request->get('survey_uuid');
        $name = $request->request->get('name');
        $age = $request->request->get('age');

        try {
            $survey = $this->getDoctrine()
                ->getRepository(Survey::class)
                ->findByUuid($surveyUuid);
            $manager = $this->getDoctrine()->getManager();

            if ($survey->getImages()->count() < ($survey->getNumPractise() + $survey->getNumQuestionShort() + $survey->getNumQuestionLong())) {
                return $this->json([
                    'message' => 'Not enough items in survey',
                ], 400);
            }

            $submission = new SurveySubmission();

            $submission->setUuid(Uuid::uuid4()->toString());
            $submission->setSubmitted(false);
            $submission->setSurvey($survey);
            $submission->setName($name);
            $submission->setAge($age);

            $images = $survey->getImages()->getValues();

            shuffle($images);

            foreach ((function () use ($survey) {
                yield from array_fill(0, $survey->getNumPractise(), 'practise');
                yield from array_fill(0, $survey->getNumQuestionShort(), 'short');
                yield from array_fill(0, $survey->getNumQuestionLong(), 'long');
            })() as $stage) {
                $submissionImage = new SurveySubmissionImage();
                $submissionImage->setImage(next($images));
                $submissionImage->setSubmission($submission);
                $submissionImage->setStage($stage);
                $submission->addImage($submissionImage);
                $manager->persist($submissionImage);
            }

            $manager->persist($submission);
            $manager->flush();

            return $this->json($submission);
        } catch (NoResultException $e) {
            return $this->json([
                'message' => vsprintf('Survey with uuid "%s" not found.', [$surveyUuid]),
            ], 400);
        }
    }

    /**
     * @Route("/submission/{uuid}", name="get_submission", methods={"GET"})
     *
     * @param $uuid
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function getSubmission($uuid) {
        $surveySubmission = $this->getDoctrine()
            ->getRepository(SurveySubmission::class)
            ->findByUuid($uuid);

        return $this->json($surveySubmission);
    }

    /**
     * @Route("/submission/{uuid}/answer", name="answer_survey", methods={"POST"})
     *
     * @param $uuid
     * @param Request $request
     * @return JsonResponse
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function answer($uuid, Request $request) {
        $stage = $request->request->get('stage');
        $imageUuid = $request->request->get('image_uuid');
        $fake = $request->request->getBoolean('fake');

        $surveySubmission = $this->getDoctrine()
            ->getRepository(SurveySubmission::class)
            ->findByUuid($uuid);

        $surveyImage = $this->getDoctrine()
            ->getRepository(SurveyImage::class)
            ->findByUuid($imageUuid);

        $surveySubmissionImage = $this->getDoctrine()
            ->getRepository(SurveySubmissionImage::class)
            ->findForSubmission($surveyImage, $surveySubmission, $stage);

        if ($surveySubmissionImage == null) {
            return $this->json('', 404);
        }

        $surveySubmissionImage->setFake($fake);

        $this->getDoctrine()->getManager()->flush();

        return $this->json($surveySubmissionImage);
    }

    /**
     * @Route("/submission/{uuid}/submit", name="submit_survey", methods={"POST"})
     *
     * @param $uuid
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function submitSurvey($uuid) {
        $surveySubmission = $this->getDoctrine()
            ->getRepository(SurveySubmission::class)
            ->findByUuid($uuid);

        $images = $surveySubmission->getImages();

        foreach ($images as $image) {
            if ($image->getFake() === null) {
                return $this->json(['message' => vsprintf('Short image %s has no answer', [$image->getImage()->getUuid()])], 400);
            }
        }

        $surveySubmission->setSubmitted(true);

        $this->getDoctrine()->getManager()->flush();

        return $this->json('', 200);
    }
}
