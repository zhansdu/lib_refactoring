<?php


namespace App\Common\Helpers\Query;


use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use PDO;
use PDOStatement;

class OracleProcedure
{
    private $name;
    private $params;
    private $pdo;

    public function __construct(string $name, array $params, string $connName = '')
    {
        $this->name = $name;
        $this->params = $params;
        $this->pdo = DB::connection($connName)->getPdo();
    }

    public function call(): bool
    {
        $statement = $this->pdo->prepare($this->generate());

        $this->bindParams($statement);

        return $statement->execute();
    }

    #[Pure] public function generate(): string
    {
        $raw = "begin " . $this->name . "(";

        $i = 0;
        foreach ($this->params as $paramName => &$param) {
            $raw .= $paramName . " => :" . $paramName;
            $raw .= $i < sizeof($this->params) - 1 ? ", " : "";
            $i++;
        }

        $raw .= "); end;";
        return $raw;
    }

    public function bindParams(PDOStatement &$statement)
    {
        foreach ($this->params as $paramName => &$param) {
            $statement->bindParam(":{$paramName}", $param['value'], $param['type'] ?? PDO::PARAM_STR);
        }
    }

    public function getOutputParams(): array
    {
        return array_filter($this->params, function ($param, $paramName) {
            return isset($param['isOut']) && $param['isOut'] == true;
        }, ARRAY_FILTER_USE_BOTH);
    }
}
