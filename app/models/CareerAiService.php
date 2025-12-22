<?php

class CareerAiService {
    private $trainingData = [];
    private $classProbabilities = [];
    private $featureProbabilities = [];
    private $vocabulary = [];

    public function __construct() {
        // Expanded training dataset for better accuracy
        // Each role has multiple training samples with distinct skill combinations
        $this->train([
            // ============ FRONTEND DEVELOPER ============
            // Primary skills: HTML, CSS, JavaScript, React, Vue, Angular, UI frameworks
            ['skills' => 'html css javascript', 'role' => 'Frontend Developer'],
            ['skills' => 'html css', 'role' => 'Frontend Developer'],
            ['skills' => 'html css bootstrap tailwind', 'role' => 'Frontend Developer'],
            ['skills' => 'javascript react redux', 'role' => 'Frontend Developer'],
            ['skills' => 'javascript vue vuex', 'role' => 'Frontend Developer'],
            ['skills' => 'javascript angular typescript', 'role' => 'Frontend Developer'],
            ['skills' => 'react nextjs javascript', 'role' => 'Frontend Developer'],
            ['skills' => 'vue nuxt javascript', 'role' => 'Frontend Developer'],
            ['skills' => 'html css sass scss responsive', 'role' => 'Frontend Developer'],
            ['skills' => 'javascript jquery html css', 'role' => 'Frontend Developer'],
            ['skills' => 'frontend webpack babel npm', 'role' => 'Frontend Developer'],
            ['skills' => 'css grid flexbox animations', 'role' => 'Frontend Developer'],
            ['skills' => 'react hooks context api', 'role' => 'Frontend Developer'],
            ['skills' => 'html5 css3 es6 javascript', 'role' => 'Frontend Developer'],
            ['skills' => 'svelte javascript css', 'role' => 'Frontend Developer'],

            // ============ BACKEND DEVELOPER ============
            // Primary skills: PHP, Python, Java, Node.js, databases, APIs, server-side
            ['skills' => 'php mysql laravel', 'role' => 'Backend Developer'],
            ['skills' => 'php mysql api rest', 'role' => 'Backend Developer'],
            ['skills' => 'python django postgresql', 'role' => 'Backend Developer'],
            ['skills' => 'python flask sqlalchemy', 'role' => 'Backend Developer'],
            ['skills' => 'java spring springboot hibernate', 'role' => 'Backend Developer'],
            ['skills' => 'nodejs express mongodb', 'role' => 'Backend Developer'],
            ['skills' => 'php symfony doctrine', 'role' => 'Backend Developer'],
            ['skills' => 'ruby rails postgresql', 'role' => 'Backend Developer'],
            ['skills' => 'golang gin postgresql', 'role' => 'Backend Developer'],
            ['skills' => 'csharp dotnet sqlserver', 'role' => 'Backend Developer'],
            ['skills' => 'api rest graphql backend', 'role' => 'Backend Developer'],
            ['skills' => 'php codeigniter mysql', 'role' => 'Backend Developer'],
            ['skills' => 'java maven gradle api', 'role' => 'Backend Developer'],
            ['skills' => 'python fastapi async', 'role' => 'Backend Developer'],
            ['skills' => 'nodejs typescript nestjs', 'role' => 'Backend Developer'],
            ['skills' => 'database sql nosql redis', 'role' => 'Backend Developer'],

            // ============ FULL STACK DEVELOPER ============
            // Must have BOTH frontend AND backend skills combined
            ['skills' => 'php mysql html css javascript laravel', 'role' => 'Full Stack Developer'],
            ['skills' => 'nodejs react mongodb express', 'role' => 'Full Stack Developer'],
            ['skills' => 'python django html css javascript', 'role' => 'Full Stack Developer'],
            ['skills' => 'java spring angular typescript', 'role' => 'Full Stack Developer'],
            ['skills' => 'php vue mysql laravel', 'role' => 'Full Stack Developer'],
            ['skills' => 'mern mongodb express react nodejs', 'role' => 'Full Stack Developer'],
            ['skills' => 'mean mongodb express angular nodejs', 'role' => 'Full Stack Developer'],
            ['skills' => 'fullstack frontend backend api database', 'role' => 'Full Stack Developer'],
            ['skills' => 'react nodejs postgresql graphql', 'role' => 'Full Stack Developer'],
            ['skills' => 'vue laravel mysql tailwind', 'role' => 'Full Stack Developer'],

            // ============ UI/UX DESIGNER ============
            // Primary skills: Design tools, user research, wireframing, prototyping
            ['skills' => 'figma sketch design', 'role' => 'UI/UX Designer'],
            ['skills' => 'ux ui wireframe prototype', 'role' => 'UI/UX Designer'],
            ['skills' => 'adobe xd photoshop illustrator', 'role' => 'UI/UX Designer'],
            ['skills' => 'design thinking user research', 'role' => 'UI/UX Designer'],
            ['skills' => 'figma prototype usability', 'role' => 'UI/UX Designer'],
            ['skills' => 'ux research personas journey mapping', 'role' => 'UI/UX Designer'],
            ['skills' => 'ui design visual design branding', 'role' => 'UI/UX Designer'],
            ['skills' => 'sketch invision zeplin', 'role' => 'UI/UX Designer'],
            ['skills' => 'interaction design motion graphics', 'role' => 'UI/UX Designer'],
            ['skills' => 'design systems component library', 'role' => 'UI/UX Designer'],

            // ============ DATA SCIENTIST ============
            // Primary skills: Python, statistics, ML, data analysis, visualization
            ['skills' => 'python pandas numpy matplotlib', 'role' => 'Data Scientist'],
            ['skills' => 'machine learning deep learning tensorflow', 'role' => 'Data Scientist'],
            ['skills' => 'python scikit-learn statistics', 'role' => 'Data Scientist'],
            ['skills' => 'data analysis visualization tableau', 'role' => 'Data Scientist'],
            ['skills' => 'python pytorch neural networks', 'role' => 'Data Scientist'],
            ['skills' => 'sql python data mining', 'role' => 'Data Scientist'],
            ['skills' => 'statistics probability regression', 'role' => 'Data Scientist'],
            ['skills' => 'nlp natural language processing', 'role' => 'Data Scientist'],
            ['skills' => 'data science jupyter notebook', 'role' => 'Data Scientist'],
            ['skills' => 'big data spark hadoop', 'role' => 'Data Scientist'],

            // ============ MOBILE DEVELOPER ============
            // Primary skills: iOS, Android, React Native, Flutter, mobile-specific
            ['skills' => 'android kotlin java mobile', 'role' => 'Mobile Developer'],
            ['skills' => 'ios swift xcode mobile', 'role' => 'Mobile Developer'],
            ['skills' => 'react native mobile javascript', 'role' => 'Mobile Developer'],
            ['skills' => 'flutter dart mobile crossplatform', 'role' => 'Mobile Developer'],
            ['skills' => 'android studio xml kotlin', 'role' => 'Mobile Developer'],
            ['skills' => 'ios swiftui uikit', 'role' => 'Mobile Developer'],
            ['skills' => 'mobile app development', 'role' => 'Mobile Developer'],
            ['skills' => 'xamarin csharp mobile', 'role' => 'Mobile Developer'],
            ['skills' => 'ionic cordova mobile hybrid', 'role' => 'Mobile Developer'],
            ['skills' => 'flutter firebase mobile', 'role' => 'Mobile Developer'],

            // ============ DEVOPS ENGINEER ============
            // Primary skills: Cloud, containers, CI/CD, infrastructure
            ['skills' => 'docker kubernetes container', 'role' => 'DevOps Engineer'],
            ['skills' => 'aws azure gcp cloud', 'role' => 'DevOps Engineer'],
            ['skills' => 'jenkins gitlab cicd pipeline', 'role' => 'DevOps Engineer'],
            ['skills' => 'terraform ansible infrastructure', 'role' => 'DevOps Engineer'],
            ['skills' => 'linux bash shell scripting', 'role' => 'DevOps Engineer'],
            ['skills' => 'devops automation deployment', 'role' => 'DevOps Engineer'],
            ['skills' => 'monitoring prometheus grafana', 'role' => 'DevOps Engineer'],
            ['skills' => 'aws lambda serverless', 'role' => 'DevOps Engineer'],
            ['skills' => 'kubernetes helm microservices', 'role' => 'DevOps Engineer'],
            ['skills' => 'git version control branching', 'role' => 'DevOps Engineer'],

            // ============ GAME DEVELOPER ============
            ['skills' => 'unity csharp game development', 'role' => 'Game Developer'],
            ['skills' => 'unreal engine cpp blueprints', 'role' => 'Game Developer'],
            ['skills' => 'game design 3d modeling animation', 'role' => 'Game Developer'],
            ['skills' => 'unity 2d 3d physics', 'role' => 'Game Developer'],
            ['skills' => 'godot gdscript game', 'role' => 'Game Developer'],

            // ============ DIGITAL MARKETER ============
            ['skills' => 'seo sem google analytics', 'role' => 'Digital Marketer'],
            ['skills' => 'social media marketing content', 'role' => 'Digital Marketer'],
            ['skills' => 'facebook ads google ads ppc', 'role' => 'Digital Marketer'],
            ['skills' => 'email marketing campaign', 'role' => 'Digital Marketer'],
            ['skills' => 'marketing analytics conversion', 'role' => 'Digital Marketer'],

            // ============ PROJECT MANAGER ============
            ['skills' => 'project management agile scrum', 'role' => 'Project Manager'],
            ['skills' => 'jira confluence planning', 'role' => 'Project Manager'],
            ['skills' => 'leadership team management', 'role' => 'Project Manager'],
            ['skills' => 'stakeholder communication budget', 'role' => 'Project Manager'],
            ['skills' => 'pmp certification waterfall agile', 'role' => 'Project Manager'],

            // ============ CYBERSECURITY ANALYST ============
            ['skills' => 'security penetration testing ethical hacking', 'role' => 'Cybersecurity Analyst'],
            ['skills' => 'network security firewall ids', 'role' => 'Cybersecurity Analyst'],
            ['skills' => 'vulnerability assessment security audit', 'role' => 'Cybersecurity Analyst'],
            ['skills' => 'siem incident response forensics', 'role' => 'Cybersecurity Analyst'],
            ['skills' => 'encryption authentication security', 'role' => 'Cybersecurity Analyst'],

            // ============ DATABASE ADMINISTRATOR ============
            ['skills' => 'mysql postgresql database administration', 'role' => 'Database Administrator'],
            ['skills' => 'oracle sqlserver database tuning', 'role' => 'Database Administrator'],
            ['skills' => 'mongodb nosql database management', 'role' => 'Database Administrator'],
            ['skills' => 'backup recovery replication clustering', 'role' => 'Database Administrator'],
            ['skills' => 'database optimization indexing queries', 'role' => 'Database Administrator'],
        ]);
    }

