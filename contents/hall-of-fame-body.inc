<?php

/**
 * Nerd Hall of Fame Body
 * Featuring the Architects of the v4.3.0 Modernization.
 */

$contributors = [
    [
        'name' => 'LinuxMalaysia',
        'date' => '2026-01-04',
        'fix'  => 'Project Lead & v4.3.0 Architecture'
    ],
    [
        'name' => 'Google Gemini',
        'date' => '2026-01-04',
        'fix'  => 'Modernization Engine & AI Thought Partner'
    ]
];
?>

<article class="hall-of-fame">
    <header class="fame-header">
        <h1>🏆 Nerd Hall of Fame</h1>
        <p>Recognition is the currency of the open-source world. We honor those who architect, secure, and evolve this laboratory.</p>
    </header>

    <div class="contributor-grid">
        <?php foreach ($contributors as $c) : ?>
            <section class="contributor-card">
                <div class="card-content">
                    <span class="name"><?= htmlspecialchars($c['name']) ?></span>
                    <span class="achievement"><?= htmlspecialchars($c['fix']) ?></span>
                </div>
                <div class="card-footer">
                    <time><?= htmlspecialchars($c['date']) ?></time>
                </div>
            </section>
        <?php endforeach; ?>
    </div>

    <aside class="nomination-box">
        <h3>Join the Ranks</h3>
        <p>
            Identified a vulnerability or optimized the core? Follow our 
            <a href="security-policy.php">Security Policy</a>. Ethical disclosures 
            earn a permanent place in the Hall of Fame.
        </p>
    </aside>

    <nav class="footer-nav">
        <a href="security-policy.php" class="btn btn-secondary">Review Security Policy</a>
        <a href="index.php" class="btn btn-primary">Back to Home</a>
    </nav>
</article>

<style>
    :root { --fame-gold: #f39c12; --fame-dark: #2c3e50; --fame-bg: #fffcf0; }
    
    .hall-of-fame { max-width: 900px; margin: 0 auto; padding: 20px; }
    .fame-header h1 { color: var(--fame-gold); border-bottom: 3px solid var(--fame-gold); font-size: 2.5rem; }
    
    .contributor-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 40px 0; }
    
    .contributor-card { 
        background: white; 
        border: 1px solid #e1e1e1; 
        border-radius: 12px; 
        overflow: hidden; 
        transition: transform 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .contributor-card:hover { transform: translateY(-5px); border-color: var(--fame-gold); }
    
    .card-content { padding: 25px; text-align: center; }
    .name { display: block; font-size: 1.4rem; font-weight: bold; color: var(--fame-dark); margin-bottom: 10px; }
    .achievement { display: block; color: #666; font-style: italic; }
    
    .card-footer { background: var(--fame-bg); padding: 10px; text-align: center; border-top: 1px solid #eee; font-size: 0.85rem; color: #888; }
    
    .nomination-box { background: var(--fame-bg); border: 2px dashed var(--fame-gold); padding: 30px; border-radius: 15px; text-align: center; margin-top: 50px; }
    .nomination-box h3 { color: #856404; margin-top: 0; }
    
    .footer-nav { text-align: center; margin-top: 50px; }
    .btn { padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; margin: 5px; }
    .btn-primary { background: var(--fame-gold); color: white; }
    .btn-secondary { background: #eee; color: #333; }
</style>
