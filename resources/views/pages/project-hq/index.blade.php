@extends('layouts.buzzooka')

@section('title', 'Project HQ')

@section('content')
<div id="app-with-routes">
  <project-hq asset="{{ asset("") }}" :project-id="{{ $project_id }}" />
</div>
@endsection