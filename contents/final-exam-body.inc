<article class="final-exam">
    <header class="exam-header">
        <h1>🚩 The CMSForNerd v4.3.0 Final Exam</h1>
        <p class="mission">Mission: Repair the Laboratory</p>
    </header>

    <div class="exam-intro">
        <p>
            This Final Exam is designed as a <strong>"Break-Fix" challenge</strong>. In the industry, a senior engineer's job 
            is often to debug systems they didn't write. To pass, you must fix five deliberate errors that prevent the CMS 
            from being secure or functional.
        </p>
        <div class="scenario-box">
            <strong>Scenario:</strong> A "junior dev" has pushed a series of updates that violated our RFC 2119 standards 
            and broke PSR-12 compliance. The site is currently vulnerable and the tests are failing.
        </div>
    </div>

    <section class="challenge">
        <h2>❌ Challenge 1: The Security Breach (Module 3)</h2>
        <p>The following loader was found in <code>index.php</code>. It allows an attacker to read <code>/etc/passwd</code> via path traversal.</p>
        <p><strong>Task:</strong> Refactor this using <code>SecurityUtils::sanitizePageName()</code> to make it a "MUST" level security block.</p>
        <div class="code-block broken">
            <pre><code>// BROKEN CODE
$page = $_GET['page']; 
include "contents/" . $page . ".inc"; </code></pre>
        </div>
    </section>

    <section class="challenge">
        <h2>❌ Challenge 2: The Logic Error (Module 1)</h2>
        <p>This PHP 8.4+ class is throwing a syntax error. Task: Fix the <strong>Property Hook</strong> so it correctly returns an uppercase version of the <code>$author</code> name.</p>
        <p><strong>Task:</strong> Remove the incorrect <code>return</code> keyword and ensure the hook is valid.</p>
        <div class="code-block broken">
            <pre><code>// BROKEN CODE
class Project {
    public string $author {
        set => $this->author = $value;
        get => return strtoupper($this->author); // Error here!
    }
}</code></pre>
        </div>
    </section>

    <section class="challenge">
        <h2>❌ Challenge 3: The PSR-12 Audit (Module 2)</h2>
        <p>The following code is functional but fails the <code>phpcs</code> audit. Task: Reformat this block to be <strong>PSR-12 compliant</strong>.</p>
        <p><strong>Task:</strong> Fix the class and function opening braces and indentation.</p>
        <div class="code-block broken">
            <pre><code>// BROKEN CODE
class checker{
function validate($data){
if($data=="valid"){return true;}
else{return false;}
}
}</code></pre>
        </div>
    </section>

    <section class="challenge">
        <h2>❌ Challenge 4: The Failing Test (Module 4)</h2>
        <p>A test uses the wrong assertion, causing it to pass even when data types are different. Task: Change the assertion to a <strong>Strict Assertion</strong> as per our RFC 2119 "SHOULD" requirement.</p>
        <p><strong>Task:</strong> Switch <code>assertEquals</code> to the strict type-safe version.</p>
        <div class="code-block broken">
            <pre><code>// BROKEN CODE
public function testTypeSafety(): void {
    $result = "100"; // String
    $this->assertEquals(104, $result); // This might pass loosely in some versions!
}</code></pre>
        </div>
    </section>

    <section class="challenge">
        <h2>❌ Challenge 5: The CSP Leak (Module 3)</h2>
        <p>The Content Security Policy is currently "Wide Open," allowing any site to run scripts on your CMS.</p>
        <p><strong>Task:</strong> Restrict <code>script-src</code> to only allow <code>'self'</code>.</p>
        <div class="code-block broken">
            <pre><code>&lt;!-- BROKEN CODE --&gt;
&lt;meta http-equiv="Content-Security-Policy" content="script-src *;"&gt;</code></pre>
        </div>
    </section>

    <footer class="exam-footer">
        <h2>📝 Evaluation Criteria</h2>
        <p>A student passes if they can run the following command and receive a perfectly clean report:</p>
        <div class="terminal-block">
            <code>composer compliance</code>
        </div>
        <div class="graduation-cta">
            <h3>🎓 Ready to Submit?</h3>
            <p>Once you have fixed all 5 challenges, you are ready to claim your certificate!</p>
            <p><a href="graduation.php" class="btn exam-btn">🏁 Go to Graduation</a></p>
        </div>
    </footer>

    <nav class="progress-nav">
        <a href="lab-module5.php" class="btn prev">&lt; Previous: Module 5 (Coverage &amp; QA)</a>
        <a href="graduation.php" class="btn next">Go to Graduation &gt;</a>
    </nav>
</article>

<style>
.final-exam h1 { color: #d9534f; border-bottom: 2px solid #d9534f; padding-bottom: 0.5rem; }
.final-exam .exam-header { text-align: center; margin-bottom: 3rem; }
.final-exam .mission { font-size: 1.5rem; font-weight: bold; color: #333; text-transform: uppercase; letter-spacing: 2px; }
.final-exam .exam-intro { background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 5px solid #d9534f; margin-bottom: 3rem; }
.final-exam .scenario-box { margin-top: 1rem; font-style: italic; color: #555; }
.final-exam h2 { color: #333; margin-top: 3rem; }
.final-exam .code-block { background: #1a1a1a; padding: 1.5rem; border-radius: 4px; margin: 1rem 0; box-shadow: inset 0 0 10px rgba(0,0,0,0.5); }
.final-exam .code-block pre { color: #f8f8f2; margin: 0; }
.final-exam .code-block.broken { border-left: 5px solid #d9534f; }
.final-exam .terminal-block { background: #000; color: #00ff00; padding: 1rem; font-family: 'Consolas', monospace; border-radius: 4px; margin: 1rem 0; }
.final-exam .graduation-cta { background: #fffbeb; border: 2px solid #fde68a; padding: 2rem; border-radius: 8px; text-align: center; margin-top: 4rem; }
.final-exam .exam-btn { background: #d9534f; color: #fff; text-decoration: none; padding: 1rem 2rem; border-radius: 4px; font-weight: bold; display: inline-block; }
.final-exam .exam-btn:hover { background: #c9302c; }
.progress-nav { display: flex; justify-content: space-between; margin-top: 3rem; padding: 2rem 0; border-top: 2px solid #eee; }
.progress-nav .btn { padding: 1rem 1.5rem; border-radius: 4px; text-decoration: none; font-weight: bold; }
.progress-nav .prev { background: #6c757d; color: #fff; }
.progress-nav .next { background: #d9534f; color: #fff; }
@media (max-width: 768px) { .progress-nav { flex-direction: column; gap: 1rem; } }
</style>
