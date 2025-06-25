<?php
// Start session and initialize data arrays if needed
session_start();

// Initialize session data arrays if not already set
if (!isset($_SESSION['study_sessions'])) {
    $_SESSION['study_sessions'] = [];
}
if (!isset($_SESSION['goals'])) {
    $_SESSION['goals'] = [];
}
if (!isset($_SESSION['ai_messages'])) {
    $_SESSION['ai_messages'] = [];
}
