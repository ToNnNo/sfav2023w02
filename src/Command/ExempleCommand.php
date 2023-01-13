<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Formatter\OutputFormatterStyleInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class ExempleCommand extends Command
{
    protected static $defaultName = 'app:exemple';
    protected static $defaultDescription = 'Découverte de la console commande de Symfony';

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

        $this->logger->info("App:Exemple :: Constructeur");

        parent::__construct(); // constructeur parent appel configure
    }

    protected function configure(): void
    {
        $this->logger->info("App:Exemple :: Configure");

        $this
            ->setHelp("Ceci est un texte d'aide pour l'utilisation de cette commande" )
            ->setAliases(['app:ex', 'app:ew'])
            ->setHidden(true)
            ->addArgument('name', InputArgument::REQUIRED, 'Nom de l\'utilisateur')
            ->addOption('success', null, InputOption::VALUE_NEGATABLE, 'Affiche le message de succès')
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info("App:Exemple :: Interact");
        $io = new SymfonyStyle($input, $output);

        if( null === $input->getArgument('name')) {
            $name = $io->ask("Quel est votre nom ?", "John Doe");
            $input->setArgument('name', $name);
        }
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info("App:Exemple :: Initialize");
        $outputStyleFire = new OutputFormatterStyle('red', 'yellow', ['bold', 'blink']);
        $output->getFormatter()->setStyle('fire', $outputStyleFire);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info("App:Exemple :: Execute");
        $io = new SymfonyStyle($input, $output);
        $io->title("Commande Exemple");

        if( !$input->hasArgument('name') ) {
            $io->error("Arf ! Ca n'a pas fonctionné ... Le nom est obligatoire");
            return Command::FAILURE;
        }

        $name = $input->getArgument('name');
        $io->text("Bonjour ".$name.".");

        if($input->isInteractive()) {
            $etat = $io->choice('Comment allez vous ce matin ?', [
                'Bien', 'Ca peut aller', 'Fatigué', 'Pas bien ...'
            ]);

            $io->text("?? > " . ($io->confirm("Alors ?") ? 'Oui' : 'Non') );

            $question = new Question('Ville ?');
            $question->setAutocompleterValues(['Lille', 'Paris', 'Los Angeles', 'New York', 'Londres']);
            $resultVille = $io->askQuestion($question);
            $this->logger->info(sprintf('Ville: %s', $resultVille));

            $io->text(sprintf('Réponse: <comment>%s</>', $etat));
            $io->text(sprintf('Réponse: <fire>%s</>', $etat));
        }

        if(!$input->getOption('no-success')) {
            $io->success("Félicitation! Nous avons réussi à executer notre commande");
        }

        return Command::SUCCESS;
    }
}
