<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Laravel + Vue 3</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="/">Laravel App</a>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          @guest
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">Register</a>
            </li>
          @else
            <li class="nav-item">
              <a class="nav-link" href="{{ route('todos.index') }}">My Todos</a>
            </li>
            <li class="nav-item">
              <span class="nav-link">Welcome, {{ Auth::user()->name }}</span>
            </li>
            <li class="nav-item">
              <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link nav-link">Logout</button>
              </form>
            </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>
  <div class="container mt-4">
    @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif
    <div id="app" class="container mt-4">
        <div class="jumbotron bg-light p-5 rounded-lg m-3">
            <h1 class="display-4">Welcome to the Todo App!</h1>
            <p class="lead">This application helps you manage your daily tasks efficiently. You can create, view, edit, and delete your todos, categorize them, and track their status.</p>
            <hr class="my-4">
            <p>Get started by logging in or registering a new account to organize your tasks.</p>
            <div class="d-flex justify-content-center">
                <a class="btn btn-primary btn-lg mx-2" href="{{ route('login') }}" role="button">Login</a>
                <a class="btn btn-secondary btn-lg mx-2" href="{{ route('register') }}" role="button">Register</a>
            </div>
        </div>
    </div>
  </div>
</body>
</html>
