<?php defined('APP_NAME') or define("APP_NAME", 'Email Parser');


// response status 
defined('STATUS_BAD_REQUEST') or define("STATUS_BAD_REQUEST", 400);
defined('STATUS_UNAUTHORIZED') or define("STATUS_UNAUTHORIZED", 401);
defined('STATUS_CREATED') or define("STATUS_CREATED", 201);
defined('STATUS_OK') or define("STATUS_OK", 200);
defined('STATUS_GENERAL_ERROR') or define("STATUS_GENERAL_ERROR", 500);
defined('STATUS_FORBIDDEN') or define("STATUS_FORBIDDEN", 403);
defined('STATUS_NOT_FOUND') or define("STATUS_NOT_FOUND", 404);
defined('STATUS_METHOD_NOT_ALLOWED') or define("STATUS_METHOD_NOT_ALLOWED", 405);
defined('STATUS_ALREADY_EXIST') or define("STATUS_ALREADY_EXIST", 409);
defined('UNPROCESSABLE_ENTITY') or define("UNPROCESSABLE_ENTITY", 422);
defined('STATUS_LINK_EXPIRED') or define("STATUS_LINK_EXPIRED", 410);
defined('TOO_MANY_REQUESTS') or define("TOO_MANY_REQUESTS", 429);