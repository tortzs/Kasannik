<?php

class HomeController extends Controller
{
    public function index()
    {
        $this->view("home/index");
    }

    public function schedule()
    {
        $this->view("schedule/index");
    }

    public function scheduleEdit()
    {
        $this->view("schedule/edit");
    }

    public function userRegister()
    {
        $this->view("user/register");
    }
    public function userLogin()
    {
        $this->view("user/login");
    }
}