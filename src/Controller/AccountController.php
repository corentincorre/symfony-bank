<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\AccountRepository;
use App\Repository\TransactionRepository;

class AccountController extends AbstractController
{
    /**
     * @Route("/", name="app_account")
     */
    public function index(UserInterface $user, AccountRepository $ar, TransactionRepository $tr): Response
    {
        if(!$user) return false;
        $account = $ar->findOneBy(['user'=> $user->getId()]);
        $addFund = $tr->findBy(['receiver' => $account]);
        $virements = $tr->findBy(['sender' => $account->getId()]);
        $transactions = array_merge($addFund, $virements);
        usort($transactions, function($a, $b) {return strcmp($a->getId(), $b->getId());});
        return $this->render('account/index.html.twig', [
            'user' => $user,
            'account' => $account,
            'transactions' => $transactions,
        ]);
    }
}
