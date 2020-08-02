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

class StartController extends AbstractController
{
    /**
     * @Route("/start", name="start")
     */

     // This functions manages the start of a new time tracker counter.
    public function index(Request $request)
    {
        if ($request->request->get('name')){
            // set name
            $name = $request->request->get('name');
            // Start ORM
            $entityManager = $this->getDoctrine()->getManager();
            // Query for one item with the same name, to check if it exists.
            $timetracker = $entityManager->getRepository(Times::class)->findOneBy(array('name' => $name));
            // If the row does not exist, create a new one.
            if (!$timetracker) {
                unset($timetracker);
                unset($entityManager);
                $entityManager = $this->getDoctrine()->getManager();
                // Initialize new Times ORM and set the name and datetime. Everything is either 0 or now.
                $timetracker = new Times();
                $timetracker->setName($name);
                $timetracker->setTime(\DateTime::createFromFormat('H:i:s', '00:00:00'));
                $timetracker->setStartTime(new \DateTime());
                // Set to true because we just started executing it.
                $timetracker->setStatus(true);
                // Execute the query
                $entityManager->persist($timetracker);
                $entityManager->flush();
                return $this->json([
                    'message' => 'Started Tracking Time for the '.$name.' task.',
                    'status' => '200',
                ]);
            // If it DOES exist...
            } else {
                // Update start time and status.
                $timetracker->setStartTime(new \DateTime());
                $timetracker->setStatus(true);
                // Execute the query. No need to persist, it already exists in the DB.
                $entityManager->flush();
                return $this->json([
                    'message' => 'Started Tracking Time for the '.$name.' task.',
                    'status' => '200',
                ]);
            }
        }
    }
}
