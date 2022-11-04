<?php

namespace App\Controller;

use App\Entity\Debit;
use App\Entity\Mois;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EntityManagerInterface $manager, Request $request ,DataTableFactory $dataTableFactory): Response
    {
//        $mois = $manager->getRepository(Mois::class)->find(date('n'));
//        dd($mois);
    $debits = $manager->getRepository(Debit::class)
        ->findAll(); // TODO: modifier lapel en fonction du mois et de la categories

        foreach ($debits as $debit) {
            $title = $debit->getTitle();
            $montant = $debit->getMontant();

        }
//$table = $this->createDataTableFromType(DebitTableType::Class)->handleRequest($request);

        $table = $dataTableFactory->create()
            ->add('title',TextColumn::class, ['label' => 'Titre', 'className' =>'bold'])
            ->add('montant', TextColumn::class, ['label' => 'Montant', 'className' =>'bold'])
            ->createAdapter(ArrayAdapter::class,[
                [
                    'title' =>$title,
                    'montant' => $montant,

                ],
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'datatable' => $table,
        ]);
    }
}
