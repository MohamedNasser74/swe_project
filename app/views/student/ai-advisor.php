<?php ob_start(); ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-5 text-center bg-light rounded-top">
                    <i class="fas fa-robot fa-4x text-primary mb-3"></i>
                    <h2 class="fw-bold text-primary">AI Career Path Predictor</h2>
                    <p class="lead text-muted">Discover your ideal career path based on your skills.</p>
                </div>
                <div class="card-body p-5">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="<?= APP_URL ?>/student/aiAdvisor">
                        <div class="mb-4">
                            <label for="skills" class="form-label fw-bold">Enter your skills (separated by spaces or commas):</label>
                            <textarea class="form-control form-control-lg" id="skills" name="skills" rows="3" 
                                      placeholder="e.g. PHP MySQL HTML CSS JavaScript Git Communication Leadership"
                                      required><?= htmlspecialchars($user_skills ?? '') ?></textarea>
                            <div class="form-text">The model analyzes these keywords to classify your profile.</div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-magic me-2"></i>Analyze My Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (isset($prediction)): ?>
                <div class="card shadow border-0 overflow-hidden">
                    <div class="card-header bg-success text-white p-4 text-center">
                        <h5 class="mb-0 text-uppercase letter-spacing-1">Top Recommendation</h5>
                        <h1 class="display-4 fw-bold mt-2 mb-0"><?= htmlspecialchars($prediction) ?></h1>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="mb-3 border-bottom pb-2">Confidence Scores</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50%">Career Path</th>
                                        <th>Match Probability</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($top_matches as $role => $score): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($role) ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                    <div class="progress-bar <?= $score > 80 ? 'bg-success' : ($score > 50 ? 'bg-info' : 'bg-warning') ?>" 
                                                         role="progressbar" 
                                                         style="width: <?= $score ?>%" 
                                                         aria-valuenow="<?= $score ?>" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100"></div>
                                                </div>
                                                <span class="small fw-bold"><?= $score ?>%</span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($score > 80): ?>
                                                <span class="badge bg-success">Excellent Match</span>
                                            <?php elseif ($score > 50): ?>
                                                <span class="badge bg-info text-dark">Good Fit</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Moderate</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-info mt-4 mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>How this works:</strong> This prediction uses a Naive Bayes classifier trained on career data. It calculates the conditional probability of each job role given your specific set of skills.
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
