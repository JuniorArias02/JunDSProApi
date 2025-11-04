<?php
require_once __DIR__ . '/../db/conexion.php';

class DimensionesRepository
{
	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function obtenerTodosLosCriterios()
	{
		$sqlDim = "SELECT * FROM dimensiones ORDER BY orden ASC";
		$stmtDim = $this->pdo->query($sqlDim);
		$dimensiones = $stmtDim->fetchAll(PDO::FETCH_ASSOC);

		foreach ($dimensiones as &$dim) {
			$sqlCri = "SELECT id, descripcion, orden
                       FROM criterios
                       WHERE dimension_id = ?
                       ORDER BY orden ASC";
			$stmtCri = $this->pdo->prepare($sqlCri);
			$stmtCri->execute([$dim['id']]);
			$dim['criterios'] = $stmtCri->fetchAll(PDO::FETCH_ASSOC);
		}

		return $dimensiones;
	}

	public function getAllConCriterios()
	{
		$sql = "
            SELECT 
                d.id AS dimension_id,
                d.nombre AS dimension_nombre,
                d.orden AS dimension_orden,
                c.id AS criterio_id,
                c.descripcion AS criterio_descripcion,
                c.orden AS criterio_orden
            FROM dimensiones d
            LEFT JOIN criterios c ON d.id = c.dimension_id
            ORDER BY d.orden ASC, c.orden ASC
        ";

		$stmt = $this->pdo->query($sql);
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// 🔹 Armamos el array anidado
		$result = [];
		foreach ($rows as $row) {
			$dimId = $row['dimension_id'];

			// Si la dimensión no existe en el array, la creamos
			if (!isset($result[$dimId])) {
				$result[$dimId] = [
					'id' => $row['dimension_id'],
					'nombre' => $row['dimension_nombre'],
					'orden' => $row['dimension_orden'],
					'criterios' => []
				];
			}

			// Si tiene criterio, lo agregamos
			if ($row['criterio_id']) {
				$result[$dimId]['criterios'][] = [
					'id' => $row['criterio_id'],
					'descripcion' => $row['criterio_descripcion'],
					'orden' => $row['criterio_orden']
				];
			}
		}

		return array_values($result);
	}
}
