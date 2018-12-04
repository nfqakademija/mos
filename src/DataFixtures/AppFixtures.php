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
        //#### config ####
        $groupsNumber = 100;
        $teachersNumber = 10;
        $maxParticipantsInGroup = 15;
        $maxTimeslotsInGroup = 8;

        $livingAreaTypes = ['miestas', 'kaimas'];
        $genres = ['vyras', 'moteris'];
        //#### end config ####

        //push all Regions to database

        $allRegionsObjects = [];
        $allRegionsData = $this->getAllRegionsData();
        foreach ($allRegionsData as $regionTitle => $extraData) {
            $region = new Region();
            $region
                ->setTitle($regionTitle)
                ->setIsProblematic($extraData['is_problematic']);
            $allRegionsObjects [] = $region;

            $manager->persist($region);
        }

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
            $userTeacher[$i] = new User();
            $userTeacher[$i]
                ->setUsername('teacher' . $i)
                ->setPassword($this->encoder->encodePassword($userTeacher[$i], 'teacher' . $i))
                ->setEmail('teacher' . $i . '@email.com')
                ->setName('Teachername' . $i)
                ->setSurname('Teachersurname' . $i)
                ->setRoles([User::ROLE_TEACHER]);
            $manager->persist($userTeacher[$i]);
        }

        //creates groups
        for ($i = 0; $i <= $groupsNumber; $i++) {
            //generate Group
            $group[$i] = new LearningGroup();
            $group[$i]->setAddress('Savanorių pr. ' . $i . ', Kaunas');
            $group[$i]->setTeacher($userTeacher[rand(0, $teachersNumber)]);
            $participantsCount = rand(2, $maxParticipantsInGroup);
            $region = $allRegionsObjects[rand(0, sizeof($allRegionsObjects)-1)];
            for ($j = 0; $j < $participantsCount; $j++) {
                $userParticipant = new User();
                $unique = $this->randomString(8);
                $uniqueSurname = $this->randomString(5);
                $userParticipant
                    ->setUsername('participant_' . $unique . '_' . $i)
                    ->setPassword($this->encoder->encodePassword($userParticipant, rand(1000, 1100)))
                    ->setEmail('participant' . $unique . '@email.com')
                    ->setName(ucfirst($unique))
                    ->setSurname(ucfirst($uniqueSurname))
                    ->setRegion($region)
                    ->setBirthDate('19' . rand(45, 75) . '-' . rand(1, 12) . '-' . rand(1, 28))
                    ->setRoles([User::ROLE_PARTICIPANT])
                    ->setLivingAreaType($livingAreaTypes[array_rand($livingAreaTypes, 1)])
                    ->setGender($genres[array_rand($genres, 1)])
                    ->setPhone($this->randomPhoneNumber())
                    ->setAddress('Liepų g. ' . $i);
                $manager->persist($userParticipant);

                $group[$i]->addParticipant($userParticipant);
            }
            $timeSlotsCount = rand(1, $maxTimeslotsInGroup);
            $month = rand(11, 12);
            for ($k = 0; $k < $timeSlotsCount; $k++) {
                $timeSlot = new TimeSlot();
                $timeSlot->setDate("2018-" . $month . '-' . rand(1, 29));
                $timeSlot->setStartTime("10:30");
                $timeSlot->setDuration(90);

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


    private function getAllRegionsData() : array
    {
        $allRegions = [
          'Akmenės raj.' => ['is_problematic' => false],
          'Alytaus m.' => ['is_problematic' => false],
          'Alytaus raj.' => ['is_problematic' => true],
          'Anykščių raj.' => ['is_problematic' => false],
          'Birštono m.' => ['is_problematic' => false],
          'Biržų raj.' => ['is_problematic' => false],
          'Druskininkų m.' => ['is_problematic' => false],
          'Elektrėnų m.' => ['is_problematic' => false],
          'Ignalinos raj.' => ['is_problematic' => false],
          'Jonavos raj.' => ['is_problematic' => false],
          'Joniškio raj.' => ['is_problematic' => false],
          'Jurbarko raj.' => ['is_problematic' => false],
          'Kaišiadorių raj.' => ['is_problematic' => false],
          'Kalvarijos m.' => ['is_problematic' => false],
          'Kauno raj.' => ['is_problematic' => false],
          'Kazlų Rūdos m.' => ['is_problematic' => false],
          'Kėdainių raj.' => ['is_problematic' => false],
          'Kelmės raj.' => ['is_problematic' => false],
          'Klaipėdos m.' => ['is_problematic' => false],
          'Klaipėdos raj.' => ['is_problematic' => false],
          'Kretingos raj.' => ['is_problematic' => false],
          'Kupiškio raj.' => ['is_problematic' => false],
          'Lazdijų raj.' => ['is_problematic' => false],
          'Marijampolės m.' => ['is_problematic' => false],
          'Mažeikių raj.' => ['is_problematic' => false],
          'Molėtų raj.' => ['is_problematic' => false],
          'Neringos m.' => ['is_problematic' => false],
          'Pagėgių m.' => ['is_problematic' => false],
          'Pakruojo raj.' => ['is_problematic' => false],
          'Palangos m.' => ['is_problematic' => false],
          'Panevėžio m.' => ['is_problematic' => false],
          'Panevėžio raj.' => ['is_problematic' => false],
          'Pasvalio raj.' => ['is_problematic' => true],
          'Plungės raj.' => ['is_problematic' => false],
          'Prienų raj.' => ['is_problematic' => false],
          'Radviliškio raj.' => ['is_problematic' => false],
          'Raseinių raj.' => ['is_problematic' => false],
          'Rietavo m.' => ['is_problematic' => false],
          'Rokiškio raj.' => ['is_problematic' => false],
          'Skuodo raj.' => ['is_problematic' => false],
          'Šakių raj.' => ['is_problematic' => false],
          'Šalčininkų raj.' => ['is_problematic' => true],
          'Šiaulių m.' => ['is_problematic' => false],
          'Šiaulių raj.' => ['is_problematic' => false],
          'Šilalės raj.' => ['is_problematic' => false],
          'Šilutės raj.' => ['is_problematic' => false],
          'Širvintų raj.' => ['is_problematic' => false],
          'Švenčionių raj.' => ['is_problematic' => false],
          'Tauragės raj.' => ['is_problematic' => false],
          'Telšių raj.' => ['is_problematic' => false],
          'Trakų raj.' => ['is_problematic' => false],
          'Ukmergės raj.' => ['is_problematic' => false],
          'Utenos raj.' => ['is_problematic' => true],
          'Utenos m.' => ['is_problematic' => true],
          'Varėnos raj.' => ['is_problematic' => false],
          'Vilkaviškio raj.' => ['is_problematic' => false],
          'Vilniaus m.' => ['is_problematic' => false],
          'Vilniaus raj.' => ['is_problematic' => false],
          'Visagino m.' => ['is_problematic' => false],
          'Zarasų raj.' => ['is_problematic' => false],
        ];

        return $allRegions;
    }
}
