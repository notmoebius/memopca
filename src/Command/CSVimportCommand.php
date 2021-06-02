<?php

// namespace App\Command;

// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\Console\Command\Command;
// use Symfony\Component\Console\Input\InputArgument;
// use Symfony\Component\Console\Input\InputInterface;
// use Symfony\Component\Console\Input\InputOption;
// use Symfony\Component\Console\Output\OutputInterface;
// use Symfony\Component\Console\Style\SymfonyStyle;
// use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
// use Symfony\Component\Serializer\Serializer;
// use App\Entity\User;

// class CSVimportCommand extends Command
// {
//     private EntityManagerInterface $em;

//     private string $dataUsers;

//     private UserRepository $userRepository;

//     private SymfonyStyle $io;

//     protected static $defaultName = 'CSVimport';
//     protected static $defaultDescription = 'Importer des données en provenance d\'un fichier CSV';
    
//     public function __construct(EntityManagerInterface $em, string $dataUsers, UserRepository $userRepository){
//         parent::__construct();

//         $this->UserRepository = $userRepository;
//         $this->datausers = $dataUsers;
//         $this->em = $em;
//     }
//     protected function configure()
//     {
//         $this
//             ->setName('csv:import')
//             ->addDescription('Importer des données en provenance d\'un fichier CSV')
//             ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
//         ;
//     }

//     protected function initialize(InputInterface $input, OutputInterface $output): void
//     {
//         $this->io = New SymfonyStyle($input, $output);
//     }

//     protected function execute(InputInterface $input, OutputInterface $output): int
//     {
//         $io = new SymfonyStyle($input, $output);
//         $this->createUsers();

//         $user = (new User())
//         $user->setFirstname($row['prenom']);
//         $user->setLastname($row['nom']);
//         $user->setProfession($row['profession']);
//         $user->setMobilenumber($row['mobile']);
//         $user->setPhonenumber($row['telephone']);
//         $user->setStructure($row['batiment']);
//         $user->setFloor($row['etage']);
//         $user->setRole($row['role']);
//         $user->setGrade($row['grade']);
//         $user->setDirectory($row['annuaire']);
//         $user->setOrganization($row['organisme']);

//         $this->em->persist($user);
//         $this->em->flush();
//         $this->addFlash('success', 'L\'ajout des contacts via le fichier CSV a été réalisé avec succès.')
//         return Command::SUCCESS;
//     }

//     private function getDataFromFile(): array
//     {
//         $file = $this->dataUsers . 'users.csv';
//         $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

//         $normalizers= [new ObjectNormalizer()];

//         $encoders = [
//             new CsvEncoder(),
//         ];

//         $serializer = new Serializer($normalizers, $encoders);

//         /** @var string $fileString */
//         $fileString = file_get_contents($file);

//         $data = $serializer->decode($fileString, $fileExtension);

//         dd($data);
//     }

//     private function createUsers(): void
//     {
//         $this->getDataFromFile(;)
//     }
// }
