<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\ClientRepository;
use App\Repository\CouponRepository;
use App\Entity\Coupon;

#[AsCommand(
name: 'coupon',
description: 'Add a short description for your command',
)]
class CouponCommand extends Command
{
    public function __construct(ClientRepository $clientRepository, CouponRepository $couponRepository, MailerInterface $mailer, string $name = null)
    {
        $this->clientRepository = $clientRepository;
        $this->couponRepository = $couponRepository;
        $this->mailer = $mailer;
        parent::__construct($name);
    }

    protected function execute(EntityManagerInterface $entityManager, coupon $coupon, InputInterface $input, OutputInterface $output): int
    {
        // Finding All Clients

        $clients = $this->clientRepository->findAll();
        $lastyear = date("Y-m-d", strtotime("-365 days"));


        foreach ($clients as $client) {
            // Finding Client Older Than a year
            // I couldn't figure out the specific query builder to use

            if ($client->getCreatedAt() > $lastyear) {
                // Generating Coupon
                $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $yearcoupon = "";
                for ($i = 0; $i < 10; $i++) {
                    $yearcoupon .= $chars[mt_rand(0, strlen($chars) - 1)];
                }

                $coupon = new Coupon;
                $coupon->setCode($yearcoupon);
                $coupon->setPercentageOff(30);
                $entityManager->persist($coupon);
                $entityManager->flush();

                // Sending Email

                $emailadress = $client->getEmail();

                $email = (new Email())
                    ->from('aaa.bbb@ccc.com')
                    ->to($emailadress)
                    ->subject('Your Coupon to celebrate over a year with us')
                    ->text($yearcoupon)
                    ->html('<p>HeeHee</p>');

                $this->mailer->send($email);
            }
        }

        $io = new SymfonyStyle($input, $output);

        $io->success(sprintf('Vouchers Sent'));

        return Command::SUCCESS;
    }
}