<?php

namespace App\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author G.J.W. Oolbekkink <g.j.w.oolbekkink@gmail.com>
 * @since 25/06/2019
 */
class LockSurveyCommand extends Command {
    protected static $defaultName = 'survey:http:lock';

    protected function configure() {
        $this->setDescription('Locks new survey on an HTTP endpoint');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln([
            'Lock Survey',
            '',
        ]);

        $helper = $this->getHelper('question');

        $uuid = $helper->ask($input, $output, new Question('uuid: '));
        $host = $helper->ask($input, $output, new Question('host (https://127.0.0.1:8000/): ', 'https://127.0.0.1:8000/'));

        $client = new Client();

        $client->request('POST', $host . 'survey/' . $uuid . '/lock', [
            'verify' => false,
            'form_params' => []
        ]);


        $output->writeln(['', 'Locked survey', '',]);
    }

}
