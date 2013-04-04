<?php

class Notify_Task{

    private $from           = "Billbot <reminders@billbot.aurer.co.uk>";
    private $users          = array();
    private $emails         = array();

    public function run($arguments)
    {
        echo "Checking for bills...\n";
        $this->get_users_with_bills();
        echo "Found " . count($this->users) . " users.\n";
        echo "Found " . count($this->emails) . " reminders to send.\n";
        
    	// Send an email if there are any availiable bills for this user
        if( count($this->emails) > 0 ){            
            foreach($this->emails as $email){
                $headers  = "From: " . $this->from . "\r\n"; 
                $headers .= "Content-type: text/html\r\n";
                $h1 = "Just a little reminder";
                $h2 = "Here are you upcoming bills";
                $to = "{$email->forename} {$email->surname} <{$email->email}>";
                $subject = "Notification from Billbot";
                $message = View::make('emails.reminder')->with( array('user'=>$email));
                $send = mail($to, $subject, $message, $headers);
                if( $send ){
                    echo "Sent " . count($this->emails) . " " . Str::plural('notification email', count($this->emails)) . " to " . $to . ".\n";
                } else {
                    echo "Failed to send message to " . $to;
                }
            }
        }
    }

    public function preview()
    {
        echo "Checking for bills...\n";
        $emails = $this->get_users_with_bills();
        echo "Found " . count($this->users) . " users.\n";
        echo "Found " . count($this->emails) . " notfications to send.\n";
        foreach ($this->emails as $mail) {
            echo "------------------------------------\n\n";
            echo "To: " . "{$mail->forename} {$mail->surname} <{$mail->email}>" . "\n";
            echo "Subject: " . "Notification from Billbot" . "\n";
            $message = $this->generate_preview_message($mail->bills);
            echo "Message: " . $message . "\n";
        }
    }

    private function generate_preview_message($bills)
    {
        $message = "\n\n";
        foreach ($bills as $bill) {
            $message .= "$bill->title \n";
            for( $i=0; $i < strlen($bill->title); $i++ ){
                $message .= "-";
            } 
            $message .= "\n";
            $message .= "Recurrence:  " . $bill->recurrence . "\n";
            $message .= "Renews on:   " . $bill->renews_on . "\n";
            if( $bill->comments ) $message .=   "Comments:     $bill->comments\n\n";
        }
        return $message;
    }

    private function get_users_with_bills()
    {
        $this->users = DB::table('users')->get();
        $this->emails = array();
        foreach ($this->users as $key=>$user) {

            // Find available bills for the user
            $bills = DB::table('bills')->where_user_id($user->id)->where_send_reminder(true)->get();
            
            // Sort bills by due date and add 'due_in'
            $user->bills = Bill::sort_bills_by_date($bills);
            
            // Unset any bills that don't have a reminder today
            foreach ($user->bills as $key => $bill) {
                if( $bill->reminder != 0 ){
                    unset($user->bills[$key]);
                }
            }

            // Populate the users array with users that do have notifiable bills
            if( count($user->bills) > 0 ){
                array_push($this->emails, $user);
            }
        }
        return $this->emails;
    }
}
