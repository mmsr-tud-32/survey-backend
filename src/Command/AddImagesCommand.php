<?php

namespace App\Command;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author G.J.W. Oolbekkink <g.j.w.oolbekkink@gmail.com>
 * @since 23/06/2019
 */
class AddImagesCommand extends Command {
    protected static $defaultName = 'survey:http:add';

    protected function configure() {
        $this->setDescription('Add a folder of images to a survey on an HTTP endpoint');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln([
            'Add images from a directory to a survey',
            '',
        ]);

        $helper = $this->getHelper('question');

        $survey = $helper->ask($input, $output, new Question('Survey uuid: ', ''));
        $fake = $helper->ask($input, $output, new Question('Does this folder contain fake images? [y/n]: '));

        if ($fake != 'y' && $fake != 'n') {
            $output->writeln('Did not recognize fake option: ' . $fake);
            return;
        }

        $images = glob($helper->ask($input, $output, new Question('Directory: ')) . '/*');

        $host = $helper->ask($input, $output, new Question('host (https://127.0.0.1:8000/): ', 'https://127.0.0.1:8000/'));

        $client = new Client();

        foreach ($images as $image) {
            $imgExt = [ 'jpg', 'jpeg', 'png', 'gif' ];
            if (in_array(pathinfo($image, PATHINFO_EXTENSION), $imgExt) != true) {
                continue;
            }

            $response = $client->request('POST', $host . 'survey/' . $survey . '/images', [
                'verify' => false,
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => fopen($image, 'r'),
                        'filename' => basename($image),
                    ],
                    [
                        'name' => 'fake',
                        'contents' => $fake == 'y' ? '1' : '0',
                    ],
                ],
            ]);

            $responseBody = json_decode($response->getBody());

            $output->writeln('Uploaded image to: ' . $responseBody->image);
        }
    }
}
