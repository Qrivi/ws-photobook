# ws-photobook

Small server add-on for WhatSlack that enables showing WhatsApp profile pictures for messages forwarded to Slack.

Might sound fancy, but in practice this is just a POST endpoint that saves a WhatsApp user id and a base64 image string to the database, and a GET endpoint that fetches the base64 string and converts it to an image resource. This image resource can then be used by WhatSlack  as `icon_url` when [posting a message to Slack](https://api.slack.com/methods/chat.postMessage).

Quickly written in PHP because I did not want to spend too much time on this (for now), wanted something easy-to-deploy to Heroku and fast to startup (when Heroku's dyno went to sleep). And also I did not want to upload other people's profile pictures to public image sharing services.
