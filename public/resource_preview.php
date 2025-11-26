<?php
session_start();

require_once __DIR__ . '/../app/config/config.php';

if (!isset($_SESSION['counselor_id'])) {
    if (isset($_SESSION['user_role'], $_SESSION['user_id']) && $_SESSION['user_role'] === 'counselor') {
        $_SESSION['counselor_id'] = (int) $_SESSION['user_id'];
    } else {
        header('Location: ' . APP_URL . '/auth/login');
        exit;
    }
}

$resourceId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$resources = [
    1 => [
        'title' => 'Ultimate Career Planning Guide 2024',
        'description' => 'Comprehensive guide covering self-assessment, goal setting, and execution frameworks for students entering the workforce.',
        'category' => 'career_guides',
        'file_type' => 'pdf',
        'file_size' => '2.5 MB',
        'downloads' => 245,
        'views' => 1230,
        'is_featured' => true,
        'tags' => 'career,planning,roadmap',
        'external_url' => null,
        'file_path' => '#',
        'created_at' => '2024-12-28',
        'updated_at' => '2025-02-14'
    ],
    2 => [
        'title' => 'Modern Resume Template Bundle',
        'description' => 'ATS-friendly resume and cover letter templates in Word and Google Docs formats.',
        'category' => 'resume_templates',
        'file_type' => 'document',
        'file_size' => '4.8 MB',
        'downloads' => 412,
        'views' => 1540,
        'is_featured' => true,
        'tags' => 'resume,template,ats',
        'external_url' => null,
        'file_path' => '#',
        'created_at' => '2025-01-12',
        'updated_at' => '2025-02-18'
    ],
    3 => [
        'title' => 'Interview Confidence Masterclass',
        'description' => 'On-demand webinar covering behavioral interviews, STAR responses, and negotiation tips.',
        'category' => 'webinars',
        'file_type' => 'video',
        'file_size' => '45 min',
        'downloads' => 96,
        'views' => 875,
        'is_featured' => false,
        'tags' => 'interview,behavioral,negotiation',
        'external_url' => 'https://example.com/webinar',
        'file_path' => null,
        'created_at' => '2025-02-03',
        'updated_at' => '2025-02-17'
    ],
    4 => [
        'title' => 'Career Interest Assessment Worksheet',
        'description' => 'Self-scoring worksheet to help students discover aptitudes and align them with in-demand roles.',
        'category' => 'assessment_tools',
        'file_type' => 'presentation',
        'file_size' => '1.9 MB',
        'downloads' => 183,
        'views' => 639,
        'is_featured' => false,
        'tags' => 'assessment,self-evaluation',
        'external_url' => null,
        'file_path' => '#',
        'created_at' => '2024-11-04',
        'updated_at' => '2025-02-06'
    ],
    5 => [
        'title' => 'Behavioral Interview Cheat Sheet',
        'description' => 'Quick reference for the STAR method with sample prompts students can rehearse.',
        'category' => 'interview_tips',
        'file_type' => 'pdf',
        'file_size' => '980 KB',
        'downloads' => 321,
        'views' => 1072,
        'is_featured' => false,
        'tags' => 'interview,star,preparation',
        'external_url' => null,
        'file_path' => '#',
        'created_at' => '2025-01-22',
        'updated_at' => '2025-02-15'
    ],
    6 => [
        'title' => 'Emerging Tech Careers 2025',
        'description' => 'Curated list of articles and think pieces on fast-growing roles and how to prepare for them.',
        'category' => 'articles',
        'file_type' => 'link',
        'file_size' => 'Reading time: 15 min',
        'downloads' => 57,
        'views' => 624,
        'is_featured' => false,
        'tags' => 'technology,future-of-work',
        'external_url' => 'https://example.com/articles',
        'file_path' => null,
        'created_at' => '2025-02-18',
        'updated_at' => '2025-02-19'
    ]
];

$categoryMeta = [
    'career_guides' => ['label' => 'Career Guides', 'color' => '#10b981', 'icon' => 'fa-compass'],
    'resume_templates' => ['label' => 'Resume Templates', 'color' => '#f59e0b', 'icon' => 'fa-file-lines'],
    'interview_tips' => ['label' => 'Interview Tips', 'color' => '#ef4444', 'icon' => 'fa-comments'],
    'assessment_tools' => ['label' => 'Assessment Tools', 'color' => '#8b5cf6', 'icon' => 'fa-bullseye'],
    'webinars' => ['label' => 'Webinars', 'color' => '#ec4899', 'icon' => 'fa-video'],
    'articles' => ['label' => 'Articles', 'color' => '#06b6d4', 'icon' => 'fa-newspaper']
];

