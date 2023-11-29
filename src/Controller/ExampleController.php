<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class ExampleController extends AbstractController
{
    #[Route('/', name: 'example', methods: ['GET'])]
    public function create(): Response
    {
        $response = new StreamedResponse();

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');

        $response->setCallback(function (): void {
            for($i = 0; $i < 10; $i++) {

                echo "event: chunk\n";
                echo "data: {\"index\": " . $i . ", \"chunk\": \"Hello.\"}\n\n";

                flush();

                // Break the loop if the client aborted the connection (closed the page)
                if (connection_aborted()) {
                    break;
                }

                sleep(1);
            }
        });

        return $response->send();
    }
}