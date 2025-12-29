@extends('layouts.app')

@section('content')
<div style="max-width:600px;margin:2rem auto;">
  <h2>Generar reporte</h2>
  <form method="POST" action="{{ route('report.generate') }}">
    @csrf
    <label>Formato:</label>
    <select name="format">
      <option value="pdf">PDF</option>
      <option value="xls">XLS</option>
    </select>
    <button type="submit">Generar</button>
  </form>
</div>
@endsection
