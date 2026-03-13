@extends('auth.index')

@section('titulo')
<title>Inicio</title>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('auth/css/inicio/core.css') }}">
{{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css"> --}}
@endsection
@section('contenido')
<div class="content-wrapper">

    <section class="content-header d-flex justify-content-between align-items-center"
        style="padding: 15px 25px; border-bottom: 2px solid #e0e0e0; background: linear-gradient(to right, #5864ff, #646eff); border-radius: 8px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #fff; margin: 0; font-size: 1.8rem;">
            <i class="fa fa-home" style="margin-right: 8px;"></i> Inicio
        </h1>
    </section>

    <div class="video-container" style="width: 100%; height: calc(100vh - 70px);">
        <iframe src="https://www.youtube.com/embed/0Pblswuf238?list=RDMMej1Ixon_aK4" title="Video de YouTube"
            allowfullscreen style="border: none; width: 100%; height: 100%; display: block;">
        </iframe>
    </div>

</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('auth/js/inicio/index.js') }}"></script>
@endsection