    /**
     * Train the Naive Bayes classifier
     */
    public function train(array $samples) {
        $classCounts = [];
        $featureCounts = [];
        $totalDocs = count($samples);
        $this->vocabulary = [];

        foreach ($samples as $sample) {
            $role = $sample['role'];
            $tokens = $this->tokenize($sample['skills']);

            if (!isset($classCounts[$role])) {
                $classCounts[$role] = 0;
                $featureCounts[$role] = [];
            }
            $classCounts[$role]++;

            foreach ($tokens as $token) {
                $this->vocabulary[$token] = true;
                if (!isset($featureCounts[$role][$token])) {
                    $featureCounts[$role][$token] = 0;
                }
                $featureCounts[$role][$token]++;
            }
        }

        // Calculate probabilities
        foreach ($classCounts as $role => $count) {
            $this->classProbabilities[$role] = log($count / $totalDocs);
            
            // Calculate feature probabilities with Laplace smoothing
            $totalFeaturesInClass = array_sum($featureCounts[$role]);
            $vocabSize = count($this->vocabulary);
            
            $this->featureProbabilities[$role] = [];
            foreach (array_keys($this->vocabulary) as $token) {
                $countInClass = $featureCounts[$role][$token] ?? 0;
                // P(word|class) = (count + 1) / (total_words_in_class + vocab_size)
                $this->featureProbabilities[$role][$token] = log(($countInClass + 1) / ($totalFeaturesInClass + $vocabSize));
            }
        }
    }

