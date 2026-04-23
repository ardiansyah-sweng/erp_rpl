<?php

namespace App\DataGeneration;

use Faker\Factory;
use Faker\Provider\Base;

class SkripsiDatasetProvider extends Base
{
    private array $companyList = [];
    private array $companySectors = [];
    private array $companySubsectors = [];
    private array $foodsList = [];
    private array $brandsList = [];
    private array $productsList = [];
    private array $categoryList = [];

    private array $companyFormats = [
        '{{company}} {{company}}',
        '{{company}} {{company}} {{company}}',
        '{{company}} {{company}} {{company}} {{company}}',
    ];

    private array $companySuffixPrefixFormat = [
        '{{prefix}} {{fullCompany}} {{suffix}}',
        '{{prefix}} {{fullCompany}}',
        '{{fullCompany}} {{suffix}}',
    ];

    private array $fmtCompanySuffixPrefix = [
        '{prefix} {fullCompany} {suffix}',
        '{prefix} {fullCompany}',
        '{fullCompany} {suffix}',
    ];

    private array $fmtBrandProduct = [
        '{{product}}',
        '{{product}} {{product}}',
        '{{product}} {{product}} {{product}}',
        '{{product}} {{product}} {{gram}}g',
        '{{product}} {{gram}}g',
    ];

    private array $prefixes = ['PT', 'CV', 'UD', 'PD', 'Perum'];
    private array $suffixes = ['Tbk', '(Persero) Tbk'];

    public function __construct($generator)
    {
        parent::__construct($generator);
        $this->loadData();
    }

    private function loadData(): void
    {
        $file = fopen(__DIR__ . '/datasets.csv', 'r');
        if (!$file) {
            throw new \RuntimeException("Unable to open " . __DIR__ . "/datasets.csv");
        }

        // Skip first 4 rows
        for ($i = 0; $i < 4; $i++) {
            fgetcsv($file);
        }

        while (($row = fgetcsv($file)) !== false) {
            switch ($row[0]) {
                case '#COMPANY_NAMES':
                    $this->companyList = array_slice($row, 1);
                    break;
                case '#SECTORS':
                    $this->companySectors = array_slice($row, 1);
                    break;
                case '#SUBSECTORS':
                    $this->companySubsectors = array_slice($row, 1);
                    break;
                case '#FOODS':
                    $this->foodsList = array_slice($row, 1);
                    break;
                case '#BRANDS':
                    $this->brandsList = array_slice($row, 1);
                    break;
                case '#PRODUCTS':
                    $this->productsList = array_slice($row, 1);
                    break;
                case "#CATEGORY":
                    $this->categoryList = array_slice($row, 1);
                    break;
            }
        }

        // remove something like \" from the strings
        $this->companyList = array_map(fn($company) => str_replace('"', '', $company), $this->companyList);
        $this->companySectors = array_map(fn($sector) => str_replace('"', '', $sector), $this->companySectors);
        $this->companySubsectors = array_map(fn($subsector) => str_replace('"', '', $subsector), $this->companySubsectors);
        $this->foodsList = array_map(fn($food) => str_replace('"', '', $food), $this->foodsList);
        $this->brandsList = array_map(fn($brand) => str_replace('"', '', $brand), $this->brandsList);
        $this->productsList = array_map(fn($product) => str_replace('"', '', $product), $this->productsList);
        $this->categoryList = array_map(fn($category) => str_replace('"', '', $category), $this->categoryList);

        fclose($file);
    }

    public function gram(): int
    {
        return $this->generator->numberBetween(100, 1000);
    }

    public function brand(): string
    {
        return $this->generator->randomElement($this->brandsList);
    }

    public function product(): string
    {
        return $this->generator->randomElement($this->productsList);
    }

    public function fullProduct(): string
    {
        $format = $this->generator->randomElement($this->fmtBrandProduct);
        return $this->generator->parse($format);
    }

    public function company(): string
    {
        return $this->generator->randomElement($this->companyList);
    }

    public function companySector(): string
    {
        return $this->generator->randomElement($this->companySectors);
    }

    public function asssproductCategory(): string
    {
        return $this->generator->randomElement($this->categoryList);
    }

    public function companySubsector(): string
    {
        return $this->generator->randomElement($this->companySubsectors);
    }

    public function food(): string
    {
        return $this->generator->randomElement($this->foodsList);
    }

    public function fullCompany(): string
    {
        $format = $this->generator->randomElement($this->companyFormats);
        return $this->generator->parse($format);
    }

    public function prefix(): string
    {
        return $this->generator->randomElement($this->prefixes);
    }

    public function suffix(): string
    {
        return $this->generator->randomElement($this->suffixes);
    }

    public function companySuffixPrefix(): string
    {
        $format = $this->generator->randomElement($this->companySuffixPrefixFormat);
        return $this->generator->parse($format);
    }

    public function phoneNumber(): string
    {
        $format = "+62 8##-###-####";
        return $this->generator->numerify($format);
    }

    public function companySuffixPrefixFromName(string $name): string
    {
        $preSuffixFormat = $this->generator->randomElement($this->fmtCompanySuffixPrefix);
        $prefix = $this->prefix();
        $suffix = $this->suffix();
        return str_replace(['{prefix}', '{fullCompany}', '{suffix}'], [$prefix, $name, $suffix], $preSuffixFormat);
    }

    public function getCompanyCode(string $name): string
    {
        return strtoupper(implode('', array_map(fn($word) => $word[0], explode(' ', $name))));
    }
}
