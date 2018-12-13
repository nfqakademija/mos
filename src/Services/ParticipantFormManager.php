<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ParticipantFormManager
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface $encoder
     */
    private $encoder;

    /**
     * GroupManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return bool|null
     */
    public function handleEdit(FormInterface $form, Request $request): bool
    {
        $user = $form->getData();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }
}
