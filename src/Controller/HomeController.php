<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Entity\Debit;
use App\Entity\Mois;
use App\Form\DebitType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Adapter\Doctrine\Event\ORMAdapterQueryEvent;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapterEvents;
use Omines\DataTablesBundle\Column\MapColumn;
use Omines\DataTablesBundle\Column\NumberColumn;
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

        $date = 12;
        $credits = $manager->getRepository(Credit::class)->findAllByMois($date);

        $tabCreditMontant = [];
        foreach ($credits as $credit){
            $title = $credit->getTitle();
            $montant = $credit->getMontant();
            $tabCreditMontant[] = $credit->getMontant();
            foreach ($credit->getIdMois() as $monthCredit){
                $idMois = $monthCredit->getId();
                $mois = $monthCredit->getTitle();
            }
        }
        $debits = $manager->getRepository(Debit::class)->findAllByMois($date);
        $debitsSum = $manager->getRepository(Debit::class)->findSumByMois($date);
//dd($debitsSum);
        $totalCredits = array_sum($tabCreditMontant);

        $tabDebitMontant = [];
        foreach ($debits as $debit){
            $title = $debit->getTitle();
            $montant = $debit->getMontant();
            $tabDebitMontant[] = $debit->getMontant();
            foreach ($debit->getIdMois() as $monthDebit){
                $idMois = $monthDebit->getId();
                $mois = $monthDebit->getTitle();
            }
        }
        $totalDebits = array_sum($tabDebitMontant);

        $reste = $totalCredits - $totalDebits;



//        $tableDebit = $dataTableFactory->createFromType(DebitType::Class)->handleRequest($request);
        $tableDebit = $dataTableFactory->create()
            ->add('title',TextColumn::class, [
                'label' => 'Titre',
                'className' =>'bold',
            ])
            ->add('montant', TextColumn::class, [
                'label' => 'Montant',
                'className' =>'bold',

            ])
//            ->add('mois', TextColumn::class, [
//                'label' => 'Mois',
//                'className' =>'bold',
//
//            ])
            ->createAdapter(ArrayAdapter::class,[
                [
                    'titre' => $debit->getTitle(),
                    'montant' => $debit->getMontant(),
//                    'mois' => $mois,

                ],
            ])
            ->handleRequest($request);


        if ($tableDebit->isCallback()) {
            return $tableDebit->getResponse();
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'datatable' => $tableDebit,
            'totalDebit' => $totalDebits,
            'totalCredit' => $totalCredits,
            'debits'=>$debits,
            'reste'=>$reste,
            'sumDebit'=>$debitsSum,

        ]);
    }
}