    /**
     * Predict the role based on skills
     */
    public function predict($skills) {
        $tokens = $this->tokenize($skills);
        $scores = [];

        foreach ($this->classProbabilities as $role => $classProb) {
            $scores[$role] = $classProb;
            foreach ($tokens as $token) {
                if (isset($this->vocabulary[$token])) {
                    $scores[$role] += $this->featureProbabilities[$role][$token];
                }
            }
        }

        arsort($scores);
        return array_keys($scores)[0];
    }

    /**
     * Get prediction with confidence scores
     */
    public function predictWithScores($skills) {
        $tokens = $this->tokenize($skills);
        $scores = [];

        foreach ($this->classProbabilities as $role => $classProb) {
            $scores[$role] = $classProb;
            foreach ($tokens as $token) {
                if (isset($this->vocabulary[$token])) {
                    $scores[$role] += $this->featureProbabilities[$role][$token];
                }
            }
        }

        // Convert log probabilities to percentages using softmax-like normalization
        $maxScore = max($scores);
        $expScores = [];
        foreach ($scores as $role => $score) {
            // Shift scores to prevent overflow
            $expScores[$role] = exp($score - $maxScore);
        }
        $sumExp = array_sum($expScores);
        
        $normalizedScores = [];
        foreach ($expScores as $role => $expScore) {
            $normalizedScores[$role] = round(($expScore / $sumExp) * 100, 1);
        }

        arsort($normalizedScores);
        return array_slice($normalizedScores, 0, 5, true); // Return top 5
    }

    /**
     * Simple tokenizer
     */
    private function tokenize($text) {
        // Convert to lowercase, remove special chars, split by whitespace
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);
        $tokens = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        return array_unique($tokens); // Use unique tokens (Bernoulli model variant)
    }
}
