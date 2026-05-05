<?php
declare(strict_types=1);
use App\Core\Route;
use App\Core\Router;

// health
Router::add(
    new Route(
        isAjax: false,
        pathPattern: "#^(\/health)$#",
        queryPattern: "#^$#",
        methodPattern: "#^(GET)$#",
        rolePattern: "#^(guest|registered|moderator|admin)$#",
        controller: "App\Controllers\HealthsController",
        action: "healthJson",
    ),
);

// home
// posts indexSoftwares
Router::add(
    new Route(
        isAjax: false,
        pathPattern: "#^(\/|\/posts\/indexSoftwares)$#",
        queryPattern: "#^$#",
        methodPattern: "#^(GET)$#",
        rolePattern: "#^(guest|registered|moderator|admin)$#",
        controller: "App\Controllers\PostsController",
        action: "indexSoftwares",
    ),
);

// posts showSoftware
Router::add(
    new Route(
        isAjax: false,
        pathPattern: "#^\/posts/showSoftware$#",
        queryPattern: "#^id=\d+$#",
        methodPattern: "#^(GET)$#",
        rolePattern: "#^(guest|registered|moderator|admin)$#",
        controller: "App\Controllers\PostsController",
        action: "showSoftware",
    ),
);

// posts addSoftware
Router::add(
    new Route(
        isAjax: false,
        pathPattern: "#^/posts/addSoftware$#",
        queryPattern: "#^$#",
        methodPattern: "#^(GET|POST)$#",
        rolePattern: "#^(moderator|admin)$#",
        controller: "App\Controllers\PostsController",
        action: "addSoftware",
    ),
);

// posts unpublish
Router::add(
    new Route(
        isAjax: true,
        pathPattern: "#^/posts/unpublish$#",
        queryPattern: "#^$#",
        methodPattern: "#^(POST)$#",
        rolePattern: "#^(moderator|admin)$#",
        controller: "App\Controllers\PostsController",
        action: "unpublish",
    ),
);

// posts publish
Router::add(
    new Route(
        isAjax: true,
        pathPattern: "#^/posts/publish$#",
        queryPattern: "#^$#",
        methodPattern: "#^(POST)$#",
        rolePattern: "#^(moderator|admin)$#",
        controller: "App\Controllers\PostsController",
        action: "publish",
    ),
);

// posts ban
Router::add(
    new Route(
        isAjax: true,
        pathPattern: "#^/posts/ban$#",
        queryPattern: "#^$#",
        methodPattern: "#^(POST)$#",
        rolePattern: "#^(moderator|admin)$#",
        controller: "App\Controllers\PostsController",
        action: "ban",
    ),
);

// posts unban
Router::add(
    new Route(
        isAjax: true,
        pathPattern: "#^/posts/unban$#",
        queryPattern: "#^$#",
        methodPattern: "#^(POST)$#",
        rolePattern: "#^(moderator|admin)$#",
        controller: "App\Controllers\PostsController",
        action: "unban",
    ),
);

// posts updatePostboxModTool
Router::add(
    new Route(
        isAjax: true,
        pathPattern: "#^/posts/updatePostboxModTool$#",
        queryPattern: "#^$#",
        methodPattern: "#^(POST)$#",
        rolePattern: "#^(moderator|admin)$#",
        controller: "App\Controllers\PostsController",
        action: "updatePostboxModTool",
    ),
);

// posts updateSoftwareStatus
Router::add(
    new Route(
        isAjax: true,
        pathPattern: "#^/posts/updateSoftwareStatus$#",
        queryPattern: "#^$#",
        methodPattern: "#^(POST)$#",
        rolePattern: "#^(moderator|admin)$#",
        controller: "App\Controllers\PostsController",
        action: "updateSoftwareStatus",
    ),
);

// supports about
Router::add(
    new Route(
        isAjax: false,
        pathPattern: "#^/supports/about$#",
        queryPattern: "#^$#",
        methodPattern: "#^(GET)$#",
        rolePattern: "#^(guest|registered|moderator|admin)$#",
        controller: "App\Controllers\SupportsController",
        action: "about",
    ),
);

// supports policies
Router::add(
    new Route(
        isAjax: false,
        pathPattern: "#^/supports/policies$#",
        queryPattern: "#^$#",
        methodPattern: "#^(GET)$#",
        rolePattern: "#^(guest|registered|moderator|admin)$#",
        controller: "App\Controllers\SupportsController",
        action: "policies",
    ),
);

// accounts signup
Router::add(
    new Route(
        isAjax: false,
        pathPattern: "#^/accounts/signup$#",
        queryPattern: "#^$#",
        methodPattern: "#^(GET|POST)$#",
        rolePattern: "#^(guest)$#",
        controller: "App\Controllers\AccountsController",
        action: "signup",
    ),
);

// accounts login
Router::add(
    new Route(
        isAjax: false,
        pathPattern: "#^/accounts/login$#",
        queryPattern: "#^$#",
        methodPattern: "#^(GET|POST)$#",
        rolePattern: "#^(guest)$#",
        controller: "App\Controllers\AccountsController",
        action: "login",
    ),
);

// accounts logout
Router::add(
    new Route(
        isAjax: false,
        pathPattern: "#^/accounts/logout$#",
        queryPattern: "#^$#",
        methodPattern: "#^(GET|POST)$#",
        rolePattern: "#^(registered|moderator|admin)$#",
        controller: "App\Controllers\AccountsController",
        action: "logout",
    ),
);
