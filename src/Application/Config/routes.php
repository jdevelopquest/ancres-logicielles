<?php
return [

    // home
    [
        "isAjax" => false,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^$/",
        "methodPattern" => "/^(GET)$/",
        "rolePattern" => "/^(guest|registered|moderator|admin)$/",
        "controller" => "App\Controllers\PostsController",
        "action" => "indexSoftwares"
    ],

// posts indexSoftwares
    [
        "isAjax" => false,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^ctr=posts&act=indexSoftwares$/",
        "methodPattern" => "/^(GET)$/",
        "rolePattern" => "/^(guest|registered|moderator|admin)$/",
        "controller" => "App\Controllers\PostsController",
        "action" => "indexSoftwares"
    ],

// posts showSoftware
    [
        "isAjax" => false,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^ctr=posts&act=showSoftware&id=\d+$/",
        "methodPattern" => "/^(GET)$/",
        "rolePattern" => "/^(guest|registered|moderator|admin)$/",
        "controller" => "App\Controllers\PostsController",
        "action" => "showSoftware"
    ],

// posts addSoftware
    [
        "isAjax" => false,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^ctr=posts&act=addSoftware$/",
        "methodPattern" => "/^(GET|POST)$/",
        "rolePattern" => "/^(moderator|admin)$/",
        "controller" => "App\Controllers\PostsController",
        "action" => "addSoftware"
    ],

// posts unpublish
    [
        "isAjax" => true,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^ctr=posts&act=unpublish$/",
        "methodPattern" => "/^(POST)$/",
        "rolePattern" => "/^(moderator|admin)$/",
        "controller" => "App\Controllers\PostsController",
        "action" => "unpublish"
    ],

// posts publish
    [
        "isAjax" => true,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^ctr=posts&act=publish$/",
        "methodPattern" => "/^(POST)$/",
        "rolePattern" => "/^(moderator|admin)$/",
        "controller" => "App\Controllers\PostsController",
        "action" => "publish"
    ],

// posts ban
    [
        "isAjax" => true,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^ctr=posts&act=ban$/",
        "methodPattern" => "/^(POST)$/",
        "rolePattern" => "/^(moderator|admin)$/",
        "controller" => "App\Controllers\PostsController",
        "action" => "ban"
    ],

// posts unban
    [
        "isAjax" => true,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^ctr=posts&act=unban$/",
        "methodPattern" => "/^(POST)$/",
        "rolePattern" => "/^(moderator|admin)$/",
        "controller" => "App\Controllers\PostsController",
        "action" => "unban"
    ],

// posts updatePostboxModTool
    [
        "isAjax" => true,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^ctr=posts&act=updatePostboxModTool$/",
        "methodPattern" => "/^(POST)$/",
        "rolePattern" => "/^(moderator|admin)$/",
        "controller" => "App\Controllers\PostsController",
        "action" => "updatePostboxModTool"
    ],

// posts updateSoftwareStatus
    [
        "isAjax" => true,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^ctr=posts&act=updateSoftwareStatus$/",
        "methodPattern" => "/^(POST)$/",
        "rolePattern" => "/^(moderator|admin)$/",
        "controller" => "App\Controllers\PostsController",
        "action" => "updateSoftwareStatus"
    ],

// supports about
    [
        "isAjax" => false,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^ctr=supports&act=about$/",
        "methodPattern" => "/^(GET)$/",
        "rolePattern" => "/^(guest|registered|moderator|admin)$/",
        "controller" => "App\Controllers\SupportsController",
        "action" => "about"
    ],

// supports policies
    [
        "isAjax" => false,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^ctr=supports&act=policies$/",
        "methodPattern" => "/^(GET)$/",
        "rolePattern" => "/^(guest|registered|moderator|admin)$/",
        "controller" => "App\Controllers\SupportsController",
        "action" => "policies"
    ],

// accounts signup
    [
        "isAjax" => false,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^ctr=accounts&act=signup$/",
        "methodPattern" => "/^(GET|POST)$/",
        "rolePattern" => "/^(guest)$/",
        "controller" => "App\Controllers\AccountsController",
        "action" => "signup"
    ],

// accounts login
    [
        "isAjax" => false,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^ctr=accounts&act=login$/",
        "methodPattern" => "/^(GET|POST)$/",
        "rolePattern" => "/^(guest)$/",
        "controller" => "App\Controllers\AccountsController",
        "action" => "login"
    ],

// accounts logout
    [
        "isAjax" => false,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^ctr=accounts&act=logout$/",
        "methodPattern" => "/^(GET|POST)$/",
        "rolePattern" => "/^(registered|moderator|admin)$/",
        "controller" => "App\Controllers\AccountsController",
        "action" => "logout"
    ],

// users api saveTheme
    [
        "isAjax" => true,
        "pathPattern" => "/^(\/|\/public\/index\.php)$/",
        "queryPattern" => "/^ctr=users&act=saveTheme$/",
        "methodPattern" => "/^(POST)$/",
        "rolePattern" => "/^(guest|registered|moderator|admin)$/",
        "controller" => "App\Controllers\SessionsController",
        "action" => "saveTheme"
    ],
];