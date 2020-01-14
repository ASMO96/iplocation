<?php
include_once 'vendor/autoload.php';

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\HttpClient\HttpClient;


use Symfony\Component\Console\Application;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


function ip_location($ip)
{

    $client = HttpClient::create();
    $response = $client->request('GET', 'http://ip-api.com/json/' . $ip);
    $result = $response->getContent();
    $result = json_decode($result, false);
    return $result;
}


class IP extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'ip:location';

    protected function configure()
    {
        $this->setDescription('IP Location');
        $this->addArgument('ip', InputArgument::REQUIRED, 'IP Field');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $location = ip_location($input->getArgument("ip"));
        $country = $location->country;
        $output->writeln("The Country : " . $country);
        return 0;
    }
}




if (php_sapi_name() == "cli") {
    $application = new Application();
    $application->add(new IP());
    $application->run();

} else {

    $app = new Silex\Application();
    $app->get('/iplocation/{ip}', function ($ip) {
        $location = ip_location($ip);
        return "<h1> The Country: " .  $location->country . "</h1>";
    });

    $app->run();

}

