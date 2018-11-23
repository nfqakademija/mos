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
        $userParticipant = [];
        $timeSlot = [];
        
        $livingAreaTypes = ['miestas', 'kaimas'];
        $genres = ['vyras', 'moteris'];
        
        //generate Regions
        $regionKaunas = new Region();
        $regionKaunas
          ->setTitle('Kaunas')
          ->setIsCity(true)
          ->setIsProblematic('false');

        $manager->persist($regionKaunas);

        $regionKaunoR = new Region();
        $regionKaunoR
          ->setTitle('Kauno raj.')
          ->setIsCity(false)
          ->setIsProblematic('false');
        $manager->persist($regionKaunoR);

        $regionJonava = new Region();
        $regionJonava
          ->setTitle('Jonava')
          ->setIsCity(true)
          ->setIsProblematic('true');
        $manager->persist($regionJonava);

        $regionJonavosR = new Region();
        $regionJonavosR
          ->setTitle('Jonavos raj.')
          ->setIsCity(false)
          ->setIsProblematic('true');
        $manager->persist($regionJonavosR);

        //generate admin///////////////////////////////////////////

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
        for ($i=0; $i<=3; $i++) {
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

        //creates 111 participants
        /**
         * @var $userParticipant User[]
         */
        for ($i=0; $i<=110; $i++) {
            $userParticipant[$i] = new User();
            $userParticipant[$i]
              ->setUsername('participant' . $i)
              ->setPassword($this->encoder->encodePassword($userParticipant[$i], rand(1000, 1100)))
              ->setEmail('participant' . $i .'@email.com')
              ->setName('Participantname' . $i)
              ->setSurname('Participantsurname' . $i)
              ->setRegion($regionJonavosR)
              ->setBirthDate(new \DateTime('1961-04-24'))
              ->setRoles([User::ROLE_PARTICIPANT])
              ->setLivingAreaType($livingAreaTypes[array_rand($livingAreaTypes, 1)])
              ->setGender($genres[array_rand($genres, 1)]);
            $manager->persist($userParticipant[$i]);
        }

        //demo group
        //generate TimeSlot
        $timeSlot1 = new TimeSlot();
        $timeSlot1->setStartTime(new \DateTime("2018-11-01"));
        $timeSlot1->setDurationMinutes(90);
        $manager->persist($timeSlot1);

        $timeSlot2 = new TimeSlot();
        $timeSlot2->setStartTime(new \DateTime("2018-11-03"));
        $timeSlot2->setDurationMinutes(90);
        $manager->persist($timeSlot2);

        $timeSlot3 = new TimeSlot();
        $timeSlot3->setStartTime(new \DateTime("2018-11-07"));
        $timeSlot3->setDurationMinutes(90);
        $manager->persist($timeSlot3);

        //generate Group
        $group1 = new LearningGroup();
        $group1->setAddress('Lapių 16, Kulautuva')
            ->setTeacher($userTeacher[1])
            ->addParticipant($userParticipant[1])
            ->addParticipant($userParticipant[2])
            ->addTimeSlot($timeSlot1)
            ->addTimeSlot($timeSlot2)
            ->addTimeSlot($timeSlot3);

        $manager->persist($group1);
        
        
        //generate TimeSlot
        $timeSlot4 = new TimeSlot();
        $timeSlot4->setStartTime(new \DateTime("2018-12-10"));
        $timeSlot4->setDurationMinutes(90);
        $manager->persist($timeSlot4);

        $timeSlot5 = new TimeSlot();
        $timeSlot5->setStartTime(new \DateTime("2018-12-15"));
        $timeSlot5->setDurationMinutes(90);
        $manager->persist($timeSlot5);
        
        //generate Group
        $group2 = new LearningGroup();
        $group2->setAddress('Savanorių pr. 254, Kaunas')
          ->setTeacher($userTeacher[2])
          ->addParticipant($userParticipant[1])
          ->addParticipant($userParticipant[2])
          ->addParticipant($userParticipant[3])
          ->addTimeSlot($timeSlot4)
          ->addTimeSlot($timeSlot5);
        $manager->persist($group2);


        //creates 101 timeslots
        for ($i=0; $i<=100; $i++) {
            //generate TimeSlot
            $timeSlot[$i] = new TimeSlot();
            $startTime = new \DateTime("2018-" . rand(11, 12) . '-' . rand(1, 29));
            $timeSlot[$i]->setStartTime($startTime);
            $timeSlot[$i]->setDurationMinutes(90);
            $manager->persist($timeSlot[$i]);
        }

        //creates groups
        for ($i=0; $i<=100; $i++) {
            //generate Group
            $group[$i] = new LearningGroup();
            $group[$i]->setAddress('Savanorių pr. ' . $i . ', Kaunas');
            $group[$i]->setTeacher($userTeacher[2]);
            $participantsCount = rand(7, 14);
            for ($j = 0; $j < $participantsCount; $j++) {
                $group[$i]->addParticipant($userParticipant[rand(0, 99)]);
            }
            $timeSlotsCount = rand(3, 4);
            for ($k = 0; $k < $timeSlotsCount; $k++) {
                $group[$i]->addTimeSlot($timeSlot[rand(0, 99)]);
            }

            $manager->persist($group[$i]);
        }
        
        $manager->flush();
    }
}
