<?php
/*
Template Name: Quiz Clean Embed
*/

// Disable WordPress scripts and styles that might interfere
remove_action('wp_head', 'wp_print_scripts');
remove_action('wp_head', 'wp_print_head_scripts', 9);
remove_action('wp_head', 'wp_enqueue_scripts', 1);
remove_action('wp_footer', 'wp_print_footer_scripts', 20);

// Remove Facebook Pixel and other tracking
remove_action('wp_head', 'wp_head');
remove_action('wp_footer', 'wp_footer');

// Get quiz URL and preserve query parameters
$base_quiz_url = get_field('quiz_url') ?: 'YOUR_REPLIT_URL_HERE';
$query_string = $_SERVER['QUERY_STRING'] ?? '';
$quiz_url = $query_string ? $base_quiz_url . '?' . $query_string : $base_quiz_url;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Income Quiz</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            overflow: hidden;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .quiz-container {
            width: 100vw;
            height: 100vh;
            position: relative;
        }
        
        .quiz-frame {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }
        
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #8B7CF6 0%, #667EEA 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            z-index: 1000;
            transition: opacity 0.5s ease;
        }
        
        .loading-overlay.fade-out {
            opacity: 0;
            pointer-events: none;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255,255,255,0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .error-message {
            text-align: center;
            max-width: 400px;
            padding: 20px;
        }
        
        .error-message a {
            color: white;
            text-decoration: underline;
            margin-top: 15px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="quiz-container">
        <div id="loading" class="loading-overlay">
            <div class="error-message">
                <div class="spinner"></div>
                <p id="loading-text">Loading your quiz...</p>
            </div>
        </div>
        
        <iframe 
            id="quiz-frame"
            class="quiz-frame"
            src="<?php echo esc_url($quiz_url); ?>"
            title="YouTube Income Quiz"
            allow="fullscreen"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>

    <script>
        (function() {
            'use strict';
            
            const iframe = document.getElementById('quiz-frame');
            const loading = document.getElementById('loading');
            const loadingText = document.getElementById('loading-text');
            let hasLoaded = false;
            let attempts = 0;
            const maxAttempts = 3;
            
            function hideLoading() {
                if (!hasLoaded) {
                    hasLoaded = true;
                    loading.classList.add('fade-out');
                    setTimeout(() => {
                        loading.style.display = 'none';
                    }, 500);
                }
            }
            
            function showError() {
                const quizUrl = iframe.src;
                loading.innerHTML = `
                    <div class="error-message">
                        <p>Unable to load the quiz in embedded mode.</p>
                        <a href="${quizUrl}" target="_blank">Click here to open the quiz →</a>
                    </div>
                `;
            }
            
            function checkIframeLoad() {
                attempts++;
                
                try {
                    // Check if iframe is responsive
                    if (iframe.contentWindow) {
                        hideLoading();
                        return;
                    }
                } catch (e) {
                    // CORS error is expected, but iframe might still work
                }
                
                if (attempts >= maxAttempts) {
                    showError();
                } else {
                    loadingText.textContent = `Loading your quiz... (${attempts}/${maxAttempts})`;
                    setTimeout(checkIframeLoad, 2000);
                }
            }
            
            // Start checking after initial delay
            setTimeout(checkIframeLoad, 1000);
            
            // Listen for iframe events
            iframe.addEventListener('load', hideLoading);
            iframe.addEventListener('error', showError);
            
            // Message listener for iframe communication
            window.addEventListener('message', function(event) {
                if (event.data === 'quiz-loaded') {
                    hideLoading();
                }
            });
            
            // Fallback - always show something after 10 seconds
            setTimeout(function() {
                if (!hasLoaded) {
                    // Assume it's loaded and hide loading
                    hideLoading();
                }
            }, 10000);
        })();
    </script>
</body>
</html>