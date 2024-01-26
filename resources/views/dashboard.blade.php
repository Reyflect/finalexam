
<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Back-End Exam</title>
@vite('resources/css/app.css')
@vite('resources/js/app.js')
@php
use Illuminate\Support\Str;
@endphp

    </head>
<body class="hold-transition sidebar-mini">
    
    
    
<div class="wrapper">

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="/dashboard" class="nav-link">Home</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">

    <li class="nav-item">
        <form action="/logout" method="POST">
            @csrf
            <button class="btn btn-danger">LOGOUT</button>
        </form>
    </a>
    </li>
    </ul>
</nav>


<aside class="main-sidebar sidebar-dark-primary elevation-4">

<a href="index3.html" class="brand-link">
<img src=" https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
<span class="brand-text font-weight-light">Exam BE</span>
</a>

<div class="sidebar">

<div class="user-panel mt-3 pb-3 mb-3 d-flex">
<div class="image">
<img src=" https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
</div>
<div class="info">
<a href="#" class="d-block"> Welcome, {{ Auth::user()->username }}!</a>
</div>
</div>


<nav class="mt-2">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

    <li class="nav-item">
        <a href="/dashboard" class="nav-link">
        <i class="nav-icon fas fa-th"></i>
        <p>
        Dashboard
      
        </p>
        </a>
    </li>
<li class="nav-item">
    <a href="/createproduct" class="nav-link">
    <i class="nav-icon fas fa-th"></i>
    <p>
    Create
  
    </p>
    </a>
</li>
    
<li class="nav-item">
    <a href="/videos" class="nav-link">
    <i class="nav-icon fas fa-th"></i>
    <p>
        Videos 
  
    </p>
    </a>
    </li>
</ul>
</nav>

</div>

</aside>

<div class="content-wrapper">

<div class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">

<h1 class="m-0">
    @if(Request::is('dashboard'))
    Products Page
@elseif(Request::is('addproduct'))
    Add Product
@endif

</h1>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
    
    @if(Request::is('dashboard'))
    <a href="/createproduct" class="btn btn-primary">Create</a>
@endif
</ol>
</div>
</div>
</div>
</div>


<div class="content">
<div class="container-fluid" id="app">
    @if(Request::is('dashboard'))
            <product-list></product-list>
    @elseif(Request::is('createproduct'))
    <form action="/api/addnewproduct" method="POST"  enctype="multipart/form-data">
        @csrf
        <create-product></create-product>
    </form>   
    
    @elseif(Str::startsWith(Route::current()->uri(), 'editproduct/'))
    <form method="post"  enctype="multipart/form-data">
        @csrf
        @method('post')
        <edit-products :product-id="{{$product->id}}"></edit-products>

    </form>   
    @elseif(Str::startsWith(Route::current()->uri(), 'videos'))
        <h3>Videos Available:</h3>
        <my-video :initial-video="'{{$video}}'"></my-video>

    </div>

    </div>
    
    
    
@endif

</div>
</div>

</div>



<footer class="main-footer">

<div class="float-right d-none d-sm-inline">
Back End Exam
</div>

<strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
</footer>
</div>


</body>
</html>
