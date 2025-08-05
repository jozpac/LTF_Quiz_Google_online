import { useState, useEffect } from "react";
import { motion, AnimatePresence } from "framer-motion";

interface QuizQuestion {
  question: string;
  answers: string[];
}

const quizData: QuizQuestion[] = [
  {
    question: "What's the main reason you want to earn on YouTube?",
    answers: [
      "I want to escape my current job for something I enjoy more",
      "I want to stop trading time for money",
      "I want a way to earn passively over time",
    ],
  },
  {
    question: "Which of these best describes your situation right now?",
    answers: [
      "I'm working a 9-5 and looking for a better option",
      "I have savings and want to build a smart income stream",
      "I'm starting from scratch and want a proven path",
    ],
  },
  {
    question:
      "How much time can you realistically commit each week to earn extra income?",
    answers: ["1-3 hours", "4-8 hours", "10+ hours"],
  },
  {
    question:
      "What would an extra full-time income from YouTube allow you to do?",
    answers: [
      "Quit my job and take back control of my time",
      "Support my family and reduce financial pressure",
      "Reinvest and grow multiple income streams",
      "Travel more and live life on my terms",
    ],
  },
  {
    question:
      "What feels like the hardest part about starting a faceless channel?",
    answers: [
      "I'm not sure how to choose the right channel idea",
      "I don't know where to find reliable freelancers",
      "I don't know what makes videos go viral",
      "I don't know how to monetize in multiple ways",
    ],
  },
  {
    question:
      "What would help you most right now to start earning from YouTube?",
    answers: [
      "A clear, step-by-step plan to follow",
      "Guidance from someone who's done it",
      "Access to a proven system that I can copy",
    ],
  },
];

