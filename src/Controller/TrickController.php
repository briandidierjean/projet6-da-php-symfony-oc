<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @Route("", name="trick_home")
     */
    public function index()
    {
        return $this->render('trick/home.html.twig', []);
    }

    public function show()
    {
        return $this->render('trick/show.html.twig', []);
    }

    public function add()
    {
        return $this->render('trick/add.html.twig', []);
    }

    public function update()
    {
        return $this->render('trick/update.html.twig', []);
    }

    public function delete()
    {

    }
}