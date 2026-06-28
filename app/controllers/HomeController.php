<?php

class HomeController extends Controller
{

    public function index()
    {
        $this->view("home/index");
    }

    public function dashboard()
    {
        $this->view("home/dashboard");
    }
}