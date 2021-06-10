<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SolveTreeStat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'treeStat:solve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function buildResponses($respones)
    {
        $formatedReponses = [];
        foreach ($respones as $key => $value) {
            array_push($formatedReponses, $key);
        };
        return $formatedReponses;
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $myQuestions = new Collection();
        $myResponses = new Collection();

        $config_questions = [
            1 => [
                'label' => 'Choix du test statistique ?',
                'responses' => [
                    'Différence moyenne' => [
                        'nextQuestion' => 2,
                    ],
                    'Association entre deux variables' => [
                        'nextQuestion' => 6,
                    ],
                    'Corrélation' => [
                        'nextQuestion' => 7,
                    ],
                    'Regression' => [
                        'nextQuestion' => 8,
                    ],
                ],
            ],
            2 => [
                'label' => 'Nombre de moyenne ?',
                'responses' => [
                    'plus que 2' => [
                        'nextQuestion' => null,
                        'result' => 'Anova',
                    ],
                    'egale à 2' => [
                        'nextQuestion' => 3,
                    ],
                ],
            ],
            3 => [
                'label' => "Type d'échantillon ?",
                'responses' => [
                    "Non pairé" => [
                        'nextQuestion' => 4,
                        'result' => 'test non pairé',
                    ],
                    "pairé" => [
                        'nextQuestion' => 5,
                        'result' => 'test pairé',
                    ],
                ],

            ],
            4 => [
                'label' => "Taille d'échantillon ?",
                'responses' => [
                    "plus que 30" => [
                        'nextQuestion' => null,
                        'result' => 'Ttest',
                    ],
                    "moins que 30" => [
                        'nextQuestion' => null,
                        'result' => 'Wilcoxon mann Whithney',
                    ],
                ],
            ],
            5 => [
                'label' => "Taille d'échantillon ?",
                'responses' => [
                    "plus que 30" => [
                        'nextQuestion' => null,
                        'result' => 'Ttest',
                    ],
                    "moins que 30" => [
                        'nextQuestion' => null,
                        'result' => 'Wilcoxon',
                    ],
                ],
            ],
            6 => [
                'label' => "Type d'association ?",
                'responses' => [
                    "Entre deux variables" => [
                        'nextQuestion' => null,
                        'result' => "test d'indépendance",
                    ],
                    "Entre 1 variable et 1 population" => [
                        'nextQuestion' => null,
                        'result' => "test d'ajustement",
                    ],
                    "Réparation d'1 variable dans 1 échantillon" => [
                        'nextQuestion' => null,
                        'result' => "test d'homogénéité",
                    ],
                ],
            ],
            7 => [
                'label' => "Outliers ?",
                'responses' => [
                    "Oui" => [
                        'nextQuestion' => null,
                        'result' => "Kendall",
                        'commentaire' => "si tau-a et tau-b sont similaire donc pas ex aequo alors faire spearmann",
                    ],
                    "Non" => [
                        'nextQuestion' => null,
                        'result' => "Pearson",
                    ],
                ],
            ],
            8 => [
                'label' => "Nombre de VI ?",
                'responses' => [
                    "Une" => [
                        'nextQuestion' => null,
                        'result' => "Regression simple",
                    ],
                    "Plusieur" => [
                        'nextQuestion' => null,
                        'result' => "Regression multiples",
                        'commentaire' => 'Luca est un pélo :D'
                    ],
                ],
            ],
        ];

        $id = 1;
        $nextQuestion = null;
        $allQuestionsReponses = new Collection();
        do {
            $currentQuestion = $config_questions[$id];
            $TypeResponses = $this->buildResponses($currentQuestion['responses']);
            $response = $this->choice($currentQuestion['label'], $TypeResponses);
            if (in_array($response, $TypeResponses)) {
                $nextQuestion = $currentQuestion['responses'][$response]['nextQuestion'];
                $allQuestionsReponses->add($currentQuestion['label'] . "\n" . '  - ' .  $response);
                $id = $nextQuestion;
            };
        } while ($nextQuestion !== null);

        $currentQuestion['responses'][$response]['result'];
        $this->error($currentQuestion['responses'][$response]['result']);
        if (isset($currentQuestion['responses'][$response]['commentaire'])) {
            $this->info('commentaire : ' . $currentQuestion['responses'][$response]['commentaire']);
        }
        $this->info('');
        $this->info("Votre liste de questions-reponses : ");
        $this->info('------------------------------------');
        foreach ($allQuestionsReponses as $value) {
            $this->info($value);
        };



        return 0;
    }
}