export default function Quiz() {
  const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
  const [selectedAnswer, setSelectedAnswer] = useState<number | null>(null);
  const [isTransitioning, setIsTransitioning] = useState(false);
  const [showLoader, setShowLoader] = useState(false);

  const currentQuestion = quizData[currentQuestionIndex];
  const progress = ((currentQuestionIndex + 1) / quizData.length) * 100;

  // Notify parent window when quiz loads (for iframe embedding)
  useEffect(() => {
    if (window.parent !== window) {
      window.parent.postMessage("quiz-loaded", "*");
    }
  }, []);

  // Function to get and preserve URL parameters from current window and parent (for iframe)
  const getUrlParams = () => {
    let params = new URLSearchParams();

    // Get params from current window (iframe)
    const currentParams = new URLSearchParams(window.location.search);

    // Try to get params from parent window if embedded in iframe
    try {
      if (window.parent !== window && window.parent.location.search) {
        const parentParams = new URLSearchParams(window.parent.location.search);
        // Merge parent params first (they take precedence for ad tracking)
        parentParams.forEach((value, key) => {
          params.set(key, value);
        });
      }
    } catch (e) {
      // Cross-origin iframe, can't access parent - that's fine
      console.log("Cannot access parent window params (cross-origin)");
    }

    // Then add current window params (these override if duplicates)
    currentParams.forEach((value, key) => {
      params.set(key, value);
    });

    return params.toString() ? `?${params.toString()}` : "";
  };

  // Function to build redirect URL with preserved parameters
  const buildRedirectUrl = () => {
    const baseUrl = "https://lp.viralprofits.yt/get-access-g";
    const preservedParams = getUrlParams();

    if (preservedParams) {
      return `${baseUrl}${preservedParams}`;
    }

    return baseUrl;
  };

  const handleAnswerSelect = (answerIndex: number) => {
    if (isTransitioning) return;

    setSelectedAnswer(answerIndex);

    // Check if this is the last question
    const isLastQuestion = currentQuestionIndex + 1 >= quizData.length;

    if (isLastQuestion) {
      // Show loader after selection animation
      setTimeout(() => {
        setShowLoader(true);
        // Redirect after showing loader for a moment
        setTimeout(() => {
          // Break out of iframe and redirect in the full browser window
          const redirectUrl = buildRedirectUrl();

          try {
            if (window.top && typeof window.top.location !== "undefined") {
              console.log("Redirecting using window.top to:", redirectUrl);
              window.top.location.href = redirectUrl;
            } else {
              throw new Error("window.top is unavailable or blocked.");
            }
          } catch (e) {
            console.warn("window.top blocked, using postMessage");
            // Send full redirect URL to parent if window.top is blocked
            window.parent.postMessage(
              { action: "redirectToNextStep", url: redirectUrl },
              "*",
            );
          }
        }, 1000);
      }, 200);
    } else {
      // Wait a bit then proceed to next question
      setTimeout(() => {
        nextQuestion();
      }, 200);
    }
  };

  const nextQuestion = () => {
    setIsTransitioning(true);

    setTimeout(() => {
      if (currentQuestionIndex + 1 >= quizData.length) {
        // Last question - redirect to landing page with preserved parameters
        window.location.href = buildRedirectUrl();
      } else {
        setCurrentQuestionIndex((prev) => prev + 1);
        setSelectedAnswer(null);
      }
      setIsTransitioning(false);
    }, 200);
  };

  return (
    <div className="quiz-gradient-bg flex items-start sm:items-center justify-center px-4 relative h-screen">
      {/* Progress Bar */}
      <div className="fixed top-0 left-0 w-full bg-gray-200 bg-opacity-30 z-50 h-2">
        <div
          className="progress-bar h-full transition-all duration-700 ease-out"
          style={{
            width: `${progress}%`,
            background:
              "linear-gradient(90deg, hsl(213, 94%, 68%), hsl(231, 78%, 58%))",
            boxShadow: "0 2px 10px rgba(59, 130, 246, 0.5)",
          }}
        />
      </div>

      <AnimatePresence mode="wait">
        <motion.div
          key={currentQuestionIndex}
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          exit={{ opacity: 0, y: -20 }}
          transition={{ duration: 0.3 }}
          className="quiz-card bg-white rounded-2xl p-4 sm:p-8 md:p-12 max-w-2xl w-full mx-2 sm:mx-4 mt-20 sm:mt-0 py-10 sm:py-4 md:py-12"
        >
          {showLoader ? (
            /* Loading State */
            <div className="flex flex-col items-center justify-center py-16">
              <div
                className="w-12 h-12 rounded-full border-4 border-gray-200 border-t-transparent animate-spin"
                style={{
                  borderTopColor: "hsl(213, 94%, 68%)",
                  background:
                    "linear-gradient(135deg, hsl(213, 94%, 68%), hsl(231, 78%, 58%))",
                }}
              ></div>
              <p className="text-gray-600 mt-4 text-center">
                Preparing your results...
              </p>
            </div>
          ) : (
            <>
              {/* Question Counter */}
              <div className="text-sm text-gray-500 mb-4 sm:mb-6 text-center">
                Question {currentQuestionIndex + 1} of {quizData.length}
              </div>

              {/* Question */}
              <h1 className="text-lg sm:text-xl md:text-3xl font-semibold text-gray-900 text-center mb-6 md:mb-8 leading-tight">
                {currentQuestion.question}
              </h1>

              {/* Answer Options */}
              <div className="space-y-4">
                {currentQuestion.answers.map((answer, index) => (
                  <div
                    key={index}
                    onClick={() => handleAnswerSelect(index)}
                    className={`answer-option border-2 border-gray-200 rounded-xl p-4 sm:p-6 flex items-center space-x-3 sm:space-x-4 ${
                      selectedAnswer === index ? "selected" : ""
                    }`}
                  >
                    <div className="flex-shrink-0 w-6 h-6 sm:w-8 sm:h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-700 font-medium text-sm sm:text-base">
                      {index + 1}
                    </div>
                    <p className="text-sm sm:text-base text-gray-800 font-medium">
                      {answer}
                    </p>
                  </div>
                ))}
              </div>
            </>
          )}
        </motion.div>
      </AnimatePresence>
    </div>
  );
}
