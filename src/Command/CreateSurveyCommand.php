<?php

namespace App\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author G.J.W. Oolbekkink <g.j.w.oolbekkink@gmail.com>
 * @since 23/06/2019
 */
class CreateSurveyCommand extends Command {
    protected static $defaultName = 'survey:http:create';

    protected function configure() {
        $this->setDescription('Creates a new survey on an HTTP endpoint');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln([
            'Create Survey',
            '',
        ]);

        $helper = $this->getHelper('question');

        $title = $helper->ask($input, $output, new Question('title (My Survey): ', 'My Survey'));
        $description = $helper->ask($input, $output, new Question('description (): ', ''));
        $num_practise = $helper->ask($input, $output, new Question('num_practise (2): ', '2'));
        $num_question_long = $helper->ask($input, $output, new Question('num_question_long (2): ', '2'));
        $num_question_short = $helper->ask($input, $output, new Question('num_question_short (2): ', '2'));
        $timeout_short = $helper->ask($input, $output, new Question('timeout_short (1): ', '1'));
        $timeout_long = $helper->ask($input, $output, new Question('timeout_long (10): ', '10'));

        $host = $helper->ask($input, $output, new Question('host (https://127.0.0.1:8000/): ', 'https://127.0.0.1:8000/'));

        $client = new Client();

        $response = $client->request('POST', $host . 'survey', [
            'verify' => false,
            'form_params' => [
                'title' => $title,
                'description' => $description,
                'num_practise' => $num_practise,
                'num_question_long' => $num_question_long,
                'num_question_short' => $num_question_short,
                'timeout_short' => $timeout_short,
                'timeout_long' => $timeout_long,
            ]
        ]);

        $responseBody = json_decode($response->getBody());

        $output->writeln([
            '',
            'Created survey with uuid: ' . $responseBody->uuid,
            '',
        ]);
    }

}
