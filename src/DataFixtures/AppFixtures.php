<?php

namespace App\DataFixtures;

use App\Entity\LearningGroup;
use App\Entity\TimeSlot;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Region;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    private $dataSamples;

    public function __construct(UserPasswordEncoderInterface $encoder, DataSamples $dataSamples)
    {
        $this->encoder = $encoder;
        $this->dataSamples = $dataSamples;
    }

    public function load(ObjectManager $manager)
    {
        $consoleOutput = new ConsoleOutput();

        //#### config ####
        $groupsNumber = 10;
        $teachersNumber = 10;
        $maxParticipantsInGroup = 12;
        $maxTimeslotsInGroup = 5;

        $livingAreaTypes = ['miestas', 'kaimas'];
        $genres = ['vyras', 'moteris'];
        //#### end config ####

        $maleNames = $this->dataSamples->getMaleNames();
        $maleSurnames = $this->dataSamples->getMaleSurnames();
        $femaleNames = $this->dataSamples->getFemaleNames();
        $femaleSurnames = $this->dataSamples->getFemaleSurnames();
        $streets = $this->dataSamples->getStreetNames();
        $villages = $this->dataSamples->getVillages();
        
        //push all Regions to database
        $allRegionsObjects = [];
        $allRegionsData = $this->dataSamples->getAllRegionsData();
        foreach ($allRegionsData as $regionTitle => $extraData) {
            $region = new Region();
            $region
                ->setTitle($regionTitle)
                ->setIsProblematic($extraData['is_problematic']);
            $allRegionsObjects [] = $region;

            $manager->persist($region);
        }
        $manager->flush();

        //generate Manager///////////////////////////////////////////

        $userManager = new User();
        $userManager
            ->setUsername('manager')
            ->setPassword($this->encoder->encodePassword($userManager, 'labasmng1'))
            ->setEmail('manager@email.com')
            ->setName('Project')
            ->setSurname('Manager')
            ->setRoles([User::ROLE_ADMIN]);
        $manager->persist($userManager);

        //generate supervisor///////////////////////////////////////////

        $userSupervisor = new User();
        $userSupervisor
            ->setUsername('supervisor')
            ->setPassword($this->encoder->encodePassword($userManager, 'labasspv1'))
            ->setEmail('supervisor@email.com')
            ->setName('Project')
            ->setSurname('Supervisor')
            ->setRoles([User::ROLE_SUPERVISOR]);
        $manager->persist($userSupervisor);

        //teachers
        /**
         * @var $userTeacher User[]
         */
        for ($i = 0; $i <= $teachersNumber; $i++) {
            $gender = $genres[array_rand($genres, 1)];
            if ($gender === 'vyras') {
                $name = $maleNames[rand(0, sizeof($maleNames) - 1)];
                $surname = $maleSurnames[rand(0, sizeof($maleSurnames) - 1)];
            } else {
                $name = $femaleNames[rand(0, sizeof($femaleNames) - 1)];
                $surname = $femaleSurnames[rand(0, sizeof($femaleSurnames) - 1)];
            }
            
            $userTeacher[$i] = new User();
            $userTeacher[$i]
                ->setUsername('teacher' . $i)
                ->setPassword($this->encoder->encodePassword($userTeacher[$i], 'labast' . $i))
                ->setEmail($surname. $i . '@email.com')
                ->setName($name)
                ->setSurname($surname)
                ->setRoles([User::ROLE_TEACHER]);
            $manager->persist($userTeacher[$i]);
        }

        //creates groups
        for ($i = 0; $i < $groupsNumber; $i++) {
            //generate Group
            $group[$i] = new LearningGroup();
            $groupStreet = $streets[rand(0, sizeof($streets) - 1)];
            $groupStreet .= ' ' . $i;
            $region = $allRegionsObjects[rand(0, sizeof($allRegionsObjects) - 1)];
            $regionTitle = $region->getTitle();
            if (strpos($regionTitle, 'raj')) {
                $groupStreet .= ', ' . $villages[rand(0, sizeof($villages) - 1)];
            }
            $group[$i]->setAddress($groupStreet);
            $group[$i]->setTeacher($userTeacher[rand(0, $teachersNumber)]);
            $group[$i]->setRegion($region);
            $participantsCount = rand(5, $maxParticipantsInGroup);
            for ($j = 0; $j < $participantsCount; $j++) {
                $participantGender = $genres[array_rand($genres, 1)];
                if ($participantGender === 'vyras') {
                    $participantName = $maleNames[rand(0, sizeof($maleNames) - 1)];
                    $participantSurname = $maleSurnames[rand(0, sizeof($maleSurnames) - 1)];
                } else {
                    $participantName = $femaleNames[rand(0, sizeof($femaleNames) - 1)];
                    $participantSurname = $femaleSurnames[rand(0, sizeof($femaleSurnames) - 1)];
                }
                $participantStreet = $streets[rand(0, sizeof($streets) - 1)];
                $participantStreet .= ' ' . rand(1, 49);
                if (strpos($region->getTitle(), 'raj')) {
                    $livingAreaType = 'kaimas';
                    $participantStreet .= ', ' . $villages[rand(0, sizeof($villages) - 1)];
                } else {
                    $livingAreaType = 'miestas';
                }
                $userParticipant = new User();
                $unique = $this->randomString(3);
                $userParticipant
                    ->setUsername($this->convertLtToLatin($participantName . '.' . $participantSurname) . '.' . $unique . $i)
                    ->setPassword($this->encoder->encodePassword($userParticipant, rand(1000, 1100)))
                    ->setEmail( $this->convertLtToLatin($participantSurname) 
                      . '.' . $unique . $i . '@email.com')
                    ->setName($participantName)
                    ->setSurname($participantSurname)
                    ->setRegion($region)
                    ->setBirthDate('19' . rand(45, 79) . '-' . rand(1, 12) . '-' . rand(1, 28))
                    ->setRoles([User::ROLE_PARTICIPANT])
                    ->setLivingAreaType($livingAreaType)
                    ->setGender($participantGender)
                    ->setPhone($this->randomPhoneNumber())
                    ->setAddress($participantStreet);
                $manager->persist($userParticipant);

                $group[$i]->addParticipant($userParticipant);
            }
            $timeSlotsCount = rand(3, $maxTimeslotsInGroup);
            $month = rand(11, 12);
            for ($k = 0; $k < $timeSlotsCount; $k++) {
                $timeSlot = new TimeSlot();
                $timeSlot->setDate("2018-" . $month . '-' . rand(1, 29));
                $timeSlot->setStartTime(rand(9, 20) . ':' . "00");
                $timeSlot->setDuration(90);

                $group[$i]->addTimeSlot($timeSlot);
                $manager->persist($timeSlot);
            }
            $group[$i]->updateStartEndDates();
            $manager->persist($group[$i]);

            $consoleOutput->writeln('<info>' . 'Created ' . ($i+1) . ' of ' . $groupsNumber . ' groups </info>');
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

    /**
     * Create a random phone number
     */
    private function randomPhoneNumber()
    {
        $phoneNumber = '8';
        $length = 7;
        for ($i = 0; $i < $length; $i++) {
            $phoneNumber .= mt_rand(0, 9);
        }
        return $phoneNumber;
    }

    private function convertLtToLatin($str) {
            $a = array('Ą', 'Č', 'Ę', 'Ė', 'Į', 'Š', 'Ų', 'Ū', 'Ž', 'ą', 'č', 'ę', 'ė', 'į', 'š', 'ų', 'ū', 'ž');
            $b = array('A', 'C', 'E', 'E', 'I', 'S', 'U', 'U', 'Z', 'a', 'c', 'e', 'e', 'i', 's', 'u', 'u', 'z');
            return str_replace($a, $b, $str);
    }

}
