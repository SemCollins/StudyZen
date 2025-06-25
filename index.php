<?php
// index.php

// Include initialization and functions, then handle requests
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/handle_requests.php';

// Now render page
require_once __DIR__ . '/includes/templates/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Add Study Session Form -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
                <i class="fas fa-plus-circle text-blue-500 mr-2"></i> Add Study Session
            </h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <input type="text" id="subject" name="subject" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duration (minutes)</label>
                    <input type="number" id="duration" name="duration" min="1" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" id="date" name="date" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="notes" name="notes" rows="2" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="md:col-span-2">
                    <button type="submit" name="add_session" 
                        class="gradient-bg text-white px-6 py-2 rounded-lg font-medium hover:opacity-90 transition">
                        <i class="fas fa-save mr-2"></i> Save Session
                    </button>
                </div>
            </form>
        </div>

        <!-- Recent Study Sessions -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-history text-purple-500 mr-2"></i> Recent Sessions
                </h2>
                <span class="text-sm text-gray-500">
                    <?php echo count($_SESSION['study_sessions']); ?> sessions recorded
                </span>
            </div>
            
            <?php if (empty($_SESSION['study_sessions'])): ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-book-open text-4xl mb-3 opacity-30"></i>
                    <p>No study sessions recorded yet.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach (array_slice($_SESSION['study_sessions'], 0, 5) as $session): ?>
                        <div class="study-card bg-gray-50 hover:bg-gray-100 rounded-lg p-4 transition duration-300 ease-in-out">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium text-lg"><?php echo htmlspecialchars($session['subject']); ?></h3>
                                    <div class="flex items-center text-sm text-gray-500 mt-1">
                                        <span class="mr-3"><i class="far fa-clock mr-1"></i> <?php echo $session['duration']; ?> mins</span>
                                        <span><i class="far fa-calendar mr-1"></i> <?php echo date('M j, Y', strtotime($session['date'])); ?></span>
                                    </div>
                                    <?php if (!empty($session['notes'])): ?>
                                        <p class="text-gray-600 mt-2 text-sm"><?php echo htmlspecialchars($session['notes']); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="?delete_session=<?php echo $session['id']; ?>" class="text-red-400 hover:text-red-600">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($_SESSION['study_sessions']) > 5): ?>
                    <div class="text-center mt-4">
                        <!-- You could implement a separate page or modal to view all sessions -->
                        <a href="#" class="text-blue-500 hover:underline">View all sessions</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Column -->
    <div class="space-y-8">
        <!-- Study Goals -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-bullseye text-green-500 mr-2"></i> Study Goals
                </h2>
                <button onclick="toggleGoalForm()" class="text-sm bg-green-500 text-white px-3 py-1 rounded-full hover:bg-green-600 transition">
                    <i class="fas fa-plus mr-1"></i> New
                </button>
            </div>
            
            <!-- Goal Form (Initially Hidden) -->
            <div id="goalForm" class="hidden mb-6">
                <form method="POST" class="space-y-3">
                    <div>
                        <label for="goal_subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <input type="text" id="goal_subject" name="goal_subject" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label for="target_hours" class="block text-sm font-medium text-gray-700 mb-1">Target Hours</label>
                        <input type="number" id="target_hours" name="target_hours" min="1" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label for="deadline" class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                        <input type="date" id="deadline" name="deadline" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" name="add_goal" 
                            class="flex-1 bg-green-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-600 transition">
                            <i class="fas fa-check mr-2"></i> Set Goal
                        </button>
                        <button type="button" onclick="toggleGoalForm()" 
                            class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
            
            <?php if (empty($_SESSION['goals'])): ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-bullseye text-4xl mb-3 opacity-30"></i>
                    <p>No goals set yet.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($_SESSION['goals'] as $goal): ?>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium"><?php echo htmlspecialchars($goal['subject']); ?></h3>
                                    <div class="text-sm text-gray-500 mt-1">
                                        <div class="flex items-center">
                                            <i class="fas fa-flag mr-2"></i>
                                            <span><?php echo $goal['target_hours']; ?> hours by <?php echo date('M j, Y', strtotime($goal['deadline'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <?php if (!$goal['completed']): ?>
                                        <a href="?complete_goal=<?php echo $goal['id']; ?>" class="text-green-500 hover:text-green-700" title="Mark as completed">
                                            <i class="fas fa-check-circle"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-green-500" title="Completed">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    <?php endif; ?>
                                    <a href="?delete_goal=<?php echo $goal['id']; ?>" class="text-red-400 hover:text-red-600">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <?php if (!$goal['completed']): ?>
                                <div class="mt-3">
                                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                                        <span>Progress</span>
                                        <span><?php echo round(calculateGoalProgress($goal, $_SESSION['study_sessions']), 1); ?>%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full" 
                                            style="width: <?php echo calculateGoalProgress($goal, $_SESSION['study_sessions']); ?>%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <?php 
                                            $completed_hours = calculateSubjectHours($goal['subject'], $_SESSION['study_sessions']);
                                            $remaining = $goal['target_hours'] - $completed_hours;
                                            echo $completed_hours . " of " . $goal['target_hours'] . " hours (" . ($remaining > 0 ? $remaining . " remaining" : "goal reached") . ")";
                                        ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="mt-2 text-sm text-green-600">
                                    <i class="fas fa-check mr-1"></i> Goal completed!
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Study Statistics -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
                <i class="fas fa-chart-bar text-yellow-500 mr-2"></i> Statistics
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <div class="text-3xl font-bold text-blue-600">
                        <?php echo getTotalStudyHours($_SESSION['study_sessions']); ?>
                    </div>
                    <div class="text-sm text-blue-800">Total Hours</div>
                </div>
                <div class="bg-purple-50 rounded-lg p-4 text-center">
                    <div class="text-3xl font-bold text-purple-600">
                        <?php echo count($_SESSION['study_sessions']); ?>
                    </div>
                    <div class="text-sm text-purple-800">Sessions</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <div class="text-3xl font-bold text-green-600">
                        <?php echo getAverageSessionDuration($_SESSION['study_sessions']); ?>
                    </div>
                    <div class="text-sm text-green-800">Avg. Minutes</div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4 text-center">
                    <div class="text-3xl font-bold text-yellow-600">
                        <?php echo count(array_filter($_SESSION['goals'], function($g) { return $g['completed']; })); ?>
                    </div>
                    <div class="text-sm text-yellow-800">Goals Met</div>
                </div>
            </div>
        </div>

        <!-- AI Study Assistant -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
                <i class="fas fa-robot text-indigo-500 mr-2"></i> Study Assistant
            </h2>
            
            <div class="bg-indigo-50 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-indigo-100 p-2 rounded-full mr-3">
                        <i class="fas fa-robot text-indigo-500"></i>
                    </div>
                    <div>
                        <p class="font-medium text-indigo-800">StudyZen AI</p>
                        <p class="text-sm text-gray-700 mt-1">
                            <?php 
                                if (!empty($_SESSION['ai_messages'])) {
                                    echo end($_SESSION['ai_messages'])['message'];
                                } else {
                                    echo "Hi there! I'm here to help you track your studies and reach your goals. Add a study session or goal to get started!";
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($_SESSION['ai_messages'])): ?>
                <div class="space-y-3 max-h-60 overflow-y-auto pr-2 ai-messages-container">
                    <?php 
                    // Show last 3 AI messages (newest first)
                    $last_msgs = array_slice($_SESSION['ai_messages'], -3);
                    foreach ($last_msgs as $message): ?>
                        <div class="ai-message bg-gray-100 rounded-lg p-3">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-gray-200 p-1 rounded-full mr-2">
                                    <i class="fas fa-robot text-gray-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">
                                        <?php echo date('M j g:i a', strtotime($message['timestamp'])); ?>
                                    </p>
                                    <p class="text-sm text-gray-700 mt-1">
                                        <?php echo htmlspecialchars($message['message']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="ai-messages-container space-y-3 max-h-60 overflow-y-auto pr-2"></div>
            <?php endif; ?>
            
            <div class="mt-4">
                <button onclick="askAI()" class="w-full bg-indigo-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-600 transition">
                    <i class="fas fa-comment-dots mr-2"></i> Ask for Advice
                </button>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/templates/footer.php';
