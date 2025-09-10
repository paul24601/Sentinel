<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Wizard Demo | Sentinel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .demo-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-radius: 20px; }
        .feature-card { transition: transform 0.3s ease; }
        .feature-card:hover { transform: translateY(-5px); }
        .gradient-text { background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="demo-card p-5 shadow-lg">
                    <div class="text-center mb-5">
                        <h1 class="gradient-text fw-bold">🚀 Mobile Parameter Wizard</h1>
                        <p class="lead">Transform your parameter entry into a modern, mobile-friendly experience</p>
                        <a href="mobile_wizard.php" class="btn btn-primary btn-lg px-5 py-3 rounded-pill">
                            <i class="bi bi-play-fill"></i> Try the Wizard
                        </a>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <div class="feature-card card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="bi bi-phone text-primary" style="font-size: 3rem;"></i>
                                    </div>
                                    <h5>Mobile-First Design</h5>
                                    <p class="text-muted">Optimized for smartphones and tablets with touch-friendly controls</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-card card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="bi bi-list-task text-success" style="font-size: 3rem;"></i>
                                    </div>
                                    <h5>Step-by-Step Guide</h5>
                                    <p class="text-muted">Break complex forms into manageable, bite-sized steps</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-card card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="bi bi-cloud-check text-info" style="font-size: 3rem;"></i>
                                    </div>
                                    <h5>Auto-Save Progress</h5>
                                    <p class="text-muted">Never lose your work with automatic progress saving</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h3 class="fw-bold mb-3">Why Mobile Wizard?</h3>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Faster Entry:</strong> Complete forms 3x faster on mobile</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Better UX:</strong> Duolingo-style interface that users love</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Less Errors:</strong> Real-time validation prevents mistakes</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Resume Anywhere:</strong> Start on phone, finish on desktop</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Zero Training:</strong> Intuitive interface needs no explanation</li>
                            </ul>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="position-relative d-inline-block">
                                <div class="bg-primary rounded-4 p-4" style="transform: rotate(-5deg);">
                                    <i class="bi bi-phone text-white" style="font-size: 4rem;"></i>
                                </div>
                                <div class="bg-success rounded-4 p-3 position-absolute" style="top: -10px; right: -10px; transform: rotate(15deg);">
                                    <i class="bi bi-check-lg text-white" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5 pt-4 border-top">
                        <h4>Ready to revolutionize your parameter entry?</h4>
                        <p class="text-muted mb-4">Join the mobile-first movement in industrial data collection</p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="mobile_wizard.php" class="btn btn-primary btn-lg">
                                <i class="bi bi-rocket-takeoff"></i> Start Wizard
                            </a>
                            <a href="index.php" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-left"></i> Back to Traditional Form
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
