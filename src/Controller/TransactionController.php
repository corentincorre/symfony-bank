<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\Account;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\VirementType;
use App\Form\AddFundType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transaction", name="app_transaction")
     */
    public function index(): Response
    {
        return $this->render('transaction/index.html.twig', [
            'controller_name' => 'TransactionController',
        ]);
    }
    /**
     * @Route("/virement", name="app_virement")
     */
    public function virement(Request $request, EntityManagerInterface $em, UserInterface $user, AccountRepository $ar): Response
    {
        $virement = new Transaction();
        $form = $this->createForm(VirementType::class, $virement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $virement = $form->getData();
            $account = $ar->findOneBy(['user'=> $user->getId()]);
            $virement->setSender($account->getId());
            if($account->getAmount() - $virement->getAmount() >= 0.00){
                $account->setAmount( $account->getAmount() - $virement->getAmount());
                $account2 = $ar->findOneBy(['user'=> $virement->getReceiver()]);
                $account2->setAmount( $account2->getAmount() + $virement->getAmount());
                $em->persist($virement);
                $em->flush();
                $this->addFlash('success', 'Virement effectué');
                return $this->redirectToRoute('app_account');
            }else{
                $this->addFlash('error', 'Fonds insuffisants');
            }
        }
        return $this->render('transaction/virement.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/add-fund", name="app_add_fund")
     */
    public function addFund(Request $request, EntityManagerInterface $em, UserInterface $user, AccountRepository $ar): Response
    {
        $virement = new Transaction();
        $form = $this->createForm(AddFundType::class, $virement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $virement = $form->getData();
            $account = $ar->findOneBy(['user'=> $user->getId()]);
            $virement->setReceiver($account);
            //print_r($virement);die;
            $account->setAmount( $account->getAmount() + $virement->getAmount());
            $em->persist($virement);
            $em->flush();
            $this->addFlash('success', 'Ajout de fond effectué');
            return $this->redirectToRoute('app_account');
        }

        return $this->render('transaction/add_fund.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
