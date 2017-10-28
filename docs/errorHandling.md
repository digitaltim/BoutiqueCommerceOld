Error Handling Rules

Reporting Methods:

1. Logging
    All error details are logged to $config['storage']['logs']['pathPhpErrors'].

2. Echo
    Live Servers*
    Error details are never echoed, rather, a generic error message is echoed. For fatal errors, this message is set in $config['errors']['fatalMessage'].

    Dev Servers*
    Error details are echoed if $config['errors']['echoDev'] is true
    
3. Email
    For security, error details are never emailed.

    Live Servers
    All errors cause generic error notifications to be emailed to $config['errors']['emailTo'].
    
    Dev Servers*
    Generic error notifications are emailed to $config['errors']['emailTo'] if $config['errors']['emailDev'] is true.
    
    
* $config['isLive'] boolean from config/env.php determines whether site is running from live or dev server.