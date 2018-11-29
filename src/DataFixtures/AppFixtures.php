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
        $livingAreaTypes = ['miestas', 'kaimas'];
        $genres = ['vyras', 'moteris'];


        $teachersNumber = 10;
        $groupsNumber = 100;
        $maxParticipantsInGroup = 15;
        $maxTimeslotsInGroup = 5;


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
          ->setPassword($this->encoder->encodePassword($userAdmin, '!admin1'))
          ->setEmail('admin@email.com')
          ->setName('Administrator')
          ->setSurname('Administrator')
          ->setRoles([User::ROLE_ADMIN]);
        $manager->persist($userAdmin);


        //teachers
        /**
         * @var $userTeacher User[]
         */
        for ($i=0; $i<=$teachersNumber; $i++) {
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

        //creates groups
        for ($i=0; $i<=$groupsNumber; $i++) {
            //generate Group
            $group[$i] = new LearningGroup();
            $group[$i]->setAddress('Savanorių pr. ' . $i . ', Kaunas');
            $group[$i]->setTeacher($userTeacher[rand(0, $teachersNumber)]);
            $participantsCount = rand(2, $maxParticipantsInGroup);
            for ($j = 0; $j < $participantsCount; $j++) {
                $userParticipant = new User();
                $unique = $this->randomString(8);
                $userParticipant
                  ->setUsername('participant_' . $unique . '_' . $i)
                  ->setPassword($this->encoder->encodePassword($userParticipant, rand(1000, 1100)))
                  ->setEmail('participant' . $unique .'@email.com')
                  ->setName('Na' . $unique)
                  ->setSurname('Su' . $unique)
                  ->setRegion($regionJonavosR)
                  ->setBirthDate('19'. rand(45, 75) .'-' . rand(1, 12) . '-' . rand(1, 28))
                  ->setRoles([User::ROLE_PARTICIPANT])
                  ->setLivingAreaType($livingAreaTypes[array_rand($livingAreaTypes, 1)])
                  ->setGender($genres[array_rand($genres, 1)]);
                $manager->persist($userParticipant);
                                
                $group[$i]->addParticipant($userParticipant);
            }
            $timeSlotsCount = rand(1, $maxTimeslotsInGroup);
            $month = rand(11, 12);
            for ($k = 0; $k < $timeSlotsCount; $k++) {
                $timeSlot = new TimeSlot();
                $startTime = new \DateTime("2018-" . $month . '-' . rand(1, 29));
                $timeSlot->setStartTime($startTime);
                $timeSlot->setDurationMinutes(90);
                
                $group[$i]->addTimeSlot($timeSlot);
                $manager->persist($timeSlot);
            }

            $manager->persist($group[$i]);
        }
        
        $manager->flush();
    }

     /**
     * Create a random string
     */
    private function randomString($length = 6)
    {
        $str = "";
        $characters = range('a', 'z');
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }
}
