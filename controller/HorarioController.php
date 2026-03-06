<?php
require_once __DIR__ . "/../model/HorarioModel.php";
require_once __DIR__ . "/../model/Clase.php";

class HorarioController {

    private $model;
    private $claseModel;

    public function __construct() {
        $this->model = new HorarioModel();

        if (class_exists('Clase')) {
            $this->claseModel = new Clase($this->model->getConnection());
        }
    }

   
    public function listar() {
        return $this->model->listarTodos();
    }

    public function listarClases() {

        if ($this->claseModel && method_exists($this->claseModel, 'listarTodos')) {
            return $this->claseModel->listarTodos();
        }

        return $this->model->obtenerClasesSimple();
    }

    public function agregar($post) {

        if (
            empty($post['id_clase']) ||
            empty($post['dia_semana']) ||
            empty($post['hora_inicio']) ||
            empty($post['hora_fin'])
        ) {
            return [
                'success' => false,
                'message' => 'Todos los campos son obligatorios'
            ];
        }

        if (strtotime($post['hora_inicio']) >= strtotime($post['hora_fin'])) {
            return [
                'success' => false,
                'message' => 'La hora de inicio debe ser menor a la hora de fin'
            ];
        }

        $result = $this->model->agregar($post);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Horario agregado correctamente'
            ];
        }

        return [
            'success' => false,
            'message' => 'Error al guardar horario'
        ];
    }

    
    public function actualizar($post) {

        if (empty($post['id_horario'])) {
            return [
                'success' => false,
                'message' => 'ID de horario inválido'
            ];
        }

        if (strtotime($post['hora_inicio']) >= strtotime($post['hora_fin'])) {
            return [
                'success' => false,
                'message' => 'La hora de inicio debe ser menor a la hora de fin'
            ];
        }

        $result = $this->model->actualizar($post);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Horario actualizado correctamente'
            ];
        }

        return [
            'success' => false,
            'message' => 'Error al actualizar horario'
        ];
    }

    
    public function eliminar($id) {

        if (empty($id)) {
            return [
                'success' => false,
                'message' => 'ID no válido'
            ];
        }

        $result = $this->model->eliminar($id);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Horario eliminado correctamente'
            ];
        }

        return [
            'success' => false,
            'message' => 'Error al eliminar horario'
        ];
    }

}