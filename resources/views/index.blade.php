<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title>Virtual Wallet</title>

        <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">

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
                            <strong>Virtual Wallet</strong>
                        </router-link>
                        
                        <ul class="nav justify-content-end">
                            <li class="nav-item" v-if="this.$store.state.user != null" >
                            <div v-if="this.$store.state.user.type === 'o'">
                                <router-link class="nav-link" to="/registerIncomeMovement" >Register Movement</router-link>
                            </div> 
                            </li>

                            <li class="nav-item" v-if="this.$store.state.user != null">
                                <div v-if="this.$store.state.user.type === 'u'">
                                    <router-link class="nav-link" to="/myWallet">My Wallet</router-link>
                                </div>
                                
                            </li>
                            <li class="nav-item" v-if="this.$store.state.user != null">
                                <div v-if="this.$store.state.user.type === 'u'">
                                    <router-link class="nav-link" to="/registerExpense">Register Expense</router-link>
                                </div>
                                
                            </li>
                            <li class="nav-item" v-if="this.$store.state.user != null">
                                <div v-if="this.$store.state.user.type === 'u'">
                                    <router-link class="nav-link" to="/userStatistics">Statistics</router-link>
                                </div>
                            </li>
                            <li class="nav-item" v-if="this.$store.state.user != null">
                                <div v-if="this.$store.state.user.type === 'a'">
                                    <router-link class="nav-link" to="/userList">User List</router-link>
                                </div>
                            </li>
                            <li class="nav-item" v-if="this.$store.state.user != null">
                                <div v-if="this.$store.state.user.type === 'a'">
                                    <router-link class="nav-link" to="/registerAdminOrOperator">Register Admin/Operator</router-link>
                                </div>
                            </li>
                            <li class="nav-item" v-if="this.$store.state.user != null">
                                <div v-if="this.$store.state.user.type === 'a'">
                                    <router-link class="nav-link" to="/statistics">Statistics</router-link>
                                </div>
                            </li>
                            
                            <li class="nav-item" >
                                <router-link to="/login" class="btn btn-secondary" role="button" v-show="!this.$store.state.user">Login</router-link>
                            </li>
                            <li class="nav-item" >
                                <router-link to="/registerUser" class="btn btn-secondary" role="button" v-show="!this.$store.state.user">Register</router-link>
                            </li>
                            

                        <li class="nav-item" v-show="this.$store.state.user">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" tag="button">
                                @{{this.$store.state.user != null ? this.$store.state.user.name : " - None - " }}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <router-link to="/changePassword" class="dropdown-item">Change Password</router-link>
                                    <router-link to="/editUser" class="dropdown-item">Edit Profile</router-link>
                                    <router-link to="/logout" class="dropdown-item">Logout</router-link>
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
