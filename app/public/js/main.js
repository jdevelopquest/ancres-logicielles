/**
 * Constructs a URL based on the specified controller, action, and optional id parameter.
 *
 * @param {string} controller - The name of the controller to be included in the URL.
 * @param {string} action - The name of the action to be included in the URL.
 * @param {string|null} [id=null] - An optional identifier to be appended as a query parameter (id).
 * @return {string} A formatted URL string.
 */
function constructUrl(controller, action, id = null) {
  return (
    `/${controller}/${action}` +
    (id !== null && id !== undefined ? `?id=${encodeURIComponent(id)}` : "")
  );
}

/**
 * Initializes menu buttons and their corresponding menu toggle functionality.
 *
 * This function associates click event listeners with menu buttons and manages the toggle visibility
 * of the corresponding menu. For "tiny" menus, it also ensures the visibility state is updated using
 * the `setTinyMenuVisibility` function.
 *
 * @param {string} menuTag The identifier tag used to associate menu buttons with their corresponding menu elements.
 * @return {void} This function does not return a value.
 */
function initMenuButtons(menuTag) {
  const buttonSelector = ".button-menu-" + menuTag;
  const menuSelector = ".menu-" + menuTag;
  document.querySelectorAll(buttonSelector)?.forEach((button) => {
    button.addEventListener("click", function () {
      const menu = document.querySelector(menuSelector);
      menu?.classList.toggle("hide");
      if (menuTag === "tiny") {
        setTinyMenuVisibility(!menu?.classList.contains("hide"));
      }
      menu?.querySelectorAll(".button-close")?.forEach((button) => {
        button?.addEventListener("click", function () {
          menu?.classList.add("hide");
          if (menuTag === "tiny") {
            setTinyMenuVisibility(false);
          }
        });
      });
    });
  });
}

/**
 * Sets the visibility state of the tiny menu in local storage.
 *
 * @param {boolean} [visible=false] - A boolean indicating whether the tiny menu should be visible.
 * @return {void} This method does not return a value.
 */
function setTinyMenuVisibility(visible = false) {
  localStorage.setItem("menu-tiny-visible", visible ? "true" : "false");
}

/**
 * Retrieves the visibility status of the tiny menu from local storage.
 *
 * @return {string|boolean} The visibility status of the tiny menu as a string
 *                          or `false` if not set in local storage.
 */
function getTinyMenuVisibility() {
  return localStorage.getItem("menu-tiny-visible") ?? false;
}

/**
 * Toggles the visibility state of a tiny menu element based on a predefined condition.
 * Checks the current visibility state through `getTinyMenuVisibility()` and
 * updates the menu's visibility by adding or removing the 'hide' CSS class.
 *
 * @return {void} This method does not return any value.
 */
function applyTinyMenuVisibility() {
  const menuSelector = ".menu-tiny";
  const menu = document.querySelector(menuSelector);
  if (getTinyMenuVisibility() === "true") {
    menu?.classList.remove("hide");
  } else {
    menu?.classList.add("hide");
  }
}

/**
 * Initializes moderation buttons for post-actions by adding event listeners.
 *
 * @param {string} buttonAction - The action associated with the post-buttons (e.g., 'delete', 'approve').
 * @return {void} This function does not return a value.
 */
function initPostboxModButtons(buttonAction) {
  const buttonSelector = ".button-post-" + buttonAction;
  document.querySelectorAll(buttonSelector)?.forEach((button) => {
    button.addEventListener("click", function () {
      const params = {
        idPost: button.dataset.idPost,
        action: buttonAction,
      };
      sendPostModAction(params);
    });
  });
}

/**
 * Initializes the post-box modification buttons for various actions such as 'publish', 'unpublish', 'ban', and 'unban'.
 * This function sets up the necessary buttons by calling helper functions with the respective action types.
 *
 * @return {void} Does not return a value.
 */
function initPostboxMod() {
  initPostboxModButtons("publish");
  initPostboxModButtons("unpublish");
  initPostboxModButtons("ban");
  initPostboxModButtons("unban");
}

/**
 * Sends a POST moderation action to the server for a specified post.
 *
 * @param {Object} params - The parameters for the moderation action.
 * @param {string} params.action - The action to be performed on the post (e.g., 'delete', 'approve').
 * @param {string} params.idPost - The ID of the post to apply the action to.
 * @return {void} This function does not return any value but performs a fetch operation to send the request.
 */
function sendPostModAction(params) {
  const posts = "posts";
  const action = params.action;
  const idPost = params.idPost;
  const data = {
    idPost: idPost,
  };
  const url = constructUrl(posts, action);
  fetch(url, {
    method: "POST",
    headers: {
      "X-Ajax-Request": "true",
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(response.statusText);
      }
      // Il n'y a pas de données à récupérer,
      // mettre à jour les status et la barre d'outils
      getUpdatePostboxModTool(idPost).then((response) =>
        getUpdateSoftwareStatus(idPost),
      );
    })
    .catch((error) => {
      // console.error('Il y a eu un problème avec la requête fetch:', error);
    });
}

