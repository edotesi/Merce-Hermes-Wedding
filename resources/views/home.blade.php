@extends('layouts.app')

@section('content')
<div class="position-relative vh-100">
   <video autoplay muted loop class="position-absolute w-100 h-100 object-fit-cover">
       <source src="{{ asset('video/background.mp4') }}" type="video/mp4">
   </video>

   <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center text-center text-white" style="background: rgba(0,0,0,0.5);">
       <div>
           <h1 class="display-1">Mercè & Hermes</h1>
           <p class="display-4">14/06/2025</p>
       </div>
   </div>
</div>
