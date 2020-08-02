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
use Symfony\Component\HttpFoundation\JsonResponse;

class ListController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index()
    {
        $alltimes = $this->getDoctrine()->getRepository(Times::class);
        $alltimes = $alltimes->findAll();

        foreach ($alltimes as $times) {
            $response[] = array(
                'name' => $times->getName(),
            );
        }
    
        return new JsonResponse(json_encode($response));
    }
}
