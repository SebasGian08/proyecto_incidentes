@extends('auth.index')

@section('titulo')
<title>Registrar Movimiento</title>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.stock-bajo { color: red; font-weight: 600; }
.entrada { background-color: #d4edda; } 
.salida { background-color: #f8d7da; }
</style>
@endsection

@section('contenido')
<div class="content-wrapper">
    <section class="content-header d-flex justify-content-between align-items-center header-animado"
        style="padding: 15px 25px; background: linear-gradient(to right, #5864ff, #646eff); border-radius: 8px;">
        <h1 style="color: #fff;"><i class="fa fa-exchange"></i> Registrar Movimiento</h1>
    </section>

    <div class="content mt-3">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('auth.movimientos.store') }}" method="POST">
            @csrf
            <div class="form-section">
                <div class="row">
                    <div class="col-md-4">
                        <label>Producto</label>
                        <select name="id_producto" class="form-control select2" required>
                            <option value="">Seleccione...</option>
                            @foreach($productos as $prod)
                                <option value="{{ $prod->id_producto }}">{{ $prod->descripcion }} (Stock: {{ $prod->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Tipo</label>
                        <select name="tipo" class="form-control" required>
                            <option value="E">Entrada</option>
                            <option value="S">Salida</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Motivo</label>
                        <select name="id_tipo_movimiento" class="form-control" required>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id_tipo_movimiento }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Cantidad</label>
                        <input type="number" name="cantidad" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <label>Costo Unitario</label>
                        <input type="number" name="costo_unitario" class="form-control" min="0" step="0.01" required>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-light-custom btn-lg">
                    <i class="fa fa-save"></i> Registrar Movimiento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function(){
    $('.select2').select2({ width: '100%' });
});
</script>
@endsection
