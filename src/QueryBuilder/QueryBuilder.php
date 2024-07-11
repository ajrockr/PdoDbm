<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\QueryBuilder;

use Arizzo\PdoDbm\Database\QueryResult;
use Arizzo\PdoDbm\Database\Exceptions\DatabaseException;
use Arizzo\PdoDbm\QueryBuilder\Parts\Delete;
use Arizzo\PdoDbm\QueryBuilder\Parts\From;
use Arizzo\PdoDbm\QueryBuilder\Parts\GroupBy;
use Arizzo\PdoDbm\QueryBuilder\Parts\Insert;
use Arizzo\PdoDbm\QueryBuilder\Parts\Limit;
use Arizzo\PdoDbm\QueryBuilder\Parts\Offset;
use Arizzo\PdoDbm\QueryBuilder\Parts\OrderBy;
use Arizzo\PdoDbm\QueryBuilder\Parts\QueryPartInterface;
use Arizzo\PdoDbm\QueryBuilder\Parts\Select;
use Arizzo\PdoDbm\QueryBuilder\Parts\Update;
use Arizzo\PdoDbm\QueryBuilder\Parts\Where;
use PDO;

class QueryBuilder
{
    protected array $queryParts = [];
    protected ?QueryPartInterface $currentQueryPart = null;

    protected PDO $pdo;
    protected string $sql;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function query(string $sql): self
    {
        $this->sql = $sql;
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

    public function getResult(): QueryResult
    {
        return new QueryResult($this->pdo, $this->sql); // Figure out what to do with params
    }

    public function select(string|array $columns = '*'): self
    {
        $this->currentQueryPart = is_array($columns)
            ? new Select($columns)
            : new Select([$columns]);

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

        return $this;
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

        return $this;
    }
}