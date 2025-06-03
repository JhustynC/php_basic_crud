<?php
require_once __DIR__ . '/../config/database.php';

abstract class Model {
    protected $table;
    protected $primaryKey = 'id';
    protected $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data) {
        $stmt = $this->pdo->query("DESCRIBE {$this->table}");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
        // Eliminar id si está vacío o nulo (AUTO_INCREMENT)
        if (isset($data['id']) && ($data['id'] === '' || $data['id'] === null)) {
            unset($data['id']);
        }
    
        // Filtrar solo columnas válidas
        $filteredData = array_filter(
            $data,
            fn($key) => in_array($key, $columns),
            ARRAY_FILTER_USE_KEY
        );
    
        if (empty($filteredData)) {
            throw new Exception("No hay datos válidos para insertar.");
        }
    
        $columnList = implode(', ', array_keys($filteredData));
        $placeholders = implode(', ', array_fill(0, count($filteredData), '?'));
        $sql = "INSERT INTO {$this->table} ($columnList) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($filteredData));
        return $this->pdo->lastInsertId();
    }
    

    public function update($id, array $data) {
        $set = implode(', ', array_map(fn($key) => "$key = ?", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $set WHERE {$this->primaryKey} = ?";
        $stmt = $this->pdo->prepare($sql);
        $values = array_values($data);
        $values[] = $id;
        return $stmt->execute($values);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }
}
?>