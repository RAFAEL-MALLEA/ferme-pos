<?php

namespace App\Models;

use CodeIgniter\Model;

class NotaCreditoModel extends Model
{
    protected $table         = 'nota_credito';
    protected $primaryKey    = 'id_nota_credito';
    protected $allowedFields = ['venta_fk', 'usuario_fk', 'motivo', 'monto', 'fecha'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;
}
