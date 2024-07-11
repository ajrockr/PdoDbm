<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Query;

use Arizzo\PdoDbm\Database\QueryResult;
use Arizzo\PdoDbm\Exceptions\DatabaseException;
use Arizzo\PdoDbm\Query\Parts\Delete;
use Arizzo\PdoDbm\Query\Parts\From;
use Arizzo\PdoDbm\Query\Parts\GroupBy;
use Arizzo\PdoDbm\Query\Parts\Insert;
use Arizzo\PdoDbm\Query\Parts\Limit;
use Arizzo\PdoDbm\Query\Parts\Offset;
use Arizzo\PdoDbm\Query\Parts\OrderBy;
use Arizzo\PdoDbm\Query\Parts\QueryPartInterface;
use Arizzo\PdoDbm\Query\Parts\Select;
use Arizzo\PdoDbm\Query\Parts\Update;
use Arizzo\PdoDbm\Query\Parts\Where;
use PDO;

class QueryBuilder
{
    protected array $queryParts = [];
    protected ?QueryPartInterface $currentQueryPart = null;

    protected PDO $pdo;
    protected string $sql;
    protected array $params = [];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function query(string $sql): self
    {
        $this->sql = $sql;
        return $this;
    }

    public function bind($param, $value): self
    {
        $this->params[$param] = $value;
        return $this;
    }

    public function execute(): QueryResult
    {
        if (!$this->currentQueryPart instanceof Insert
            && !$this->currentQueryPart instanceof Update
            && !$this->currentQueryPart instanceof Delete) {
            throw new DatabaseException('Can only call "execute" on a "INSERT, UPDATE, DELETE" method');
        }

        return new QueryResult($this->pdo, $this->sql);
    }

    public function getSql(): string
    {
        return $this->sql;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getResult(): QueryResult
    {
        return new QueryResult($this->pdo, $this->sql, $this->params);
    }

    public function select(array $columns = ['*']): self
    {
        $this->currentQueryPart = new Select($columns);
        $this->queryParts[] = $this->currentQueryPart;
        return $this;
    }

    public function from(string $table): self
    {
        $this->queryParts['from'] = new From($table);
        return $this;
    }

    public function where(array $conditions): self
    {
        $this->queryParts['where'] = new Where($conditions);
        return $this;
    }

    public function orderBy(array $columns): self
    {
        $this->queryParts['orderBy'] = new OrderBy($columns);
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->queryParts['limit'] = new Limit($limit);
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->queryParts['offset'] = new Offset($offset);
        return $this;
    }

    public function groupBy(array $columns): self
    {
        $this->queryParts['groupBy'] = new GroupBy($columns);
        return $this;
    }

    public function update(string $table): self
    {
        $this->currentQueryPart = new Update($table);
        $this->queryParts[] = $this->currentQueryPart;
        return $this;
    }

    public function insert(string $table): self
    {
        $this->currentQueryPart = new Insert($table);
        $this->queryParts[] = $this->currentQueryPart;
        return $this;
    }

    public function columns(array $columns): self
    {
        if ($this->currentQueryPart instanceof Insert) {
            $this->currentQueryPart->columns($columns);
        }
    }

    public function values(array $values): self {
        if ($this->currentQueryPart instanceof Insert) {
            $this->currentQueryPart->values($values);
        }
        return $this;
    }

    public function set(array $set): self {
        if ($this->currentQueryPart instanceof Update) {
            $this->currentQueryPart->set($set);
        }
        return $this;
    }

    public function delete(string $table): self {
        $this->currentQueryPart = new Delete($table);
        $this->queryParts[] = $this->currentQueryPart;
        return $this;
    }

    public function getQuery(): self
    {
        $sql = [];

        foreach ($this->queryParts as $part) {
            if ($part instanceof QueryPartInterface) {
                $sql[] = $part->getSql();
            }
        }

        $this->sql = implode(' ', $sql);
    }
}