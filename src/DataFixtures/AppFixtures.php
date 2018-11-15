<?php

namespace App\DataFixtures;

use App\Entity\LearningGroup;
use App\Entity\TimeSlot;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Region;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        //generate Regions
        $regionKaunas = new Region();
        $regionKaunas
          ->setTitle('Kaunas')
          ->setIsCity(true);
        $manager->persist($regionKaunas);

        $regionKaunoR = new Region();
        $regionKaunoR
          ->setTitle('Kauno raj.')
          ->setIsCity(false);
        $manager->persist($regionKaunoR);

        $regionJonava = new Region();
        $regionJonava
          ->setTitle('Jonava')
          ->setIsCity(true);
        $manager->persist($regionJonava);

        $regionJonavosR = new Region();
        $regionJonavosR
          ->setTitle('Jonavos raj.')
          ->setIsCity(false);
        $manager->persist($regionJonavosR);

        //generate Users///////////////////////////////////////////

        $userAdmin = new User();
        $userAdmin
          ->setUsername('admin')
          ->setPassword($this->encoder->encodePassword($userAdmin, 'admin1'))
          ->setEmail('admin@email.com')
          ->setName('Administrator')
          ->setSurname('Administrator')
          ->setRoles([User::ROLE_ADMIN]);
        $manager->persist($userAdmin);


        //teachers
        /**
         * @var $userTeacher User[]
         */
        for ($i=1; $i<=3; $i++) {
            $userTeacher[$i] = new User();
            $userTeacher[$i]
              ->setUsername('teacher' . $i)
              ->setPassword($this->encoder->encodePassword($userTeacher[$i], 'teacher' . $i))
              ->setEmail('teacher' . $i .'@email.com')
              ->setName('Teachername' . $i)
              ->setSurname('Teachersurname' . $i)
              ->setRoles([User::ROLE_TEACHER]);
            $manager->persist($userTeacher[$i]);
        }

        //participants
        /**
         * @var $userParticipant User[]
         */
        for ($i=1; $i<=5; $i++) {
            $userParticipant[$i] = new User();
            $userParticipant[$i]
              ->setUsername('participant' . $i)
              ->setPassword($this->encoder->encodePassword($userParticipant[$i], 'participant' . $i))
              ->setEmail('participant' . $i .'@email.com')
              ->setName('Participantname' . $i)
              ->setSurname('Participantsurname' . $i)
              ->setRegion($regionJonavosR)
              ->setBirthDate(new \DateTime('1961-04-24'))
              ->setRoles([User::ROLE_PARTICIPANT]);
            $manager->persist($userParticipant[$i]);
        }


        //demo group
        //generate TimeSlot
        $timeSlot1 = new TimeSlot();
        $timeSlot1->setStartTime(new \DateTime("2019-01-01"));
        $timeSlot1->setDurationMinutes(90);
        $manager->persist($timeSlot1);

        $timeSlot2 = new TimeSlot();
        $timeSlot2->setStartTime(new \DateTime("2019-01-03"));
        $timeSlot2->setDurationMinutes(90);
        $manager->persist($timeSlot2);

        $timeSlot3 = new TimeSlot();
        $timeSlot3->setStartTime(new \DateTime("2019-01-07"));
        $timeSlot3->setDurationMinutes(90);
        $manager->persist($timeSlot3);

        //generate Group
        $group1 = new LearningGroup();
        $group1->setAddress('Lapių 16, Kulautuva')
            ->addParticipant($userParticipant[1])
            ->addParticipant($userParticipant[3])
            ->addTimeSlot($timeSlot1)
            ->addTimeSlot($timeSlot2)
            ->addTimeSlot($timeSlot3);

        $manager->persist($group1);



        $manager->flush();
    }
}
