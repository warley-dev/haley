<?php
require './Core/global.php';
require './vendor/autoload.php';
use Core\SiteConfig;
use Core\App;
$site = new SiteConfig;
$app = new App;
$site->siteinfo();
// return die(header('Location: https://www.google.com'));

//Routers
$app->view("","./Controller/View/ControllerHome.php");
$app->view("filmes","./Controller/View/ControllerFilmes.php");
$app->view("series","./Controller/View/ControllerSeries.php");
$app->view("animes","./Controller/View/ControllerAnimes.php");
$app->view("dmca","./Controller/View/ControllerDmca.php");
$app->view("404","./Controller/View/Controller404.php");

//telas
$app->view("serie","./Controller/View/ControllerTelaserie.php");
$app->view("filme","./Controller/View/ControllerTelafilme.php");
$app->view("anime","./Controller/View/ControllerTelaanime.php");

//user
$app->view("login","./Controller/View/ControllerLogin.php");
$app->view("logoff","./Controller/usuario/ControllerLogoff.php");
$app->view("loginform","./Controller/usuario/ControllerUserLogin.php");

if(isuser()){
    $app->view("u/".$_SESSION['usuario'],"./Controller/usuario/ControllerUsuario.php");    
    $app->view("fav","./Controller/usuario/ControllerFavoritos.php");
    $app->view("usermenu","./Controller/usuario/ControllerMenu.php");
    $app->view("deletar","./Controller/usuario/ControllerDeletar.php");
    $app->view("conta","./Controller/usuario/ControllerConta.php");   
}

//ajax
$app->view("telaseries","./Controller/iframeseries.php");
$app->view("telafilmes","./Controller/iframefilme.php");
$app->view("telaanimes","./Controller/iframeanimes.php");
$app->view("autocomplete","./Controller/ControllerSearch.php");

//update e testes
$app->view("updatefilmes","./Controller/Update/FilmesUpdate.php");
$app->view("updateseries","./Controller/Update/SeriesUpdate.php");
$app->view("loaderio-0a68843c48a5d3f8b94b8936f3141d87","./Controller/teste.php");
// $app->view("updateanimes","./Controller/Update/AnimesUpdate.php");
// $app->view("teste","./Controller/teste.php");

if($app->url == false){
    include './Controller/View/Controller404.php';      
}
