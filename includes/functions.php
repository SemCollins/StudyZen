<?php

/**
 * Generate AI feedback for a newly added session.
 *
 * @param array $new_session
 * @param array $all_sessions
 * @return string
 */
function generateAIFeedback($new_session, $all_sessions) {
    $total_minutes = array_reduce($all_sessions, function($carry, $session) {
        return $carry + $session['duration'];
    }, 0);
    $total_hours = round($total_minutes / 60, 1);

    // Count how many times each subject appears
    $subjects = array_count_values(array_column($all_sessions, 'subject'));
    arsort($subjects);
    $most_studied = key($subjects) ?: $new_session['subject'];

    $messages = [
        "Great job studying {$new_session['subject']} for {$new_session['duration']} minutes!",
        "You've now studied {$new_session['subject']} for a total of " . calculateSubjectHours($new_session['subject'], $all_sessions) . " hours.",
        "Your total study time is now {$total_hours} hours across all subjects.",
        "Keep up the good work! {$most_studied} seems to be your focus area.",
        "Consistency is key! You're building great study habits.",
        "Did you know? Taking short breaks every 50 minutes can improve retention."
    ];

    return $messages[array_rand($messages)];
}

/**
 * Generate AI suggestion when a new goal is set.
 *
 * @param array $new_goal
 * @param array $all_sessions
 * @return string
 */
function generateAIGoalSuggestion($new_goal, $all_sessions) {
    $subject_hours = calculateSubjectHours($new_goal['subject'], $all_sessions);
    $remaining_hours = $new_goal['target_hours'] - $subject_hours;
    $deadline_ts = strtotime($new_goal['deadline']);
    $now = time();
    $days_remaining = $deadline_ts > $now
        ? ceil(($deadline_ts - $now) / (60 * 60 * 24))
        : 0;
    $daily_target = $days_remaining > 0 ? round($remaining_hours / $days_remaining, 1) : $remaining_hours;

    $messages = [
        "Ambitious goal! To reach {$new_goal['target_hours']} hours in {$new_goal['subject']}, aim for {$daily_target} hours daily.",
        "Goal set! You've already studied {$new_goal['subject']} for {$subject_hours} hours. {$remaining_hours} more to go!",
        "Great planning! Breaking your goal into smaller daily sessions will help you stay on track.",
        "Remember: Quality matters more than quantity. Focus on understanding, not just hours.",
        "Pro tip: Schedule study sessions for your most productive times of day."
    ];

    return $messages[array_rand($messages)];
}

/**
 * Generate AI congratulation when a goal is marked completed.
 *
 * @param array $goal
 * @return string
 */
function generateAICongratulation($goal) {
    $messages = [
        "Congratulations! You've completed your goal of {$goal['target_hours']} hours in {$goal['subject']}! 🎉",
        "Goal achieved! Your hard work on {$goal['subject']} has paid off. Time to celebrate!",
        "Well done! You've demonstrated great commitment to mastering {$goal['subject']}.",
        "Success! Completing goals is the first step to academic excellence. What's next?",
        "Amazing work! You've shown what consistent effort can achieve with {$goal['subject']}."
    ];

    return $messages[array_rand($messages)];
}

/**
 * Calculate total hours studied for a given subject.
 *
 * @param string $subject
 * @param array $sessions
 * @return float
 */
function calculateSubjectHours($subject, $sessions) {
    $minutes = array_reduce($sessions, function($carry, $session) use ($subject) {
        return $carry + ($session['subject'] === $subject ? $session['duration'] : 0);
    }, 0);
    return round($minutes / 60, 1);
}

/**
 * Calculate progress percentage for a goal.
 *
 * @param array $goal
 * @param array $sessions
 * @return float
 */
function calculateGoalProgress($goal, $sessions) {
    $subject_hours = calculateSubjectHours($goal['subject'], $sessions);
    if ($goal['target_hours'] <= 0) {
        return 0;
    }
    $percentage = ($subject_hours / $goal['target_hours']) * 100;
    return min($percentage, 100);
}

/**
 * Get total study hours across all sessions.
 *
 * @param array $sessions
 * @return float
 */
function getTotalStudyHours($sessions) {
    $minutes = array_reduce($sessions, function($carry, $session) {
        return $carry + $session['duration'];
    }, 0);
    return round($minutes / 60, 1);
}

/**
 * Get the most studied subject (the subject with highest count of sessions).
 *
 * @param array $sessions
 * @return string
 */
function getMostStudiedSubject($sessions) {
    if (empty($sessions)) {
        return 'None';
    }
    $subjects = array_count_values(array_column($sessions, 'subject'));
    arsort($subjects);
    return key($subjects);
}

/**
 * Get average session duration in minutes.
 *
 * @param array $sessions
 * @return float
 */
function getAverageSessionDuration($sessions) {
    if (empty($sessions)) {
        return 0;
    }
    $total = array_reduce($sessions, function($carry, $session) {
        return $carry + $session['duration'];
    }, 0);
    return round($total / count($sessions), 0);
}
