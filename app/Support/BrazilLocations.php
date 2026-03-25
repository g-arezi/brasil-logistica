<?php

declare(strict_types=1);

namespace App\Support;

final class BrazilLocations
{
    /**
     * @var array<string, list<string>>
     */
    private const STATES_TO_CITIES = [
        'AC' => ['Rio Branco', 'Cruzeiro do Sul', 'Sena Madureira'],
        'AL' => ['Maceio', 'Arapiraca', 'Palmeira dos Indios'],
        'AM' => ['Manaus', 'Parintins', 'Itacoatiara'],
        'AP' => ['Macapa', 'Santana', 'Laranjal do Jari'],
        'BA' => ['Salvador', 'Feira de Santana', 'Vitoria da Conquista'],
        'CE' => ['Fortaleza', 'Juazeiro do Norte', 'Sobral'],
        'DF' => ['Brasilia', 'Taguatinga', 'Ceilandia'],
        'ES' => ['Vitoria', 'Vila Velha', 'Serra'],
        'GO' => ['Goiania', 'Anapolis', 'Rio Verde'],
        'MA' => ['Sao Luis', 'Imperatriz', 'Caxias'],
        'MG' => ['Belo Horizonte', 'Uberlandia', 'Contagem'],
        'MS' => ['Campo Grande', 'Dourados', 'Tres Lagoas'],
        'MT' => ['Cuiaba', 'Rondonopolis', 'Sinop'],
        'PA' => ['Belem', 'Ananindeua', 'Santarem'],
        'PB' => ['Joao Pessoa', 'Campina Grande', 'Patos'],
        'PE' => ['Recife', 'Jaboatao dos Guararapes', 'Caruaru'],
        'PI' => ['Teresina', 'Parnaiba', 'Picos'],
        'PR' => ['Curitiba', 'Londrina', 'Maringa'],
        'RJ' => ['Rio de Janeiro', 'Niteroi', 'Campos dos Goytacazes'],
        'RN' => ['Natal', 'Mossoro', 'Parnamirim'],
        'RO' => ['Porto Velho', 'Ji-Parana', 'Ariquemes'],
        'RR' => ['Boa Vista', 'Rorainopolis', 'Caracarai'],
        'RS' => ['Porto Alegre', 'Caxias do Sul', 'Pelotas'],
        'SP' => ['Sao Paulo', 'Campinas', 'Santos', 'Ribeirao Preto'],
        'SC' => ['Florianopolis', 'Joinville', 'Blumenau'],
        'SE' => ['Aracaju', 'Nossa Senhora do Socorro', 'Itabaiana'],
        'TO' => ['Palmas', 'Araguaina', 'Gurupi'],
    ];

    /**
     * @return list<string>
     */
    public static function states(): array
    {
        return array_keys(self::STATES_TO_CITIES);
    }

    /**
     * @return list<string>
     */
    public static function citiesByState(?string $state): array
    {
        if ($state === null || $state === '') {
            return [];
        }

        return self::STATES_TO_CITIES[strtoupper($state)] ?? [];
    }

    /**
     * @return array<string, list<string>>
     */
    public static function all(): array
    {
        return self::STATES_TO_CITIES;
    }
}
