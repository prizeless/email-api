<?php
$I = new ApiTester($scenario);
$I->wantTo('call the messages activity report endpoint');
$post = '[
  {
    "sg_message_id":"sendgrid_internal_message_id",
    "message_id":1,
    "email": "antony@prizeless.net",
    "timestamp": 1337197600,
    "smtp-id": "<4FB4041F.6080505@sendgrid.com>",
    "event": "processed"
  },
  {
    "sg_message_id":"sendgrid_internal_message_id",
    "message_id": 1,
    "email": "john.doe@sendgrid.com",
    "timestamp": 1337966815,
    "category": "newuser",
    "event": "click",
    "url": "https://sendgrid.com"
  },
  {
    "sg_message_id":"sendgrid_internal_message_id",
    "email": "john.doe@sendgrid.com",
    "timestamp": 1337969592,
    "smtp-id": "<20120525181309.C1A9B40405B3@Example-Mac.local>",
    "event": "group_unsubscribe",
    "asm_group_id": 42
  }
]';

$I->sendPOST('http://localhost:8001/messages/email/events', $post);
$I->seeResponseCodeIs(200);
