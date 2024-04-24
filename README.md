# Symfony application for guestbook

### Stack

    - Client side (SSR,SPA)
    - Admin side (EasyAdmin, default Auth)
    - RestAPI (OpenAPI resources)
    - Symfony cli
    - Postgres 16
    - Mailpit
    - Cordova
    - Preact on SPA
    - Webpack and webpack encore
    - Bootstrap5

### Functionality

    - Notifications with Slack and Mail channels
    - Commands with cron, comment cleanup and git step
    - Unit and Functional tests (data fixtures)
    - Conference entity listener (computes slug)
    - Messages and message handler
    - Comment state machine
    - Image Resizer service
    - Spam checker service
    - Caching with local esi strategy
    - Inner forms
    - Twig template
    - Vault secret keeping
    - Doctrine auto mapping
    - Async bus messaging
    - Makefile

## Cloud
    - Deploy on platform.sh
    - Varnish caching strategy

## Problems that have left to do
    - Deploying spa on platform sh cloud (node build problem)
    - Building cordova application

## Things that need to touch
    - symfony internals blackfire
    - symfony internals xdebug
        
