@extends('layouts.buzzooka')

@section('title', 'Milestones')

@section('content')
   <milestone-tasks :id="<?php echo $id; ?>"/>
@endsection