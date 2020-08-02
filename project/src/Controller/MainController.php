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
        if ($request->request->get('name')) {
            // Get the data.
            $name = $request->request->get('name');
            // Start the doctrine manager.
            $entityManager = $this->getDoctrine()->getManager();
            // Query for one item with the same name, to check if it exists.
            $timetracker = $entityManager->getRepository(Times::class)->findOneBy(array('name' => $name));
            // If it does not...
            if (!$timetracker) {
                // Should always exist as they get created on start. If it doesnt throw error.
                throw $this->createNotFoundException(
                    'No timetrack found for the name '.$name
                );
            // If it DOES exist...
            } else {
                // Get the DB start time.
                $timeA = $timetracker->getStartTime();
                // Set the name,the new end time, and the status.
                $timetracker->setName($name);
                $timetracker->setEndTime(new \DateTime());
                $timetracker->setStatus(false);
                // Get the elapsed time & add the time passed. This is done by substracting start date and end date.
                $totaltime = $timetracker->getTime();
                $totaltime = $this->addTime($timeA, $totaltime);
                // Update time elapsed.
                $timetracker->setTime(\DateTime::createFromFormat('H:i:s', $totaltime->format('H:i:s')));
                // Execute the query.
                $entityManager->flush();
                
            }
            // Get all the rows for viewing the time spent and calculate the total time spent.
            $alltimes = $this->getDoctrine()->getRepository(Times::class);
            $alltimes = $alltimes->findAll();
            $totaltimetoday = $this->getTotalTime($alltimes);

            return $this->render('base.html.twig', array(
                'times' => $alltimes,
                'totaltime' => $totaltimetoday,
            ));
        } else {
            // If no petition has been made we just go to the main page.
            $alltimes = $this->getDoctrine()->getRepository(Times::class);
            $alltimes = $alltimes->findAll();

            // Get all the rows for viewing the time spent and calculate the total time spent.
            $totaltimetoday = $this->getTotalTime($alltimes);
            return $this->render('base.html.twig', array(
                'times' => $alltimes,
                'totaltime' => $totaltimetoday,
            ));
        }        
    }

    /**
     * This function is used to add time to an already existing datetime object.
     * Arguments: String with time in format: 00:00:00 (hours, minutes, seconds), datetime object.
     * 
     */
    public function addTime(DateTime $timeA, DateTime $totaltime)
    {
        $timestart = $timeA;
        $timeend= new \DateTime();
        $difference = $timestart->diff($timeend);
        $time = $difference->h.':'.$difference->i.':'.$difference->s;
        // Explode the time string.
        $explodedtime = array_map(function($num) {
            return (int) $num;
        }, explode(':', $time));
        // Create a new DateInterval. This object contains the elapsed time of the time tracker. We create this object so we can use the add method.
        $timeB = new \DateInterval('PT'.$explodedtime[0].'H'.$explodedtime[1].'M'.$explodedtime[2].'S');
        $totaltime->add($timeB);
        return $totaltime;
    }

    /**
     * This function gets total time spent today.
     * Arguments: an array containing a get ORM with all the timetracker items.
     */

    public function getTotalTime(array $alltimes)
    {
        $totaltimetoday = \DateTime::createFromFormat('H:i:s', '00:00:00');
        // Calculate total time for today.
        foreach ($alltimes as $key => $value) {
            $time = $value->getTime();
            $time = $time->format('H:i:s');

            $explodedtime = array_map(function($num) {
                return (int) $num;
            }, explode(':', $time));
            
            // Create a new DateInterval. This object contains the elapsed time of the time tracker. We create this object so we can use the add method.
            $timeB = new \DateInterval('PT'.$explodedtime[0].'H'.$explodedtime[1].'M'.$explodedtime[2].'S');
            $totaltimetoday->add($timeB);
        }

        return $totaltimetoday;
    }
}
