<?php
// config/webhooks.php
define('N8N_WEBHOOK_LOGIN', 'https://n8n.kairasystems.com/webhook/edubook/login');
define('N8N_WEBHOOK_REGISTER', 'https://n8n.kairasystems.com/webhook/edubook/registro');
define('N8N_CHATBOT_URL', 'https://n8n.kairasystems.com/webhook/edubook-chatbot/chat');
define('N8N_WEBHOOK_GET_EVENTS', 'https://n8n.kairasystems.com/webhook/edubook/events'); // Nuevo webhook Issue 1
define('N8N_WEBHOOK_CREATE_EVENT', 'https://n8n.kairasystems.com/webhook/edubook/event/create'); // Issue 2
define('N8N_WEBHOOK_UPDATE_EVENT', 'https://n8n.kairasystems.com/webhook/edubook/event/update'); // Issue 2
define('N8N_WEBHOOK_DELETE_EVENT', 'https://n8n.kairasystems.com/webhook/edubook/event/delete'); // Issue 2
define('N8N_WEBHOOK_SUBSCRIBE_EVENT', 'https://n8n.kairasystems.com/webhook/edubook/event/subscribe'); // Issue 3
define('N8N_WEBHOOK_UNSUBSCRIBE_EVENT', 'https://n8n.kairasystems.com/webhook/edubook/event/unsubscribe'); // Issue 3
define('N8N_WEBHOOK_GET_USER_EVENTS', 'https://n8n.kairasystems.com/webhook/edubook/user/events'); // Issue 3
define('N8N_SECRET', 'EDUBOOK');
define('ALLOWED_ORIGIN', '*');
?>