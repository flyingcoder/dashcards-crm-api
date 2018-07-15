@extends('layouts.buzzooka')

@section('title', 'Milestone Template')

@section('content')
   <milestone-template-tasks :id="<?php echo $id; ?>"/>
@endsection