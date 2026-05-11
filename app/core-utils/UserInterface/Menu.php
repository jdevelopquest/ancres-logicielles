<?php
declare(strict_types=1);

namespace App\CoreUtils\UserInterface;

final class Menu
{
    private array $menus = [];
    private string $lastSubMenuTag = "submenu";

    /**
     * Constructor method for initializing the object with a name property.
     *
     * @param string $name The name to initialize the object with. Defaults to "menu".
     * @return void
     */
    public function __construct(private readonly string $name = "menu") {}

    /**
     * Retrieves the menu items associated with the current instance.
     *
     * @return array An associative array where the key is the instance name and the value is the corresponding menus.
     */
    public function getMenu(): array
    {
        return [$this->name => $this->menus];
    }

    /**
     * Adds a submenu with the given tag to the menu structure.
     *
     * @param string $tag The identifier for the submenu. Defaults to "submenu".
     * @return static Returns the current Menu instance for method chaining.
     */
    public function addSubMenu(string $tag = "submenu"): static
    {
        $this->lastSubMenuTag = $tag;
        $this->menus[$this->lastSubMenuTag] = [];
        return $this;
    }

    /**
     * Adds a menu item to the current submenu.
     *
     * @param string $href The URL or link of the menu item.
     * @param string $title The title attribute of the menu item for additional description.
     * @param string $text The display text for the menu item.
     * @param string $icon The icon associated with the menu item.
     * @return static The current Menu instance for chaining.
     */
    public function addSubMenuItem(
        string $href,
        string $title,
        string $text,
        string $icon,
    ): static {
        $this->menus[$this->lastSubMenuTag][] = [
            "href" => $href,
            "title" => $title,
            "text" => $text,
            "icon" => $icon,
        ];
        return $this;
    }
}
