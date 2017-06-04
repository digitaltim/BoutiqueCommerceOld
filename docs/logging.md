Logging

Stored in $config['storage']['pathLogs']

apacheErrors.log

phpErrors.log 
written to in ErrorHandler::handleError()

events.log
written by monolog (slim's container['logger'])

Note that some errors/events are written to both events.log and phpErrors.log (ie, CSRF Check Failure, Too many login attempts failuer).