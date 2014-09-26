<?php

class EventSubmissionEmailService extends EmailBaseService
{
    protected $event;
    protected $comment;

    public function __construct($config, $recipients, $event)
    {
        // set up the common stuff first
        parent::__construct($config, $recipients);

        // this email needs event info
        $this->event = $event;
    }

    public function sendEmail()
    {
        $this->setSubject('New event submitted to joind.in');

        $date = new DateTime($this->event['start_date']);

        $replacements = array(
            "title"       => $this->event['name'],
            "description" => $this->event['description'],
            "date"        => $date->format('jS M, Y'),
            "contact_name"   => $this->event['contact_name'],
        );

        $messageBody = $this->parseEmail("eventSubmission.md", $replacements);
        $messageHTML = $this->markdownToHtml($messageBody);

        $this->setBody($this->htmlToPlainText($messageHTML));
        $this->setHtmlBody($messageHTML);

        $this->dispatchEmail();
    }
}
