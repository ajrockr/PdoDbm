<?php declare(strict_types=1);

namespace Ajrockr\PdoDbm\QueryBuilder;

use Ajrockr\PdoDbm\Database\DatabaseConnection;
use Ajrockr\PdoDbm\Database\Result\ExecutionResult;
use Ajrockr\PdoDbm\Database\Result\QueryResult;
use Ajrockr\PdoDbm\Database\Exceptions\DatabaseException;
use Ajrockr\PdoDbm\QueryBuilder\Parts\Delete;
use Ajrockr\PdoDbm\QueryBuilder\Parts\From;
use Ajrockr\PdoDbm\QueryBuilder\Parts\GroupBy;
use Ajrockr\PdoDbm\QueryBuilder\Parts\Insert;
use Ajrockr\PdoDbm\QueryBuilder\Parts\Limit;
use Ajrockr\PdoDbm\QueryBuilder\Parts\Offset;
use Ajrockr\PdoDbm\QueryBuilder\Parts\OrderBy;
use Ajrockr\PdoDbm\QueryBuilder\Parts\QueryPartInterface;
use Ajrockr\PdoDbm\QueryBuilder\Parts\Select;
use Ajrockr\PdoDbm\QueryBuilder\Parts\Update;
use Ajrockr\PdoDbm\QueryBuilder\Parts\Where;

class QueryBuilder
{
    protected array $queryParts = [];
    protected ?QueryPartInterface $currentQueryPart = null;

    protected DatabaseConnection $connection;
    protected string $sql;

    protected array $parameters;

    public function __construct(?DatabaseConnection $connection)
    {
        if ($connection === null) {
            throw new DatabaseException("Database connection does not exist.");
        }

        $this->connection = $connection;
    }

    public function query(string $sql): self
    {
        $this->sql = $sql;
        return $this;
    }

    public function execute(): ExecutionResult
    {
        if (!$this->currentQueryPart instanceof Insert
            && !$this->currentQueryPart instanceof Update
            && !$this->currentQueryPart instanceof Delete) {
            throw new DatabaseException('Can only call "execute" on a "INSERT, UPDATE, DELETE" method');
        }

        $this->getQuery();
        $statement = $this->connection->getConnection()->getPDO()->exec($this->sql);
        return new ExecutionResult($statement, $this->connection->getConnection()->getPDO()->lastInsertId());
    }

    public function getSql(): string
    {
        return $this->sql;
    }

    public function getResult(): QueryResult
    {
        $statement = $this->connection->getConnection()->getPDO()->query($this->sql);
        return new QueryResult($statement); // Figure out what to do with params
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