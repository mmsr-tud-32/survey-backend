<?php

namespace App\Command;

use App\Entity\SurveyImage;
use App\Repository\SurveyRepository;
use App\Repository\SurveySubmissionImageRepository;
use App\Repository\SurveySubmissionRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author G.J.W. Oolbekkink <g.j.w.oolbekkink@gmail.com>
 * @since 27/06/2019
 */
class ExportCommand extends Command {
    protected static $defaultName = 'survey:export';
    /**
     * @var SurveyRepository
     */
    private $surveyRepository;
    /**
     * @var SurveySubmissionRepository
     */
    private $surveySubmissionRepository;
    /**
     * @var SurveySubmissionImageRepository
     */
    private $surveySubmissionImageRepository;

    public function __construct(SurveyRepository $surveyRepository, SurveySubmissionRepository $surveySubmissionRepository, SurveySubmissionImageRepository $surveySubmissionImageRepository) {
        parent::__construct();
        $this->surveyRepository = $surveyRepository;
        $this->surveySubmissionRepository = $surveySubmissionRepository;
        $this->surveySubmissionImageRepository = $surveySubmissionImageRepository;
    }

    protected function configure() {
        $this->setDescription('Export the results of a survey');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln([
            'Export the results of a survey',
            '',
        ]);

        $helper = $this->getHelper('question');

        $surveyUuid = $helper->ask($input, $output, new Question('Survey uuid: ', ''));
        $stage = $helper->ask($input, $output, new Question('Stage (short/long/practise): ', ''));

        $survey = $this->surveyRepository->findByUuid($surveyUuid);

        /** @var SurveyImage[] $images */
        $images = $survey->getImages()->toArray();
        $submissions = $survey->getSubmissions();

        $handle = fopen('export_' . $stage . '.csv', 'w');

        $cols = iterator_to_array((function () use ($images) {
            yield 'uuid';
            yield 'name';
            yield 'age';
            yield 'feedback';
            for ($i = 0; $i < count($images); $i++) {
                yield 'image_id_' . $i;
                yield 'image_stage_' . $i;
                yield 'image_fake_' . $i;
                yield 'image_answer_' . $i;
            }
        })());

        fputcsv($handle, $cols, ';');

        foreach ($submissions as $submission) {
            if (!$submission->getSubmitted()) continue;

            $row = [];

            $row[] = $submission->getUuid();
            $row[] = $submission->getName();
            $row[] = $submission->getAge();
            $row[] = $submission->getFeedback();

            foreach ($images as $image) {
                $submissionImage = $this->surveySubmissionImageRepository->findForSubmission($image, $submission, $stage);

                if ($submissionImage == null) {
                    $row[] = null;
                    $row[] = null;
                    $row[] = null;
                    $row[] = null;
                } else {
                    $row[] = $image->getUuid();
                    $row[] = $submissionImage->getStage();
                    $row[] = $this->formatOptionalBool($image->getFake());
                    $row[] = $this->formatOptionalBool($submissionImage->getFake());
                }
            }

            fputcsv($handle, $row, ';');
        }

        fclose($handle);
    }

    /**
     * @param $image
     * @return string
     */
    protected function formatOptionalBool($image): string {
        return $image == null ? '' : $image == true ? '1' : '0';
    }

}
