<?php ob_start(); ?>

<section class="py-5 bg-gradient-primary text-white learn-hero">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <p class="text-uppercase fw-semibold mb-2">Future-ready coaching</p>
                <h1 class="display-4 fw-bold mb-4"><?= $page_title ?? 'Learn More' ?></h1>
                <p class="lead mb-4">
                    We built <?= APP_NAME ?> so students can access the same quality of coaching,
                    resources, and accountability that top professionals enjoy—fully online and
                    on their schedule.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= APP_URL ?>/auth/register" class="btn btn-light btn-lg text-primary">Create Free Account</a>
                    <a href="<?= APP_URL ?>/auth/login" class="btn btn-outline-light btn-lg">Already have access?</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-4 bg-white text-dark learn-hero-card">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Impact snapshot</h5>
                        <div class="row g-3">
                            <?php foreach (($impact_metrics ?? []) as $metric): ?>
                                <div class="col-6">
                                    <div class="impact-card border rounded-4 p-3 h-100">
                                        <div class="h2 fw-bold text-primary mb-1"><?= $metric['value'] ?></div>
                                        <p class="text-muted small mb-0"><?= $metric['label'] ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill text-uppercase">Platform pillars</span>
            <h2 class="display-6 fw-semibold mt-3 mb-3">Everything you need in one workspace</h2>
            <p class="text-muted lead mb-0">We removed the friction between students, counselors, and institutions so everyone sees progress in real time.</p>
        </div>
        <div class="row g-4">
            <?php foreach (($pillars ?? []) as $pillar): ?>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm pillar-card">
                        <div class="card-body p-4">
                            <div class="icon-circle mb-4">
                                <i class="fas <?= $pillar['icon'] ?>"></i>
                            </div>
                            <h5 class="fw-bold mb-3"><?= $pillar['title'] ?></h5>
                            <p class="text-muted mb-0"><?= $pillar['body'] ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-5">
                <p class="text-uppercase fw-semibold text-primary mb-2">Student journey</p>
                <h2 class="fw-bold mb-3">Built for clarity, feedback, and measurable outcomes</h2>
                <p class="text-muted mb-4">
                    Our workflow keeps counselors proactive and students motivated. Every milestone, document,
                    and conversation lives in one secure timeline so nothing slips through the cracks.
                </p>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Session agendas auto-sync to student dashboards</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Progress alerts keep parents and admins informed</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Analytics show which interventions drive results</li>
                </ul>
            </div>
            <div class="col-lg-7">
                <div class="timeline-card rounded-4 p-4 bg-white shadow-sm">
                    <?php foreach (($journey_steps ?? []) as $index => $step): ?>
                        <div class="timeline-step d-flex py-3">
                            <div class="timeline-marker me-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary"><?= $step['label'] ?></span>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1"><?= $step['title'] ?></h5>
                                <p class="text-muted mb-0"><?= $step['text'] ?></p>
                            </div>
                        </div>
                        <?php if ($index < count($journey_steps) - 1): ?>
                            <hr class="my-2 opacity-25">
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <span class="badge bg-success bg-opacity-10 text-success mb-3 rounded-pill px-3 py-2">Trusted by teams</span>
                        <h3 class="fw-bold mb-3">Counselor dashboard highlights</h3>
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex">
                                <div class="me-3 text-success"><i class="fas fa-bell"></i></div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Smart nudges</h6>
                                    <p class="text-muted mb-0">Automated reminders prompt counselors when a student misses a milestone or needs follow-up.</p>
                                </div>
                            </li>
                            <li class="mb-3 d-flex">
                                <div class="me-3 text-primary"><i class="fas fa-chart-line"></i></div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Outcome analytics</h6>
                                    <p class="text-muted mb-0">Track success by cohort, counselor, or program to prove ROI to stakeholders.</p>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="me-3 text-warning"><i class="fas fa-shield-alt"></i></div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Compliance-ready notes</h6>
                                    <p class="text-muted mb-0">Granular permissions and immutable logs ensure every interaction stays audit ready.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="accordion" id="learnMoreFaq">
                    <?php foreach (($faqs ?? []) as $idx => $faq): ?>
                        <?php $collapseId = 'faqItem' . $idx; ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?= $idx ?>">
                                <button class="accordion-button <?= $idx === 0 ? '' : 'collapsed' ?>" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>"
                                        aria-expanded="<?= $idx === 0 ? 'true' : 'false' ?>" aria-controls="<?= $collapseId ?>">
                                    <?= $faq['question'] ?>
                                </button>
                            </h2>
                            <div id="<?= $collapseId ?>" class="accordion-collapse collapse <?= $idx === 0 ? 'show' : '' ?>"
                                 aria-labelledby="heading<?= $idx ?>" data-bs-parent="#learnMoreFaq">
                                <div class="accordion-body">
                                    <?= $faq['answer'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-3">Ready to give your students a competitive edge?</h2>
                <p class="mb-0 lead">Launch a pilot in under a week. We provide onboarding, change management resources, and success coaches.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <a href="<?= APP_URL ?>/home/contact" class="btn btn-light btn-lg text-primary me-2">Talk to us</a>
                <a href="<?= APP_URL ?>/auth/register" class="btn btn-outline-light btn-lg">Start pilot</a>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>

