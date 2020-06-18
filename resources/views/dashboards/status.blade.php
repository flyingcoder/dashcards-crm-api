@extends('layouts.laravel')

@section('content')
   <section>
	   	<div class="title m-b-md">
	        Buzzooka
	    </div>
   		<h1>Server Status : UP</h1>
   		<h5>Server Time : {{$utc}}</h5>
   		<h5>Manila Time : {{$manila}}</h5>
   		<h5>Toronto Time : {{$toronto}}</h5>

   </section>
@endsection