Error Handling

Error Handling Rules

Reporting Methods:

1. Logging
    All error details are logged to $config['storage']['logs']['pathPhpErrors'].

2. Echo
    Live Servers*
    Error details are never echoed, rather, a generic error message is echoed. For fatal errors, this message is set in $config['errors']['fatalMessage'].
    *Test for live server: $config['isLive'] is true, set in config/env.php

    Dev Servers*
    Error details are echoed if $config['errors']['echoDev'] is true<br>
    * Test for Dev Server: $config['isLive'] is false, set in config/env.php
    
3. Email
    For security reasons, error details are never emailed.

    Live Servers
    All errors cause generic error notifications to be emailed to $config['errors']['emailTo'].
    
    Dev Servers*
    Generic error notifications are emailed to $config['errors']['emailTo'] if $config['errors']['emailDev'] is true.