$fileTypeMeta = [
    'pdf' => ['label' => 'PDF', 'icon' => 'fa-file-pdf', 'color' => '#ef4444'],
    'video' => ['label' => 'Video', 'icon' => 'fa-file-video', 'color' => '#8b5cf6'],
    'link' => ['label' => 'External Link', 'icon' => 'fa-external-link-alt', 'color' => '#2563eb'],
    'document' => ['label' => 'Document', 'icon' => 'fa-file-word', 'color' => '#1d4ed8'],
    'image' => ['label' => 'Image', 'icon' => 'fa-file-image', 'color' => '#10b981'],
    'presentation' => ['label' => 'Presentation', 'icon' => 'fa-file-powerpoint', 'color' => '#f59e0b']
];

$resource = $resources[$resourceId] ?? null;

function safe(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $resource ? safe($resource['title']) : 'Resource Not Found' ?> | Resource Preview</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-bxPWQs2Kq8np5xpoE2mR7BfrpsbR9f7D3Dveoxu48UUfZo0fo0RKZ77N8BINU3CFAaj6MqFmoVoe2MktKL7Xlw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #64748b;
            --bg: #f5f6f8;
            --card: #ffffff;
            --border: #e2e8f0;
            --shadow: 0 32px 56px -32px rgba(15, 23, 42, 0.35);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: #0f172a;
        }
        header {
            padding: 2.4rem 1.5rem 2rem;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.95), rgba(29, 78, 216, 0.92));
            color: #fff;
        }
        header a { color: #fff; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; }
        main {
            max-width: 960px;
            margin: -2.5rem auto 3.5rem;
            padding: 0 1.5rem;
        }
        .card {
            background: var(--card);
            border-radius: 22px;
            padding: 2rem;
            box-shadow: var(--shadow);
            border: 1px solid rgba(148, 163, 184, 0.14);
            display: grid;
            gap: 1.5rem;
        }
        .badges { display: flex; gap: 0.75rem; flex-wrap: wrap; }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border-radius: 999px;
            padding: 0.4rem 0.75rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: #fff;
        }
        .meta-grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); }
        .meta-item { background: rgba(37, 99, 235, 0.06); border-radius: 16px; padding: 1rem; display: grid; gap: 0.3rem; }
        .meta-item span { font-size: 0.85rem; color: var(--secondary); }
        .buttons { display: flex; gap: 0.75rem; flex-wrap: wrap; }
        .buttons a, .buttons button {
            border: none;
            border-radius: 12px;
            padding: 0.7rem 1.2rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-secondary { background: rgba(148, 163, 184, 0.16); color: var(--secondary); }
        .btn-outline { background: transparent; border: 1px solid rgba(148, 163, 184, 0.4); color: var(--secondary); }
        .description { margin: 0; line-height: 1.7; color: var(--secondary); }
        .tags { display: flex; gap: 0.55rem; flex-wrap: wrap; }
        .tag { background: rgba(37, 99, 235, 0.12); color: var(--primary); padding: 0.35rem 0.65rem; border-radius: 999px; font-size: 0.82rem; font-weight: 600; }
        .split {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
        }
        iframe { width: 100%; border: none; border-radius: 18px; min-height: 320px; background: rgba(37, 99, 235, 0.05); }
        .placeholder-preview { display: grid; place-items: center; height: 320px; border-radius: 18px; background: rgba(37, 99, 235, 0.08); color: var(--secondary); font-weight: 600; }
        @media (max-width: 860px) {
            .split { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<header>
    <a href="resources.php"><i class="fas fa-arrow-left" aria-hidden="true"></i> Back to resources</a>
    <?php if ($resource): ?>
        <h1 style="margin: 1.6rem 0 0; font-size: 2rem; max-width: 720px;">
            <?= safe($resource['title']) ?>
        </h1>
        <p style="max-width: 640px; margin-top: 0.6rem; line-height: 1.6; opacity: 0.9;">
            <?= safe($resource['description']) ?>
        </p>
    <?php else: ?>
        <h1 style="margin: 1.6rem 0 0;">Resource not found</h1>
        <p style="max-width: 640px; margin-top: 0.6rem; opacity: 0.9;">The requested resource could not be located. It may have been removed or is awaiting publication.</p>
    <?php endif; ?>
</header>

<main>
    <section class="card">
        <?php if ($resource): ?>
            <div class="badges">
                <?php $categoryInfo = $categoryMeta[$resource['category']] ?? null; ?>
                <?php if ($categoryInfo): ?>
                    <span class="badge" style="background: <?= safe($categoryInfo['color']) ?>;">
                        <i class="fas <?= safe($categoryInfo['icon']) ?>" aria-hidden="true"></i>
                        <?= safe($categoryInfo['label']) ?>
                    </span>
                <?php endif; ?>
                <?php $typeInfo = $fileTypeMeta[$resource['file_type']] ?? null; ?>
                <?php if ($typeInfo): ?>
                    <span class="badge" style="background: <?= safe($typeInfo['color']) ?>;">
                        <i class="fas <?= safe($typeInfo['icon']) ?>" aria-hidden="true"></i>
                        <?= safe($typeInfo['label']) ?>
                    </span>
                <?php endif; ?>
                <?php if ($resource['is_featured']): ?>
                    <span class="badge" style="background: rgba(37,99,235,0.25); color: var(--primary);">
                        <i class="fas fa-star" aria-hidden="true"></i> Featured Resource
                    </span>
                <?php endif; ?>
            </div>

            <div class="meta-grid">
                <div class="meta-item">
                    <span>File size</span>
                    <strong><?= safe($resource['file_size']) ?></strong>
                </div>
                <div class="meta-item">
                    <span>Total downloads</span>
                    <strong><?= (int) $resource['downloads'] ?></strong>
                </div>
                <div class="meta-item">
                    <span>Total views</span>
                    <strong><?= (int) $resource['views'] ?></strong>
                </div>
                <div class="meta-item">
                    <span>Last updated</span>
                    <strong><?= safe(date('M j, Y', strtotime($resource['updated_at']))) ?></strong>
                </div>
            </div>

            <div class="buttons">
                <a href="<?= $resource['external_url'] ? safe($resource['external_url']) : '#" onclick="event.preventDefault();"' ?>" class="btn-primary"><i class="fas fa-download" aria-hidden="true"></i> Download</a>
                <?php if ($resource['external_url']): ?>
                    <a href="<?= safe($resource['external_url']) ?>" class="btn-secondary" target="_blank" rel="noopener"><i class="fas fa-external-link-alt" aria-hidden="true"></i> Open link</a>
                <?php endif; ?>
                <button class="btn-outline" type="button" id="copyLink"><i class="fas fa-share-alt" aria-hidden="true"></i> Copy share link</button>
            </div>

            <div class="split">
                <div>
                    <h2 style="margin: 0 0 0.75rem; font-size: 1.2rem;">Overview</h2>
                    <p class="description"><?= safe($resource['description']) ?></p>
                    <?php if (!empty($resource['tags'])): ?>
                        <div style="margin-top: 1.25rem;">
                            <strong style="display: block; margin-bottom: 0.65rem; color: var(--secondary);">Tags</strong>
                            <div class="tags">
                                <?php foreach (array_filter(array_map('trim', explode(',', $resource['tags']))) as $tag): ?>
                                    <span class="tag">#<?= safe($tag) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <aside>
                    <h2 style="margin: 0 0 0.75rem; font-size: 1.2rem;">Preview</h2>
                    <?php if ($resource['file_type'] === 'video' && $resource['external_url']): ?>
                        <iframe src="<?= safe($resource['external_url']) ?>" title="Video preview"></iframe>
                    <?php elseif ($resource['file_type'] === 'link' && $resource['external_url']): ?>
                        <div class="placeholder-preview">External resource preview available at link</div>
                    <?php else: ?>
                        <div class="placeholder-preview">Inline preview pending file storage integration</div>
                    <?php endif; ?>
                </aside>
            </div>
        <?php else: ?>
            <p style="margin: 0;">Please return to the resource library and choose another item.</p>
        <?php endif; ?>
    </section>
</main>

<script>
(function() {
    const copyBtn = document.getElementById('copyLink');
    if (!copyBtn) return;
    copyBtn.addEventListener('click', () => {
        navigator.clipboard.writeText(window.location.href).then(() => {
            copyBtn.textContent = 'Link copied!';
            setTimeout(() => copyBtn.innerHTML = '<i class="fas fa-share-alt" aria-hidden="true"></i> Copy share link', 2200);
        });
    });
})();
</script>
</body>
</html>
