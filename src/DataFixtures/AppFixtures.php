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
        $maxTimeslotsInGroup = 5;

        $livingAreaTypes = ['miestas', 'kaimas'];
        $genres = ['vyras', 'moteris'];
        //#### end config ####

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

        //generate Manager///////////////////////////////////////////

        $userManager = new User();
        $userManager
            ->setUsername('manager')
            ->setPassword($this->encoder->encodePassword($userManager, 'labasmng123'))
            ->setEmail('manager@email.com')
            ->setName('Project')
            ->setSurname('Manager')
            ->setRoles([User::ROLE_ADMIN]);
        $manager->persist($userManager);

        //generate supervisor///////////////////////////////////////////

        $userSupervisor = new User();
        $userSupervisor
          ->setUsername('supervisor')
          ->setPassword($this->encoder->encodePassword($userManager, 'labasspv123'))
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
            $region = $this->randomRegion();
            for ($j = 0; $j < $participantsCount; $j++) {
                $userParticipant = new User();
                $unique = $this->randomString(8);
                $userParticipant
                    ->setUsername('participant_' . $unique . '_' . $i)
                    ->setPassword($this->encoder->encodePassword($userParticipant, rand(1000, 1100)))
                    ->setEmail('participant' . $unique . '@email.com')
                    ->setName(ucfirst($unique))
                    ->setSurname(ucfirst($unique))
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

    private function randomRegion() : string
    {
        $allRegions = [
            'Akmenės raj.',
            'Alytaus m.',
            'Alytaus raj.',
            'Anykščių raj.',
            'Birštono m.',
            'Biržų raj.',
            'Druskininkų m.',
            'Elektrėnų m.',
            'Ignalinos raj.',
            'Jonavos raj.',
            'Joniškio raj.',
            'Jurbarko raj.',
            'Kaišiadorių raj.',
            'Kalvarijos m.',
            'Kauno raj.',
            'Kazlų Rūdos m.',
            'Kėdainių raj.',
            'Kelmės raj.',
            'Klaipėdos m.',
            'Klaipėdos raj.',
            'Kretingos raj.',
            'Kupiškio raj.',
            'Lazdijų raj.',
            'Marijampolės m.',
            'Mažeikių raj.',
            'Molėtų raj.',
            'Neringos m.',
            'Pagėgių m.',
            'Pakruojo raj.',
            'Palangos m.',
            'Panevėžio m.',
            'Panevėžio raj.',
            'Pasvalio raj.',
            'Plungės raj.',
            'Prienų raj.',
            'Radviliškio raj.',
            'Raseinių raj.',
            'Rietavo m.',
            'Rokiškio raj.',
            'Skuodo raj.',
            'Šakių raj.',
            'Šalčininkų raj.',
            'Šiaulių m.',
            'Šiaulių raj.',
            'Šilalės raj.',
            'Šilutės raj.',
            'Širvintų raj.',
            'Švenčionių raj.',
            'Tauragės raj.',
            'Telšių raj.',
            'Trakų raj.',
            'Ukmergės raj.',
            'Utenos raj.',
            'Varėnos raj.',
            'Vilkaviškio raj.',
            'Vilniaus m.',
            'Vilniaus raj.',
            'Visagino m.',
            'Zarasų raj.',
        ];

        return $allRegions[rand(0, count($allRegions)-1)];

    }
}
