<?php
declare(strict_types = 1);

namespace App\Model;

class TodosManager
{
    const STATUS_ALL = null;
    const STATUS_ACTIVE = 0;
    const STATUS_COMPLETED = 1;

    /** @var \PDO */
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchAll(int $status = null) : array
    {
        $sql = 'SELECT * FROM todos';

        if ($status !== null) {
            $sql .= " WHERE isDone = $status";
        }

        $sql .= ' ORDER BY timeCreated ASC';

        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_OBJ);
    }

    public function countActive() : int
    {
        $sql = 'SELECT COUNT(*)
FROM todos
WHERE isDone = ' . self::STATUS_ACTIVE . '
ORDER BY timeCreated ASC';

        return +$this->pdo->query($sql)->fetchColumn();
    }

    public function countCompleted() : int
    {
        $sql = 'SELECT COUNT(*) 
FROM todos
WHERE isDone = ' . self::STATUS_COMPLETED . '
ORDER BY timeCreated ASC';

        return +$this->pdo->query($sql)->fetchColumn();
    }

    public function remove(int $id)
    {
        $this->pdo->exec('DELETE FROM todos WHERE id = ' . $id);
    }

    public function clearCompleted()
    {
        $this->pdo->exec('DELETE FROM todos WHERE isDone = ' . self::STATUS_COMPLETED);
    }

    public function changeStatus(int $id, int $status)
    {
        $this->pdo->exec('UPDATE todos SET isDone = ' . $status . ' WHERE id = ' . $id);
    }

    public function changeValue(int $id, string $value)
    {
        $this->pdo->prepare('UPDATE todos SET value = ? WHERE id = ' . $id)->execute([$value]);
    }

    public function add(string $value)
    {
        $this->pdo->prepare('INSERT INTO todos (value, isDone, timeCreated) VALUES (?, 0, ?)')->execute([$value, time()]);
    }
}
