<?php
// includes/handle_requests.php

// This file should be included after session is started (init.php) and functions are available.

// Add new study session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_session'])) {
    $subject = htmlspecialchars(trim($_POST['subject']));
    $duration = (int)$_POST['duration'];
    $date = htmlspecialchars($_POST['date']);
    $notes = htmlspecialchars(trim($_POST['notes']));

    $new_session = [
        'id' => uniqid(),
        'subject' => $subject,
        'duration' => $duration,
        'date' => $date,
        'notes' => $notes,
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Prepend to sessions
    array_unshift($_SESSION['study_sessions'], $new_session);

    // Generate AI feedback
    $ai_feedback = generateAIFeedback($new_session, $_SESSION['study_sessions']);
    $_SESSION['ai_messages'][] = [
        'type' => 'feedback',
        'message' => $ai_feedback,
        'timestamp' => date('Y-m-d H:i:s')
    ];

    // Redirect to avoid resubmission on refresh
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Add new goal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_goal'])) {
    $goal_subject = htmlspecialchars(trim($_POST['goal_subject']));
    $target_hours = (int)$_POST['target_hours'];
    $deadline = htmlspecialchars($_POST['deadline']);

    $new_goal = [
        'id' => uniqid(),
        'subject' => $goal_subject,
        'target_hours' => $target_hours,
        'deadline' => $deadline,
        'created_at' => date('Y-m-d H:i:s'),
        'completed' => false
    ];

    array_unshift($_SESSION['goals'], $new_goal);

    // Generate AI suggestion
    $ai_suggestion = generateAIGoalSuggestion($new_goal, $_SESSION['study_sessions']);
    $_SESSION['ai_messages'][] = [
        'type' => 'suggestion',
        'message' => $ai_suggestion,
        'timestamp' => date('Y-m-d H:i:s')
    ];

    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Mark goal as completed
if (isset($_GET['complete_goal'])) {
    $goal_id = $_GET['complete_goal'];
    foreach ($_SESSION['goals'] as &$goal) {
        if ($goal['id'] === $goal_id) {
            $goal['completed'] = true;
            // Generate AI congrats
            $ai_congrats = generateAICongratulation($goal);
            $_SESSION['ai_messages'][] = [
                'type' => 'congrats',
                'message' => $ai_congrats,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            break;
        }
    }
    unset($goal);
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Delete session
if (isset($_GET['delete_session'])) {
    $session_id = $_GET['delete_session'];
    $_SESSION['study_sessions'] = array_filter($_SESSION['study_sessions'], function($session) use ($session_id) {
        return $session['id'] !== $session_id;
    });
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Delete goal
if (isset($_GET['delete_goal'])) {
    $goal_id = $_GET['delete_goal'];
    $_SESSION['goals'] = array_filter($_SESSION['goals'], function($goal) use ($goal_id) {
        return $goal['id'] !== $goal_id;
    });
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}
