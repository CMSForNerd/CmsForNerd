# 🏁 Guide: Testing Back/Forward Cache (bfcache)

The CMSForNerd v3.5 laboratory environment utilizes an advanced Vanilla Javascript History API router. To ensure instantaneous navigation experiences, we implemented Back/Forward Cache (bfcache) optimizations (such as `AbortController` cancellation for pending network requests). 

As a developer or student, here is the protocol to verify that bfcache is actively working in your local environment.

## The Chrome DevTools Method

The most scientific way to verify bfcache eligibility is using Google Chrome's built-in application diagnostic panel.

### Step 1: Start the Local Laboratory
First, ensure you are running the CMSForNerd environment locally.
*   **Via PHP Native Server:** `php -S localhost:8000 index.php`
*   **Via Laravel Herd:** `herd secure cmsfornerd` (Ensure HTTPS is active)

Navigate to your homepage (e.g., `https://cmsfornerd.test`).

### Step 2: Open Developer Tools
Right-click on the page and select **Inspect**, or press `F12` (`Cmd+Option+I` on Mac).

1.  Navigate to the **Application** tab at the top.
2.  In the left sidebar, under the **Background Services** category, click on **Back/forward cache**.

### Step 3: Execute the Test
1.  Click the button labeled **"Test back/forward cache"**.
2.  Chrome will simulate navigating away from the page and then returning to it.

#### Analyzing the Results:
*   **✅ Success (`Actionable` / `Restored`)**: If the frame is successfully cached, Chrome will display a green success message indicating "Restored from back/forward cache".
*   **❌ Failure (`notRestoredReasons`)**: If it fails, Chrome will list exact reasons why. Common reasons include open WebSocket connections, incomplete `fetch` requests, or active `unload` event listeners (which we strictly prohibit in v3.5).

---

## The Console Log Method

Our PWA router (`assets/pwa/router.js`) is equipped with precise lifecycle hooks designed to log bfcache events directly to your console.

### How to Trigger:
1.  Open the **Console** tab in Developer Tools.
2.  Click any internal link in the CMS to navigate to a new page (e.g., "About Us").
3.  Click the **Browser Back Button**.
4.  Observe the Console. If bfcache is working, you will immediately see the following log without the page attempting to reload:
    
    `> [PWA Router] Page restored from bfcache instantaneously.`

*If you see `> [PWA Router] Hydrated:` instead, it means bfcache failed and the router had to perform a standard AJAX fragment fetch to restore the page state.*

---

## Troubleshooting `AbortError`
If you click links extremely rapidly, you may see `> [PWA Router] Fetch aborted due to navigation.` in the console log. 

**This is intentional and correct behavior.** It means our `AbortController` successfully killed the dangling network request, thereby keeping the page eligible for bfcache suspension when you navigated away.
