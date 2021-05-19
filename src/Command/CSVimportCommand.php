<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Entity\User;

class CSVimportCommand extends Command
{
    private EntityManagerInterface $em;

    private string $dataUsers;

    private UserRepository $userRepository;

    private SymfonyStyle $io;

    protected static $defaultName = 'CSVimport';
    protected static $defaultDescription = 'Importer des données en provenance d\'un fichier CSV';
    
    public function __construct(EntityManagerInterface $em, string $dataUsers, UserRepository $userRepository){
        parent::__construct();

        $this->UserRepository = $userRepository;
        $this->datausers = $dataUsers;
        $this->em = $em;
    }
    protected function configure()
    {
        $this
            ->setName('csv:import')
            ->addDescription('Importer des données en provenance d\'un fichier CSV')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = New SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->createUsers();

        $user = (new User())
        $user->setFirstname('francis');
        $user->setLastname('francis');
        $user->setProfession('responsable francis');
        $user->setMobilenumber('0612345678');
        $user->setPhonenumber('0412345678');
        $user->setStructure('Siege Francis');
        $user->setFloor('Etage 3');
        $user->setRole(1);
        $user->setGrade(1);
        $user->setDirectory(1);
        $user->setOrganization(1);

        if($_FILES['photo']['error'] === UPLOAD_ERR_OK){
            $rootPublic = $_SERVER['DOCUMENT_ROOT']; // Chemin jusqu'à "public"                    
            $publicOutput = 'asset/uploads/pictures/'; // Chemin à partir de public
            $dirOutput = $rootPublic.$publicOutput;

            switch ($_FILES['photo']['type']) {
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/pjpeg':
                    $extension = 'jpg';
                break;

                case 'image/png':
                    $extension = 'png';
                break;

            }

            $filename = uniqid().'.'.$extension;
            
            if(!move_uploaded_file($_FILES['photo']['tmp_name'], $dirOutput.$filename)){
                die('Erreur d\'upload fichier Images');
            }
            $user->setPhoto($publicOutput.$filename);
        }

        $this->em->persist($user);
        $this->em->flush();

        return Command::SUCCESS;
    }

    private function getDataFromFile(): array
    {
        $file = $this->dataUsers . 'users.csv';
        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

        $normalizers= [new ObjectNormalizer()];

        $encoders = [
            new CsvEncoder(),
        ];

        $serializer = new Serializer($normalizers, $encoders);

        /** @var string $fileString */
        $fileString = file_get_contents($file);

        $data = $serializer->decode($fileString, $fileExtension);

        dd($data);
    }

    private function createUsers(): void
    {
        $this->getDataFromFile(;)
    }
}
