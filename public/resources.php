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

$resources = [
    [
        'id' => 1,
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
        'created_at' => '2024-12-28'
    ],
    [
        'id' => 2,
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
        'created_at' => '2025-01-12'
    ],
    [
        'id' => 3,
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
        'created_at' => '2025-02-03'
    ],
    [
        'id' => 4,
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
        'created_at' => '2024-11-04'
    ],
    [
        'id' => 5,
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
        'created_at' => '2025-01-22'
    ],
    [
        'id' => 6,
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
        'created_at' => '2025-02-18'
    ]
];

$categoryMeta = [
    'career_guides' => ['label' => 'Career Guides', 'color' => '#10b981', 'class' => 'career-guides'],
    'resume_templates' => ['label' => 'Resume Templates', 'color' => '#f59e0b', 'class' => 'resume-templates'],
    'interview_tips' => ['label' => 'Interview Tips', 'color' => '#ef4444', 'class' => 'interview-tips'],
    'assessment_tools' => ['label' => 'Assessment Tools', 'color' => '#8b5cf6', 'class' => 'assessment-tools'],
    'webinars' => ['label' => 'Webinars', 'color' => '#ec4899', 'class' => 'webinars'],
    'articles' => ['label' => 'Articles', 'color' => '#06b6d4', 'class' => 'articles']
];

$fileTypeMeta = [
    'pdf' => ['label' => 'PDF', 'icon' => 'fa-file-pdf', 'class' => 'type-pdf'],
    'video' => ['label' => 'Video', 'icon' => 'fa-file-video', 'class' => 'type-video'],
    'link' => ['label' => 'External Link', 'icon' => 'fa-external-link-alt', 'class' => 'type-link'],
    'document' => ['label' => 'Document', 'icon' => 'fa-file-word', 'class' => 'type-document'],
    'image' => ['label' => 'Image', 'icon' => 'fa-file-image', 'class' => 'type-image'],
    'presentation' => ['label' => 'Presentation', 'icon' => 'fa-file-powerpoint', 'class' => 'type-presentation']
];

