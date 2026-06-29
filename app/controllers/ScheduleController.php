<?php

class ScheduleController extends Controller
{



    public function schedule()
    {
        $this->view("schedule/index");
    }

    public function scheduleEdit()
    {
        $this->view("schedule/edit");
    }

    
}