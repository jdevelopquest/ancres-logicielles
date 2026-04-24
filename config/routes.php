<?php
return [

// home
// posts indexSoftwares
    [
        "isAjax" => false,
        "pathPattern" => "#^(\/|\/posts\/indexSoftwares)$#",
        "queryPattern" => "#^$#",
        "methodPattern" => "#^(GET)$#",
        "rolePattern" => "#^(guest|registered|moderator|admin)$#",
        "controller" => "App\Controllers\PostsController",
        "action" => "indexSoftwares"
    ],

// posts showSoftware
    [
        "isAjax" => false,
        "pathPattern" => "#^\/posts/showSoftware$#",
        "queryPattern" => "#^id=\d+$#",
        "methodPattern" => "#^(GET)$#",
        "rolePattern" => "#^(guest|registered|moderator|admin)$#",
        "controller" => "App\Controllers\PostsController",
        "action" => "showSoftware"
    ],

// posts addSoftware
    [
        "isAjax" => false,
        "pathPattern" => "#^/posts/addSoftware$#",
        "queryPattern" => "#^$#",
        "methodPattern" => "#^(GET|POST)$#",
        "rolePattern" => "#^(moderator|admin)$#",
        "controller" => "App\Controllers\PostsController",
        "action" => "addSoftware"
    ],

// posts unpublish
    [
        "isAjax" => true,
        "pathPattern" => "#^/posts/unpublish$#",
        "queryPattern" => "#^$#",
        "methodPattern" => "#^(POST)$#",
        "rolePattern" => "#^(moderator|admin)$#",
        "controller" => "App\Controllers\PostsController",
        "action" => "unpublish"
    ],

// posts publish
    [
        "isAjax" => true,
        "pathPattern" => "#^/posts/publish$#",
        "queryPattern" => "#^$#",
        "methodPattern" => "#^(POST)$#",
        "rolePattern" => "#^(moderator|admin)$#",
        "controller" => "App\Controllers\PostsController",
        "action" => "publish"
    ],

// posts ban
    [
        "isAjax" => true,
        "pathPattern" => "#^/posts/ban$#",
        "queryPattern" => "#^$#",
        "methodPattern" => "#^(POST)$#",
        "rolePattern" => "#^(moderator|admin)$#",
        "controller" => "App\Controllers\PostsController",
        "action" => "ban"
    ],

// posts unban
    [
        "isAjax" => true,
        "pathPattern" => "#^/posts/unban$#",
        "queryPattern" => "#^$#",
        "methodPattern" => "#^(POST)$#",
        "rolePattern" => "#^(moderator|admin)$#",
        "controller" => "App\Controllers\PostsController",
        "action" => "unban"
    ],

// posts updatePostboxModTool
    [
        "isAjax" => true,
        "pathPattern" => "#^/posts/updatePostboxModTool$#",
        "queryPattern" => "#^$#",
        "methodPattern" => "#^(POST)$#",
        "rolePattern" => "#^(moderator|admin)$#",
        "controller" => "App\Controllers\PostsController",
        "action" => "updatePostboxModTool"
    ],

// posts updateSoftwareStatus
    [
        "isAjax" => true,
        "pathPattern" => "#^/posts/updateSoftwareStatus$#",
        "queryPattern" => "#^$#",
        "methodPattern" => "#^(POST)$#",
        "rolePattern" => "#^(moderator|admin)$#",
        "controller" => "App\Controllers\PostsController",
        "action" => "updateSoftwareStatus"
    ],

// supports about
    [
        "isAjax" => false,
        "pathPattern" => "#^/supports/about$#",
        "queryPattern" => "#^$#",
        "methodPattern" => "#^(GET)$#",
        "rolePattern" => "#^(guest|registered|moderator|admin)$#",
        "controller" => "App\Controllers\SupportsController",
        "action" => "about"
    ],

// supports policies
    [
        "isAjax" => false,
        "pathPattern" => "#^/supports/policies$#",
        "queryPattern" => "#^$#",
        "methodPattern" => "#^(GET)$#",
        "rolePattern" => "#^(guest|registered|moderator|admin)$#",
        "controller" => "App\Controllers\SupportsController",
        "action" => "policies"
    ],

// accounts signup
    [
        "isAjax" => false,
        "pathPattern" => "#^/accounts/signup$#",
        "queryPattern" => "#^$#",
        "methodPattern" => "#^(GET|POST)$#",
        "rolePattern" => "#^(guest)$#",
        "controller" => "App\Controllers\AccountsController",
        "action" => "signup"
    ],

// accounts login
    [
        "isAjax" => false,
        "pathPattern" => "#^/accounts/login$#",
        "queryPattern" => "#^$#",
        "methodPattern" => "#^(GET|POST)$#",
        "rolePattern" => "#^(guest)$#",
        "controller" => "App\Controllers\AccountsController",
        "action" => "login"
    ],

// accounts logout
    [
        "isAjax" => false,
        "pathPattern" => "#^/accounts/logout$#",
        "queryPattern" => "#^$#",
        "methodPattern" => "#^(GET|POST)$#",
        "rolePattern" => "#^(registered|moderator|admin)$#",
        "controller" => "App\Controllers\AccountsController",
        "action" => "logout"
    ],
];