$totalResources = count($resources);
$totalDownloads = array_sum(array_map(static fn($item) => (int) $item['downloads'], $resources));
$categoryTotals = [];
foreach ($resources as $resource) {
    $categoryTotals[$resource['category']] = ($categoryTotals[$resource['category']] ?? 0) + (int) $resource['downloads'];
}
arsort($categoryTotals);
$mostPopularCategoryKey = array_key_first($categoryTotals) ?: null;
$mostPopularCategory = $mostPopularCategoryKey && isset($categoryMeta[$mostPopularCategoryKey]) ? $categoryMeta[$mostPopularCategoryKey]['label'] : '—';
$featuredCount = count(array_filter($resources, static fn($item) => $item['is_featured']));

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
    <title>Resource Library | Virtual Career Counseling Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-bxPWQs2Kq8np5xpoE2mR7BfrpsbR9f7D3Dveoxu48UUfZo0fo0RKZ77N8BINU3CFAaj6MqFmoVoe2MktKL7Xlw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --bg: #f5f6f8;
            --card: #ffffff;
            --border: #e2e8f0;
            --muted: #94a3b8;
            --text: #0f172a;
            --shadow: 0 24px 48px -28px rgba(15, 23, 42, 0.25);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
        }
        header {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.95), rgba(29, 78, 216, 0.92));
            color: #fff;
            padding: 2.6rem 1.5rem 2rem;
        }
        header .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        header h1 { margin: 0; font-size: 2rem; }
        header p { margin: 0.35rem 0 0; max-width: 580px; opacity: 0.9; }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            background: #fff;
            color: var(--primary);
            border: none;
            border-radius: 999px;
            padding: 0.65rem 1.25rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
            box-shadow: 0 16px 32px -24px rgba(15, 23, 42, 0.3);
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 16px 36px -24px rgba(37, 99, 235, 0.4); }
        main {
            max-width: 1280px;
            margin: -2.5rem auto 3rem;
            padding: 0 1.5rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 1.1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: var(--card);
            border-radius: 18px;
            padding: 1.35rem;
            box-shadow: var(--shadow);
            display: grid;
            gap: 0.4rem;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            inset: auto -40% -50% auto;
            width: 150px;
            height: 150px;
            background: rgba(37, 99, 235, 0.08);
            border-radius: 50%;
        }
        .stat-label { font-size: 0.9rem; color: var(--secondary); font-weight: 600; }
        .stat-value { font-size: 2rem; font-weight: 700; }
        .stat-sub { font-size: 0.85rem; color: var(--muted); }
        .layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 1.5rem;
        }
        .filters {
            background: var(--card);
            border-radius: 18px;
            box-shadow: var(--shadow);
            padding: 1.5rem;
            display: grid;
            gap: 1.5rem;
            position: sticky;
            top: 1.5rem;
            height: fit-content;
        }
        .filters h2 { margin: 0; font-size: 1.1rem; }
        .search-box input {
            width: 100%;
            padding: 0.65rem 0.75rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            font-size: 0.95rem;
        }
        .filter-group { display: grid; gap: 0.4rem; }
        .filter-group strong { font-size: 0.92rem; color: var(--secondary); }
        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(99, 102, 241, 0.08);
            border-radius: 999px;
            padding: 0.4rem 0.75rem;
            cursor: pointer;
            font-size: 0.85rem;
            transition: background 0.18s ease;
        }
        .filter-chip.active { background: rgba(37, 99, 235, 0.16); color: var(--primary); }
        .filter-chip input { display: none; }
        .sort-select {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            font-size: 0.95rem;
        }
        .resource-grid {
            display: grid;
            gap: 1.2rem;
        }
        .resource-grid.empty::before {
            content: 'No resources match your filters yet.';
            display: block;
            padding: 3rem 1rem;
            background: var(--card);
            border-radius: 18px;
            text-align: center;
            color: var(--secondary);
            box-shadow: var(--shadow);
        }
        .resource-card {
            background: var(--card);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            display: grid;
            gap: 1.1rem;
            border: 1px solid rgba(148, 163, 184, 0.14);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .resource-card:hover { transform: translateY(-4px); box-shadow: 0 32px 48px -28px rgba(37, 99, 235, 0.35); }
        .resource-header { display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
        .file-type-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            color: #fff;
        }
        .type-pdf { background: #ef4444; }
        .type-video { background: #8b5cf6; }
        .type-link { background: #2563eb; }
        .type-document { background: #1d4ed8; }
        .type-image { background: #10b981; }
        .type-presentation { background: #f59e0b; }
        .resource-meta { display: flex; gap: 0.55rem; align-items: center; flex-wrap: wrap; }
        .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 999px;
            padding: 0.35rem 0.7rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #fff;
        }
        .featured-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(37, 99, 235, 0.16);
            color: var(--primary);
            border-radius: 999px;
            padding: 0.3rem 0.7rem;
            font-weight: 600;
            font-size: 0.78rem;
        }
        .resource-title { margin: 0; font-size: 1.25rem; }
        .resource-desc { margin: 0.3rem 0 0; color: var(--secondary); line-height: 1.6; }
        .resource-stats {
            display: flex;
            gap: 1.25rem;
            flex-wrap: wrap;
            color: var(--secondary);
            font-size: 0.9rem;
            font-weight: 600;
        }
        .resource-actions {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
        }
        .resource-actions button {
            flex: 1 0 140px;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
        .btn-preview { background: rgba(37, 99, 235, 0.1); color: var(--primary); }
        .btn-download { background: var(--primary); color: #fff; }
        .btn-share { background: rgba(148, 163, 184, 0.16); color: var(--secondary); }
        .resource-actions button:hover { transform: translateY(-1px); box-shadow: 0 12px 20px -16px rgba(15, 23, 42, 0.35); }
        .bulk-bar {
            background: rgba(37, 99, 235, 0.12);
            border-radius: 16px;
            padding: 0.8rem 1rem;
            display: none;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .bulk-bar.active { display: flex; }
        .bulk-bar button {
            border: none;
            background: transparent;
            color: var(--primary);
            font-weight: 600;
            cursor: pointer;
        }
        dialog {
            border: none;
            border-radius: 24px;
            padding: 0;
            width: min(640px, 92%);
            box-shadow: 0 40px 80px -32px rgba(15, 23, 42, 0.45);
        }
        dialog::backdrop { backdrop-filter: blur(6px); background: rgba(15, 23, 42, 0.55); }
        .modal {
            padding: 1.8rem;
            display: grid;
            gap: 1.25rem;
            background: var(--card);
        }
        .modal header { padding: 0; background: transparent; color: var(--text); }
        .upload-drop {
            border: 2px dashed rgba(37, 99, 235, 0.3);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            background: rgba(37, 99, 235, 0.05);
            transition: border-color 0.18s ease, background 0.18s ease;
        }
        .upload-drop.active { border-color: var(--primary); background: rgba(37, 99, 235, 0.12); }
        .upload-grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
        .upload-grid label { font-weight: 600; font-size: 0.9rem; color: var(--secondary); display: block; margin-bottom: 0.3rem; }
        .upload-grid input, .upload-grid select, .upload-grid textarea {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            font-size: 0.95rem;
        }
        textarea { min-height: 120px; resize: vertical; }
        .modal footer { display: flex; gap: 0.6rem; justify-content: flex-end; }
        .toast {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            background: var(--card);
            box-shadow: 0 24px 48px -28px rgba(15, 23, 42, 0.35);
            padding: 1rem 1.25rem;
            border-radius: 16px;
            display: none;
            gap: 0.75rem;
            align-items: center;
            border-left: 4px solid var(--primary);
        }
        .toast.active { display: flex; }
        @media (max-width: 1024px) {
            .layout { grid-template-columns: 1fr; }
            .filters { position: static; }
        }
        @media (max-width: 640px) {
            header .top-bar { flex-direction: column; align-items: flex-start; }
            .resource-actions { flex-direction: column; }
            .bulk-bar { flex-direction: column; gap: 0.75rem; align-items: flex-start; }
        }
    </style>
</head>
<body>
<header>
    <div class="top-bar">
        <div>
            <h1>Resource Library</h1>
            <p>Share curated guides, templates, and tools with your students. Organize, track engagement, and surface featured materials effortlessly.</p>
        </div>
        <div>
            <button class="btn-primary" id="openUpload"><i class="fas fa-cloud-upload-alt"></i>Add Resource</button>
        </div>
    </div>
</header>

<main>
    <section class="stats-grid" aria-label="Resource statistics">
        <article class="stat-card">
            <span class="stat-label">Total Resources</span>
            <span class="stat-value" id="statTotalResources"><?= $totalResources ?></span>
            <span class="stat-sub">Including <?= $featuredCount ?> featured items</span>
        </article>
        <article class="stat-card">
            <span class="stat-label">Total Downloads</span>
            <span class="stat-value" id="statTotalDownloads"><?= $totalDownloads ?></span>
            <span class="stat-sub">Across all available assets</span>
        </article>
        <article class="stat-card">
            <span class="stat-label">Most Popular Category</span>
            <span class="stat-value" style="font-size: 1.4rem;">
                <?= safe($mostPopularCategory) ?>
            </span>
            <span class="stat-sub">Based on download volume</span>
        </article>
        <article class="stat-card">
            <span class="stat-label">Featured Resources</span>
            <span class="stat-value" id="statFeatured"><?= $featuredCount ?></span>
            <span class="stat-sub">Highlighted for quick access</span>
        </article>
    </section>

    <div class="layout">
        <aside class="filters" aria-label="Resource filters">
            <div class="search-box">
                <label for="searchInput" style="font-weight: 600; color: var(--secondary); font-size: 0.9rem;">Search resources</label>
                <input type="search" id="searchInput" placeholder="Search by title, description, or tags">
            </div>
            <div class="filter-group" id="categoryFilters">
                <strong>Categories</strong>
                <?php foreach ($categoryMeta as $key => $meta): ?>
                    <label class="filter-chip" data-filter="category" data-value="<?= safe($key) ?>" style="background: rgba(<?= hexdec(substr($meta['color'], 1, 2)) ?>, <?= hexdec(substr($meta['color'], 3, 2)) ?>, <?= hexdec(substr($meta['color'], 5, 2)) ?>, 0.14); color: <?= safe($meta['color']) ?>;">
                        <input type="checkbox" value="<?= safe($key) ?>">
                        <?= safe($meta['label']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <div class="filter-group" id="typeFilters">
                <strong>File Types</strong>
                <?php foreach ($fileTypeMeta as $key => $meta): ?>
                    <label class="filter-chip" data-filter="type" data-value="<?= safe($key) ?>">
                        <input type="checkbox" value="<?= safe($key) ?>">
                        <i class="fas <?= safe($meta['icon']) ?>" aria-hidden="true"></i>
                        <?= safe($meta['label']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <div class="filter-group">
                <strong>Featured</strong>
                <label class="filter-chip" data-filter="featured" data-value="1">
                    <input type="checkbox" value="1">
                    <i class="fas fa-star"></i> Featured only
                </label>
            </div>
            <div>
                <label for="sortSelect" style="font-weight: 600; color: var(--secondary); font-size: 0.9rem;">Sort by</label>
                <select id="sortSelect" class="sort-select">
                    <option value="recent">Most recent</option>
                    <option value="downloads">Downloads</option>
                    <option value="views">Views</option>
                    <option value="title">Title (A-Z)</option>
                </select>
            </div>
        </aside>

        <section>
            <div class="bulk-bar" id="bulkBar">
                <span id="bulkCount">0 selected</span>
                <div>
                    <button type="button" id="bulkFeature">Mark as featured</button>
                    <button type="button" id="bulkDelete">Delete</button>
                </div>
            </div>
            <div class="resource-grid" id="resourceGrid">
                <?php foreach ($resources as $resource):
                    $category = $resource['category'];
                    $fileType = $resource['file_type'];
                    $categoryInfo = $categoryMeta[$category] ?? null;
                    $typeInfo = $fileTypeMeta[$fileType] ?? null;
                ?>
                    <article class="resource-card" data-resource-id="<?= (int) $resource['id'] ?>" data-category="<?= safe($category) ?>" data-type="<?= safe($fileType) ?>" data-featured="<?= $resource['is_featured'] ? '1' : '0' ?>" data-title="<?= safe(strtolower($resource['title'])) ?>" data-tags="<?= safe(strtolower($resource['tags'])) ?>" data-description="<?= safe(strtolower($resource['description'])) ?>">
                        <header class="resource-header">
                            <div class="file-type-icon <?= safe($typeInfo['class'] ?? '') ?>">
                                <i class="fas <?= safe($typeInfo['icon'] ?? 'fa-file-alt') ?>" aria-hidden="true"></i>
                            </div>
                            <div class="resource-meta">
                                <?php if ($categoryInfo): ?>
                                    <span class="category-badge" style="background: <?= safe($categoryInfo['color']) ?>;">
                                        <?= safe($categoryInfo['label']) ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($resource['is_featured']): ?>
                                    <span class="featured-badge"><i class="fas fa-star" aria-hidden="true"></i> Featured</span>
                                <?php endif; ?>
                                <label style="margin-left: auto; display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.85rem; color: var(--muted);">
                                    <input type="checkbox" class="select-resource" value="<?= (int) $resource['id'] ?>">
                                    Select
                                </label>
                            </div>
                        </header>
                        <div class="resource-content">
                            <h3 class="resource-title"><?= safe($resource['title']) ?></h3>
                            <p class="resource-desc"><?= safe($resource['description']) ?></p>
                            <div class="resource-stats">
                                <span><i class="fas fa-download" aria-hidden="true"></i> <?= (int) $resource['downloads'] ?></span>
                                <span><i class="fas fa-eye" aria-hidden="true"></i> <?= (int) $resource['views'] ?></span>
                                <span><i class="fas fa-database" aria-hidden="true"></i> <?= safe($resource['file_size']) ?></span>
                                <span><i class="fas fa-clock" aria-hidden="true"></i> Added <?= safe(date('M j, Y', strtotime($resource['created_at']))) ?></span>
                            </div>
                        </div>
                        <footer class="resource-actions">
                            <button class="btn-preview" data-action="preview" data-id="<?= (int) $resource['id'] ?>">Preview</button>
                            <button class="btn-download" data-action="download" data-id="<?= (int) $resource['id'] ?>">Download</button>
                            <button class="btn-share" data-action="share" data-id="<?= (int) $resource['id'] ?>">Share</button>
                        </footer>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</main>

<dialog id="uploadModal">
    <form class="modal" id="uploadForm" method="POST" action="upload_resource.php" enctype="multipart/form-data">
        <header>
            <h2 style="margin: 0; font-size: 1.4rem;">Add new resource</h2>
            <p style="margin: 0.35rem 0 0; color: var(--secondary);">Upload files or link to external content to keep your students up to date.</p>
        </header>
        <div class="upload-drop" id="uploadDrop">
            <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: var(--primary);"></i>
            <p style="margin: 0.75rem 0 0; font-weight: 600;">Drag & drop files here, or click to browse</p>
            <p style="margin: 0.25rem 0 0; color: var(--secondary); font-size: 0.9rem;">PDF, PPT, DOCX, video, and image files supported.</p>
            <input type="file" id="uploadInput" name="resource_file" style="display: none;">
        </div>
        <div class="upload-grid">
            <div>
                <label for="resourceTitle">Title *</label>
                <input type="text" id="resourceTitle" name="title" required>
            </div>
            <div>
                <label for="resourceCategory">Category *</label>
                <select id="resourceCategory" name="category" required>
                    <option value="">Select category</option>
                    <?php foreach ($categoryMeta as $key => $meta): ?>
                        <option value="<?= safe($key) ?>"><?= safe($meta['label']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="resourceType">File type *</label>
                <select id="resourceType" name="file_type" required>
                    <option value="">Select file type</option>
                    <?php foreach ($fileTypeMeta as $key => $meta): ?>
                        <option value="<?= safe($key) ?>"><?= safe($meta['label']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="resourceUrl">External URL</label>
                <input type="url" id="resourceUrl" name="external_url" placeholder="https://">
            </div>
            <div>
                <label for="resourceTags">Tags</label>
                <input type="text" id="resourceTags" name="tags" placeholder="Separate tags with commas">
            </div>
            <div>
                <label for="resourceFeatured">Feature this resource</label>
                <select id="resourceFeatured" name="is_featured">
                    <option value="0" selected>No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="resourceDescription">Description *</label>
                <textarea id="resourceDescription" name="description" required></textarea>
            </div>
        </div>
        <footer>
            <button type="button" class="btn-share" id="closeUpload">Cancel</button>
            <button type="submit" class="btn-download" id="submitUpload">Upload</button>
        </footer>
    </form>
</dialog>

<div class="toast" id="toast" role="status" aria-live="polite">
    <i class="fas fa-circle-check" style="color: var(--primary);" aria-hidden="true"></i>
    <span id="toastMessage">Action completed</span>
</div>

<script>
(function() {
    const searchInput = document.getElementById('searchInput');
    const resourceGrid = document.getElementById('resourceGrid');
    const sortSelect = document.getElementById('sortSelect');
    const filterChips = Array.from(document.querySelectorAll('.filter-chip'));
    const cards = Array.from(document.querySelectorAll('.resource-card'));
    const bulkBar = document.getElementById('bulkBar');
    const bulkCount = document.getElementById('bulkCount');
    const bulkFeature = document.getElementById('bulkFeature');
    const bulkDelete = document.getElementById('bulkDelete');
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');

    const activeFilters = { category: new Set(), type: new Set(), featured: false };

    function updateGridVisibility() {
        const query = searchInput.value.trim().toLowerCase();
        const sortValue = sortSelect.value;
        const filtered = cards.filter(card => {
            const matchesCategory = activeFilters.category.size === 0 || activeFilters.category.has(card.dataset.category);
            const matchesType = activeFilters.type.size === 0 || activeFilters.type.has(card.dataset.type);
            const matchesFeatured = !activeFilters.featured || card.dataset.featured === '1';
            const haystack = `${card.dataset.title} ${card.dataset.description} ${card.dataset.tags}`;
            const matchesQuery = haystack.includes(query);
            return matchesCategory && matchesType && matchesFeatured && matchesQuery;
        });
        resourceGrid.classList.toggle('empty', filtered.length === 0);
        cards.forEach(card => card.style.display = 'none');
        filtered.forEach(card => card.style.display = 'grid');
        applySort(filtered, sortValue);
    }

    function applySort(filteredCards, criterion) {
        const sorted = [...filteredCards].sort((a, b) => {
            if (criterion === 'downloads') {
                return parseInt(b.querySelector('.fa-download').nextSibling.textContent) - parseInt(a.querySelector('.fa-download').nextSibling.textContent);
            }
            if (criterion === 'views') {
                return parseInt(b.querySelector('.fa-eye').nextSibling.textContent) - parseInt(a.querySelector('.fa-eye').nextSibling.textContent);
            }
            if (criterion === 'title') {
                return a.dataset.title.localeCompare(b.dataset.title);
            }
            const dateA = Date.parse(a.querySelector('.fa-clock').parentElement.textContent.replace('Added', '').trim()) || 0;
            const dateB = Date.parse(b.querySelector('.fa-clock').parentElement.textContent.replace('Added', '').trim()) || 0;
            return dateB - dateA;
        });
        sorted.forEach(card => resourceGrid.appendChild(card));
    }

    filterChips.forEach(chip => {
        chip.addEventListener('click', () => {
            const filterType = chip.dataset.filter;
            const value = chip.dataset.value;
            if (filterType === 'featured') {
                chip.classList.toggle('active');
                activeFilters.featured = chip.classList.contains('active');
            } else {
                const set = activeFilters[filterType];
                if (chip.classList.toggle('active')) {
                    set.add(value);
                } else {
                    set.delete(value);
                }
            }
            updateGridVisibility();
        });
    });

    searchInput.addEventListener('input', updateGridVisibility);
    sortSelect.addEventListener('change', () => updateGridVisibility());

    const modal = document.getElementById('uploadModal');
    const openUpload = document.getElementById('openUpload');
    const closeUpload = document.getElementById('closeUpload');
    const uploadDrop = document.getElementById('uploadDrop');
    const uploadInput = document.getElementById('uploadInput');
    const uploadForm = document.getElementById('uploadForm');

    openUpload.addEventListener('click', () => modal.showModal());
    closeUpload.addEventListener('click', () => modal.close());
    modal.addEventListener('cancel', event => { event.preventDefault(); modal.close(); });

    uploadDrop.addEventListener('click', () => uploadInput.click());
    uploadDrop.addEventListener('dragover', event => { event.preventDefault(); uploadDrop.classList.add('active'); });
    uploadDrop.addEventListener('dragleave', () => uploadDrop.classList.remove('active'));
    uploadDrop.addEventListener('drop', event => {
        event.preventDefault();
        uploadDrop.classList.remove('active');
        if (event.dataTransfer.files.length) {
            uploadInput.files = event.dataTransfer.files;
            showToast(`${event.dataTransfer.files[0].name} ready to upload`);
        }
    });

    uploadForm.addEventListener('submit', event => {
        event.preventDefault();
        showToast('Upload queued. Database integration pending.');
        modal.close();
        uploadForm.reset();
    });

    function refreshBulkBar() {
        const selected = document.querySelectorAll('.select-resource:checked');
        bulkCount.textContent = `${selected.length} selected`;
        bulkBar.classList.toggle('active', selected.length > 0);
    }

    document.querySelectorAll('.select-resource').forEach(checkbox => {
        checkbox.addEventListener('change', refreshBulkBar);
    });

    bulkFeature.addEventListener('click', () => {
        showToast('Selected resources marked as featured.');
        bulkBar.classList.remove('active');
        document.querySelectorAll('.select-resource:checked').forEach(cb => cb.checked = false);
    });

    bulkDelete.addEventListener('click', () => {
        showToast('Selected resources removed.');
        bulkBar.classList.remove('active');
        document.querySelectorAll('.select-resource:checked').forEach(cb => {
            cb.closest('.resource-card').remove();
        });
        updateGridVisibility();
    });

    resourceGrid.addEventListener('click', event => {
        const action = event.target.dataset.action;
        const card = event.target.closest('.resource-card');
        if (!action || !card) return;
        const resourceId = card.dataset.resourceId;
        if (action === 'preview') {
            window.location.href = `resource_preview.php?id=${resourceId}`;
        }
        if (action === 'download') {
            showToast('Download action triggered. Tracking pending integration.');
        }
        if (action === 'share') {
            navigator.clipboard.writeText(window.location.origin + `/public/resource_preview.php?id=${resourceId}`).then(() => {
                showToast('Resource link copied to clipboard.');
            });
        }
    });

    function showToast(message) {
        toastMessage.textContent = message;
        toast.classList.add('active');
        setTimeout(() => toast.classList.remove('active'), 2800);
    }

    updateGridVisibility();
})();
</script>
</body>
</html>
