<article class="exam-answers">
    <header class="answers-header">
        <h1>✅ Official Answer Key: Final Exam</h1>
        <p class="subtitle">CMSForNerd v4.3.0 Modernization Curriculum</p>
    </header>

    <div class="answers-intro">
        <p>
            Use this <strong>Official Answer Key</strong> to verify student submissions. 
            Solutions MUST utilize <strong>PHP 8.4 Property Hooks</strong> and maintain 
            <strong>Strict Type Safety</strong> as defined in RFC 2119.
        </p>
    </div>

    <section class="answer-section">
        <h2>✅ Answer 1: Security Breach (Path Traversal)</h2>
        <p><strong>Requirement:</strong> Utilize <code>SecurityUtils</code> for allow-list validation.</p>
        <div class="code-block fixed">
            <pre><code>// v4.3.0 Standard Fix
$requested = $_GET['page'] ?? 'index';
$safePage = \CmsForNerd\SecurityUtils::sanitizePageName($requested);
$contentPath = __DIR__ . "/contents/{$safePage}-body.inc";

include file_exists($contentPath) ? $contentPath : "contents/404-body.inc";</code></pre>
        </div>
    </section>

    <section class="answer-section">
        <h2>✅ Answer 2: Logic Error (Property Hooks)</h2>
        <p><strong>Requirement:</strong> Implement PHP 8.4 Property Hooks with implicit returns.</p>
        <div class="code-block fixed">
            <pre><code>class Project {
    public string $author {
        set => $this->author = trim($value);
        get => strtoupper($this->author); // Implicit return
    }
}</code></pre>
        </div>
    </section>

    <section class="answer-section">
        <h2>✅ Answer 3: PSR-12 Compliance</h2>
        <p><strong>Requirement:</strong> Proper brace placement and strict visibility.</p>
        <div class="code-block fixed">
            <pre><code>class Checker
{
    public function validate(string $data): bool
    {
        return $data === "valid";
    }
}</code></pre>
        </div>
    </section>

    <footer class="grading-rubric">
        <h2>🎓 Certification Rubric</h2>
        <ul>
            <li><strong>MUST:</strong> Pass <code>composer compliance</code> (Green Bar).</li>
            <li><strong>MUST:</strong> Pass <code>composer test</code> with 100% coverage.</li>
            <li><strong>SHOULD:</strong> Demonstrate use of Constructor Property Promotion.</li>
        </ul>
    </footer>
</article>

<style>
    :root { --exam-green: #28a745; --exam-gold: #92400e; }
    .exam-answers { max-width: 850px; margin: 0 auto; line-height: 1.8; }
    .answers-header { text-align: center; border-bottom: 3px double var(--exam-green); padding-bottom: 20px; }
    .answers-intro { background: #f0fdf4; border-left: 5px solid var(--exam-green); padding: 20px; border-radius: 8px; }
    .answer-section { margin: 40px 0; }
    .code-block { background: #1a1a1a; color: #f8f8f2; padding: 20px; border-radius: 6px; font-family: 'Courier New', monospace; border-left: 5px solid var(--exam-green); overflow-x: auto; }
    .grading-rubric { background: #fffbeb; border: 2px solid #fde68a; padding: 30px; border-radius: 12px; margin-top: 50px; }
    .grading-rubric h2 { color: var(--exam-gold); margin-top: 0; }
</style>