/**
 * Sends a request to update the post-box moderation tool for a specific post and processes the response.
 *
 * @param {string} idPost - The unique identifier of the post to be updated.
 * @return {Promise<void>} A promise that resolves when the update operation is completed.
 */
async function getUpdatePostboxModTool(idPost) {
  const posts = "posts";
  const action = "updatePostboxModTool";
  const body = {
    idPost: idPost,
  };
  const url = constructUrl(posts, action);
  await fetch(url, {
    method: "POST",
    headers: {
      "X-Ajax-Request": "true",
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    body: JSON.stringify(body),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      updatePostboxModTool(idPost, data);
    })
    .catch((error) => {
      // console.error(error);
    });
}

/**
 * Sends a request to update the software status for a given post and processes the response.
 *
 * @param {number|string} idPost - The ID of the post for which the software status needs to be updated.
 * @return {Promise<void>} A promise that resolves when the request is successfully processed or rejects if an error occurs.
 */
async function getUpdateSoftwareStatus(idPost) {
  const posts = "posts";
  const action = "updateSoftwareStatus";
  const body = {
    idPost: idPost,
  };
  const url = constructUrl(posts, action);
  await fetch(url, {
    method: "POST",
    headers: {
      "X-Ajax-Request": "true",
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    body: JSON.stringify(body),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      updateSoftwareStatus(idPost, data);
    })
    .catch((error) => {
      // console.error(error);
    });
}

/**
 * Updates the content of the post-box moderation tools for the given post-ID.
 *
 * @param {string} idPost - The identifier of the post whose moderation tools need to be updated.
 * @param {string} data - The HTML content to replace the current content of the post-box moderation tools.
 * @return {void} - Does not return a value.
 */
function updatePostboxModTool(idPost, data) {
  const postboxSelector = `.postbox-mod-tools-${idPost}`;
  document.querySelectorAll(postboxSelector)?.forEach((postbox) => {
    // todo attention à la sécurité
    postbox.innerHTML = data;
  });
  initPostboxMod();
}

/**
 * Updates the software status of a given item by modifying the inner HTML
 * of elements matched by a specific selector.
 *
 * @param {string|number} idPost - The unique identifier for the post-box whose status will be updated.
 * @param {string} data - The new status content to be set as the inner HTML of the matched elements.
 * @return {void} This function does not return a value.
 */
function updateSoftwareStatus(idPost, data) {
  const postboxStatusSelector = `.postbox-status-${idPost}`;
  document.querySelectorAll(postboxStatusSelector)?.forEach((postbox) => {
    // todo attention à la sécurité
    postbox.innerHTML = data;
  });
}

/**
 * Initializes theme switch buttons by attaching click event listeners
 * to elements with the class 'button-switch-theme'. On button click,
 * this method toggles the theme between 'theme-light' and 'theme-dark'
 * on the body element and applies the selected theme.
 *
 * @return {void} This method does not return any value.
 */
function initSwitchThemeButtons() {
  document.querySelectorAll(".button-switch-theme")?.forEach((button) => {
    button.addEventListener("click", function () {
      const body = document.querySelector("body");
      body?.classList.toggle("theme-light");
      body?.classList.toggle("theme-dark");
      const theme = body?.classList.contains("theme-light")
        ? "theme-light"
        : "theme-dark";
      setTheme(theme);
    });
  });
}

/**
 * Sets the application theme by saving the provided theme name to localStorage.
 * If no theme is provided, it defaults to 'theme-light'.
 *
 * @param {string} [theme='theme-light'] - The name of the theme to set.
 * @return {void} - This method does not return any value.
 */
function setTheme(theme = "theme-light") {
  localStorage.setItem("theme", theme);
}

/**
 * Retrieves the current theme from local storage.
 * If no theme is found, defaults to 'theme-light'.
 *
 * @return {string} The current theme name or the default theme 'theme-light'.
 */
function getTheme() {
  return localStorage.getItem("theme") ?? "theme-light";
}

/**
 * Applies the appropriate theme to the document body based on the current theme setting.
 * It toggles between 'theme-dark' and 'theme-light' classes on the body element.
 *
 * @return {void} Does not return any value.
 */
function applyTheme() {
  const body = document.querySelector("body");
  if (getTheme() === "theme-dark") {
    body?.classList.remove("theme-light");
    body?.classList.add("theme-dark");
  } else {
    body?.classList.add("theme-light");
    body?.classList.remove("theme-dark");
  }
}

document.addEventListener("DOMContentLoaded", function () {
  initMenuButtons("hamburger");
  initMenuButtons("tiny");
  initPostboxMod();
  initSwitchThemeButtons();
  applyTheme();
  applyTinyMenuVisibility();
});
