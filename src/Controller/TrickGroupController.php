<?php


namespace App\Controller;


use App\Entity\TrickGroup;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TrickGroupController extends AbstractController
{
    /**
     * @Route("add-group-trick", name="group_trick_add")
     */
    public function add(): Response
    {
        $trickGroup = new TrickGroup();
    }
}