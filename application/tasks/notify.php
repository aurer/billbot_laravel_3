<?php

class Notify_Task{

    private $from           = "Billbot <reminders@billbot.aurer.co.uk>";
    private $users          = array();
    private $emails         = array();

    public function run($arguments)
    {
        echo "Checking for bills...\n";
        $emails = $this->build_emails();
        echo "Found " . count($this->users) . " users.\n";
        echo "Found " . count($this->emails) . " reminders to send.\n";
        
    	// Send an email if there are any availiable bills for this user
        if( count($this->emails) > 0 ){
            
                foreach($this->emails as $email){
                    $headers  = "From: " . $this->from . "\r\n"; 
                    $headers .= "Content-type: text/html\r\n";
                    $h1 = "Just a little reminder";
                    $h2 = "Here are you upcoming bills";
                    $message = View::make('emails.reminder')->with( array('message'=>$email['message'], 'h1'=>$h1, 'h2'=>$h2));
                    mail($email['to'], $email['subject'], $message, $headers);
                }
                echo "Sent " . count($this->emails) . " " . Str::plural('notification email', count($this->emails)) . ".\n";
        }
    }

    public function preview()
    {
        echo "Checking for bills...\n";
        $emails = $this->build_emails();
        echo "Found " . count($this->users) . " users.\n";
        echo "Found " . count($this->emails) . " bills to send.\n";
        foreach ($this->emails as $mail) {
            echo "------------------------------------\n\n";
            echo "To: " . $mail['to'] . "\n";
            echo "Subject: " . $mail['subject'] . "\n";
            echo "Message: " . $mail['message'] . "\n";
        }
    }

    private function build_emails()
    {
        // Loop over the users
        $this->users = DB::table('users')->get();
        foreach ($this->users as $user) {

            // Find available bills for the user
            $bills = DB::table('bills')
            ->where_user_id($user->id)
            ->where_send_reminder(true)
            ->where(function($group1){
                $group1->where(function($query){
                    $query->where('recurrence', '=', 'weekly');
                    $query->where(DB::raw('(renews_on - send_reminder)'), '=', date('N'));
                })->or_where(function($query){
                    $query->where('recurrence', '!=', 'weekly');
                    $query->where(DB::raw('(renews_on - send_reminder) '), '=', date('z')+1); // Add 1 to date to compensate for 0 based day of year in PHP
                });
            })
            ->get();

            // Build the email
            $name = $user->forename ? $user->forename." ".$user->surname : $user->username;
            $to = "{$name} <{$user->email}>";
            $subject = "Dotification from Dibbs";
             $message = "Hi there,\n\n";
            $message .= "It looks like you have " . count($bills) . " " . Str::plural('bill', count($bills)) . " coming up:\n\n";
            
            // Loop over relevant bills and build a list
            foreach ($bills as $bill) {
                $message .= "$bill->title \n";
                for( $i=0; $i < strlen($bill->title); $i++ ){
                    $message .= "-";
                } 
                $message .= "\n";
                $message .= "Recurrence:   " . $bill->recurrence . "\n";
                $message .= "Renews on: " . $bill->renews_on . "\n";
                if( $bill->comments ) $message .=   "Comments:     $bill->comments\n\n";
            }

            if( count($bills) > 0 ){
                array_push($this->emails, array(
                    'to'       => $to,
                    'subject'  => $subject,
                    'message'  => $message,
                ));
            }
        }
        return $emails;
    }
}