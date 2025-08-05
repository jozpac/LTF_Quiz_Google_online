<?php
/*
Template Name: Full Screen Quiz
*/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title(); ?></title>
    
    <!-- Remove WordPress admin bar for full-screen experience -->
    <style>
        html { margin-top: 0 !important; }
        * html body { margin-top: 0 !important; }
        @media screen and ( max-width: 782px ) {
            html { margin-top: 0 !important; }
            * html body { margin-top: 0 !important; }
        }
        
        /* Full-screen iframe styles */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        
        #quiz-iframe {
            width: 100%;
            height: 100vh;
            border: none;
            display: block;
        }
        
        /* Loading fallback */
        .quiz-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(135deg, hsl(240, 67%, 72%) 0%, hsl(258, 37%, 62%) 100%);
            color: white;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .quiz-loading.hidden {
            display: none;
        }
    </style>
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- Loading screen -->
<div id="quiz-loading" class="quiz-loading">
    <div style="text-align: center;">
        <div style="width: 40px; height: 40px; border: 4px solid rgba(255,255,255,0.3); border-top: 4px solid white; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
        <p>Loading your quiz...</p>
    </div>
</div>

<!-- Quiz iframe -->
<iframe 
    id="quiz-iframe"
    src="<?php echo esc_url(get_field('quiz_url') ?: 'YOUR_REPLIT_URL_HERE'); ?>"
    title="YouTube Monetization Quiz"
    allow="fullscreen; payment; microphone; camera"
    sandbox="allow-same-origin allow-scripts allow-forms allow-popups allow-top-navigation allow-modals"
    style="display: none;">
</iframe>

<script>
// Enhanced loading detection
let iframeLoaded = false;
const iframe = document.getElementById('quiz-iframe');
const loading = document.getElementById('quiz-loading');

function showQuiz() {
    if (!iframeLoaded) {
        iframeLoaded = true;
        loading.style.display = 'none';
        iframe.style.display = 'block';
        console.log('Quiz iframe loaded successfully');
    }
}

function handleError() {
    console.error('Quiz iframe failed to load');
    loading.innerHTML = '<div style="text-align: center;"><p>Unable to load quiz. <a href="' + iframe.src + '" target="_blank" style="color: white; text-decoration: underline;">Click here to open in new tab</a></p></div>';
}

// Multiple detection methods
iframe.onload = showQuiz;
iframe.onerror = handleError;

// Check if iframe content is accessible
iframe.addEventListener('load', function() {
    try {
        // Try to access iframe content to verify it loaded
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        if (iframeDoc.body) {
            showQuiz();
        }
    } catch (e) {
        // Cross-origin restriction - but iframe might still be loading
        showQuiz();
    }
});

// Fallback timeout with error handling
setTimeout(function() {
    if (!iframeLoaded) {
        try {
            // Check if iframe has content
            if (iframe.contentWindow && iframe.contentWindow.location.href !== 'about:blank') {
                showQuiz();
            } else {
                handleError();
            }
        } catch (e) {
            // Assume loaded if we can't check (CORS)
            showQuiz();
        }
    }
}, 5000);

// Add spinning animation
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);

// Remove WordPress admin bar if present
if (document.getElementById('wpadminbar')) {
    document.getElementById('wpadminbar').style.display = 'none';
}
</script>

<?php wp_footer(); ?>
</body>
</html>