<?php
include_once 'vendor/autoload.php';

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\HttpClient\HttpClient;


use Symfony\Component\Console\Application;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IP extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'ip:location';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a new user.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a user...');

        $this
            // ...
            ->addArgument('ip',  InputArgument::REQUIRED , 'IP ')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("The Country : " . ip_location($input->getArgument("ip") )->country );
        return 0;
    }
}


function ip_location($ip){

    $client = HttpClient::create();

    $response = $client->request('GET', 'http://ip-api.com/json/' . $ip);


    $result = $response->getContent();

    $result = json_decode($result, false);

    return $result;

}

if (php_sapi_name() == "cli"){



    $application = new Application();

    $application->add( new IP());

    $application->run();


}
else {

    $app = new Silex\Application();

    $app->get('/ahamad/{x}', function ($x) {
        return $x;
    });

    $app->get('/iplocation/{ip}', function ($ip) {
        return "<h1> The Country: ".ip_location($ip)->country . "</h1>";
    });


    $app->run();

}

