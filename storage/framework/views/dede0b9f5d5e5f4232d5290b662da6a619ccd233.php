<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">


        <title>DAD Exam</title>

        <link rel="stylesheet" href="<?php echo e(URL::asset('css/app.css')); ?>">

        <!-- Styles -->
        <style type="text/css">
            .nav-link {
                color: white;
            }
            .nav-link:hover {
                color: lightgray;
            }
            main > .container {
                padding: 40px 0 20px 0;
            }
        </style>
    </head>
    <body>
        <div id="app">
            <header>
                <div class="navbar navbar-dark bg-dark shadow-sm">
                    <div class="container d-flex justify-content-between">
                        <router-link class="navbar-brand d-flex align-items-center" to="/">
                            <img src="img/logo.svg" width="20" height="20" style="margin-right:20px"/>
                            <strong>Exam (DAD)</strong>
                        </router-link>
                        
                        <ul class="nav justify-content-end">
                            <li class="nav-item">
                                <router-link class="nav-link" to="#">Menu1</router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" to="#">Menu2</router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" to="#">Menu3</router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/login" class="btn btn-secondary" role="button">Login</router-link>
                            </li>

                        <li class="nav-item">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" tag="button">Name of User
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <router-link to="#" class="dropdown-item">Menu4</router-link>
                                    <router-link to="4" class="dropdown-item">Menu5</router-link>
                                    <a class="dropdown-item">Logout</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </header>

            <main role="main">
                <div class="container">
                    <router-view></router-view> 
                </div>
            </main>    
        </div>
        
        <script src="js/app.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    </body>
</html>
