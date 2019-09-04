
## About Newsletter Management System  
  
NMS is a transactional e-mail micro-service. This micro-service uses external services to send emails. When such an external service is unavailable there is a fallback to a secondary service. It is also possible to extend fallback services.  
  
 1. Developed using Laravel 5.8  
 2. Graphical User Interface is developed using VueJS, JQuery, and Bootstrap.  
 3. GUI supports for Text, HTML, and Markdown mail contents.  
 4. Expandable Mail Drivers with WebHook support.  
 5. PHPUnit test cases.  
 6. Expandable Asynchronous Workers (default count=8).
 7. Docker-ready.
  
## DEMO  
  
**[http://185.92.222.97/](http://185.92.222.97/)**  
  
## Transactional Mailing Mechanism  
  
This application uses 3rd-party email delivery platforms for sending emails. There are currently two pre-defined email drivers (SendGrid and Mailjet) in the system. If a mail driver fails to send an email, then the other will try to send it. Email driver structure has been developed in a way that makes it easily expandable. And, the priority of email drivers can be defined easily.  

To improve the speed of the API calls, the sending happens asynchronously via queuing technique. The default worker count is 8. You can increase or decrease it in the file `laravel-worker.conf`.
  
The webhook feature is also developed so that the application can get feedback about the mails sent. In order to use the webhook feature, the application must be deployed in a host which is accessible from web and webhook addresses must be defined to email delivery platforms.  
  
I have deployed the app in a VPS so that you can test the app's interoperability with webhook feature.  
  
  
## Deployment using Docker  
  
You can deploy the application by following the steps below: 
  
 1. `git clone https://github.com/bariscimen/newslettermanagementsystem` 
 2. `cd newslettermanagementsystem`
 3. `docker-compose up -d`
 4. `docker-compose exec app bash install.sh`

Now, you can access the app at http://localhost/
  
## GUI  
  
I developed a GUI using VueJS, JQuery, and Bootstrap.  Also, I used TinyMCE editor for email body, which provides Text, HTML, and Markdown supports. This GUI basically utilizes the app's API. 

## API Support  
  
I developed an API so that you can send email through API calls. Basic description of the API is as follows:

```
// List deliveries  
GET /deliveries  
  
// List single delivery  
GET /delivery/{id}
  
// List statuses of an delivery  
GET /delivery/{id}/status
  
// List attachments of an mail  
GET /mail/{id}/attachment
  
// Create new mail  
POST /mail
```
Example POST json for /mail
```
{  
"from": {  
"email": "from@example.com",  
"name": "From Email"  
},  
"replyTo": {  
"email": "replyTo@example.com",  
"name": "ReplyTo Email"  
},  
"to": [  
{  
"email": "user1@example.com",  
"name": "User 1"  
},  
{  
"email": "user2@example.com",  
"name": "User 2"  
}  
],  
"subject": "Test subject!",  
"text": "Dear user, May the force be with you!",  
"html": "<h3>Dear user,</h3> May the delivery force be with you!",  
"attachments": [  
{  
"contentType": "text/plain",  
"filename": "test.txt",  
"base64Content": "VGhpcyBpcyB5b3VyIGF0dGFjaGVkIGZpbGUhISEK"  
}  
]  
}
```
  
## Command-line Interface  
  
Also, I developed an CLI through which you can send an email. Basic description of the CLI is as follows:

`php artisan email:send [options]`
```
Description:
  Send e-mails

Usage:
  email:send [options]

Options:
  -F, --from[=FROM]        From e-mail address e.g. example@example.com
  -R, --replyTo[=REPLYTO]  ReplyTo e-mail address e.g. example@example.com
  -T, --to[=TO]            To e-mail address e.g. example@example.com (multiple values allowed)
  -S, --subject[=SUBJECT]  Email subject
  -C, --content[=CONTENT]  Email content
  -h, --help               Display this help message
  -q, --quiet              Do not output any message
  -V, --version            Display this application version
      --ansi               Force ANSI output
      --no-ansi            Disable ANSI output
  -n, --no-interaction     Do not ask any interactive question
      --env[=ENV]          The environment the command should run under
  -v|vv|vvv, --verbose     Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

```
  
## PHPUnit Tests  
  
I created 3 PHPUnit test cases. One for testing general database operations, another for testing API, and the last for testing GUI.
