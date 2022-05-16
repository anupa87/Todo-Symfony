<?php

namespace App\Controller;

use App\Entity\TodoList;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    #[Route('/', name: 'todoApp')]
    public function index(EntityManagerInterface $em): Response
    {
        $tasks = $em->getRepository(TodoList::class)->findBy([],['id'=>'DESC']);
        return $this->render('todo/index.html.twig',['tasks'=>$tasks]);
    }

    #[Route('/create', name: 'create_task',methods:['POST'])]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $title = trim ($request->get("title"));
        if(!empty("title")){
        $entityManager = $doctrine->getManager();
        $task = new TodoList();
        $task->setTitle($title);
        $entityManager->persist($task);
        $entityManager->flush();
        return $this ->redirectToRoute('todoApp');
        } else {
        return $this ->redirectToRoute('todoApp');
        }
     
    }

    #[Route('/update/{id}', name: 'update_task')]
    public function update(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $task = $entityManager->getRepository(TodoList::class)->find($id);
        $task->setStatus(!$task->isStatus());
        $entityManager -> flush();
        return $this ->redirectToRoute('todoApp');
    
    }

    #[Route('/delete/{id}', name: 'delete_task')]
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $task = $entityManager->getRepository(TodoList::class)->find($id);
        $entityManager -> remove($task);
        $entityManager -> flush();
        return $this ->redirectToRoute('todoApp');
     
    }
}
