<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use App\Entity\Times;
use App\Form\Times\FormTimes;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(Request $request)
    {
        // Check if the request has been done (If the stop button has been pressed).
        if ($request->request->get('name') && $request->request->get('time')) {
            // Get the data.
            $name = $request->request->get('name');
            $time = $request->request->get('time');
            // Start the doctrine manager.
            $entityManager = $this->getDoctrine()->getManager();
            // Query for one item with the same name, to check if it exists.
            $timetracker = $entityManager->getRepository(Times::class)->findOneBy(array('name' => $name));

            // If it does not...
            if (!$timetracker) {
                // Initialize new Times ORM and set the name and datetime.

                $timetracker = new Times();
                $timetracker->setName($name);
                $timetracker->setTime(\DateTime::createFromFormat('h:i:s', $time));

                // tell Doctrine you want to (eventually) save the Product (no queries yet)
                $entityManager->persist($timetracker);

                // Execute the query.
                $entityManager->flush();
            // If it DOES exist...
            } else {
                // Get the DB object time.
                $timeA = $timetracker->getTime();
                // Explode it to allow it's usage on DateInterval Object.
                $explodedtime = array_map(function($num) {
                    return (int) $num;
                }, explode(':', $time));
                // Create a new DateInterval. This object contains the elapsed time of the time tracker. We create this object so we can use the add method.
                $timeB = new \DateInterval('PT'.$explodedtime[0].'H'.$explodedtime[1].'M'.$explodedtime[2].'S');
                $timeA->add($timeB);
                // Set the name and new date time.
                $timetracker->setName($name);
                $timetracker->setTime(\DateTime::createFromFormat('H:i:s', $timeA->format('H:i:s')));

                // Execute the query.
                $entityManager->flush();
                
            }
            // We are done. Return to the time tracker.
            $alltimes = $this->getDoctrine()->getRepository(Times::class);
            $alltimes = $alltimes->findAll();
            return $this->render('base.html.twig', array(
                'times' => $alltimes,
            ));
        } else {
            // If no petition has been made we just go to the main page.
            $alltimes = $this->getDoctrine()->getRepository(Times::class);
            $alltimes = $alltimes->findAll();
            return $this->render('base.html.twig', array(
                'times' => $alltimes,
            ));
        }        
    }
}
