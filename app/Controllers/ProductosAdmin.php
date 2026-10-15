<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use App\Models\ProductoModel;
use App\Models\ProveedorModel;

/**
 * BUG ORIGINAL en logs:
 *   - "Too few arguments ... ProductosAdmin::eliminar(), 1 passed ... exactly 2 expected"
 *     -> eliminar() exigia 2 parametros obligatorios sin necesitarlos realmente.
 *   - "Call to undefined method ProductosAdminModel::eliminarProducto" -> ya existe
 *     en ProductoModel (ver Models/ProductoModel.php).
 *   - "Column 'fecha_vencimiento' cannot be null" / "fk_categoria" -> se valida
 *     la categoria ANTES de intentar el insert, y fecha_vencimiento es opcional.
 *   - "The original file is not a valid file." (44x) -> subida de imagen sin
 *     validar si realmente vino un archivo; aqui se revisa con isValid().
 */
class ProductosAdmin extends BaseController
{
    public function index()
    {
        $model = new ProductoModel();

        return view('productos/index', [
            'productos' => $model->select('producto.*, categoria.nombre as categoria_nombre')
                ->join('categoria', 'categoria.id_categoria = producto.categoria')
                ->orderBy('producto.nombre', 'ASC')
                ->findAll(),
        ]);
    }

    public function nuevo()
    {
        return view('productos/form', [
            'producto'    => null,
            'categorias'  => (new CategoriaModel())->activas(),
            'proveedores' => (new ProveedorModel())->activos(),
        ]);
    }

    public function guardar()
    {
        $rules = [
            'nombre'       => 'required|max_length[150]',
            'categoria'    => 'required|is_natural_no_zero',
            'precio_venta' => 'required|decimal',
            'stock'        => 'permit_empty|is_natural',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $datos = [
            'nombre'            => $this->request->getPost('nombre'),
            'descripcion'       => $this->request->getPost('descripcion'),
            'categoria'         => $this->request->getPost('categoria'),
            'proveedor_fk'      => $this->request->getPost('proveedor_fk') ?: null,
            'precio_compra'     => $this->request->getPost('precio_compra') ?: 0,
            'precio_venta'      => $this->request->getPost('precio_venta'),
            'stock'             => $this->request->getPost('stock') ?: 0,
            'stock_minimo'      => $this->request->getPost('stock_minimo') ?: 5,
            'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento') ?: null,
            'estado'            => 1,
        ];

        $imagen = $this->request->getFile('imagen');
        if ($imagen && $imagen->isValid() && ! $imagen->hasMoved()) {
            $nuevoNombre = $imagen->getRandomName();
            $imagen->move(WRITEPATH . 'img/productos', $nuevoNombre);
            $datos['imagen'] = $nuevoNombre;
        }

        (new ProductoModel())->insert($datos);

        return redirect()->to('/productos-admin')->with('mensaje', 'Producto creado correctamente.');
    }

    public function editar(int $id)
    {
        $producto = (new ProductoModel())->find($id);

        if (! $producto) {
            return redirect()->to('/productos-admin')->with('error', 'Producto no encontrado.');
        }

        return view('productos/form', [
            'producto'    => $producto,
            'categorias'  => (new CategoriaModel())->activas(),
            'proveedores' => (new ProveedorModel())->activos(),
        ]);
    }

    public function actualizar(int $id)
    {
        $model    = new ProductoModel();
        $producto = $model->find($id);

        if (! $producto) {
            return redirect()->to('/productos-admin')->with('error', 'Producto no encontrado.');
        }

        $datos = [
            'nombre'            => $this->request->getPost('nombre'),
            'descripcion'       => $this->request->getPost('descripcion'),
            'categoria'         => $this->request->getPost('categoria'),
            'proveedor_fk'      => $this->request->getPost('proveedor_fk') ?: null,
            'precio_compra'     => $this->request->getPost('precio_compra') ?: 0,
            'precio_venta'      => $this->request->getPost('precio_venta'),
            'stock_minimo'      => $this->request->getPost('stock_minimo') ?: 5,
            'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento') ?: null,
        ];

        $imagen = $this->request->getFile('imagen');
        if ($imagen && $imagen->isValid() && ! $imagen->hasMoved()) {
            $nuevoNombre = $imagen->getRandomName();
            $imagen->move(WRITEPATH . 'img/productos', $nuevoNombre);
            $datos['imagen'] = $nuevoNombre;
        }

        $model->update($id, $datos);

        return redirect()->to('/productos-admin')->with('mensaje', 'Producto actualizado.');
    }

    /**
     * Firma simple y consistente con la ruta: un solo parametro obligatorio,
     * que la ruta SIEMPRE entrega. Baja logica, nunca DELETE fisico.
     */
    public function eliminar(int $id)
    {
        (new ProductoModel())->eliminarProducto($id);

        return redirect()->to('/productos-admin')->with('mensaje', 'Producto eliminado.');
    }
}
