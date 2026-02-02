/**
 * Central State Management
 * Single source of truth for the entire application
 */

const AppState = {
    // Authentication
    currentUser: null,

    // Collections
    interns: [],
    tasks: [],
    logs: [],

    // UI State
    currentView: 'dashboard',
    loading: false,
    error: null,

    // Counters
    internSequence: 0,
    taskSequence: 0,

    /**
     * Initialize state from localStorage
     */
    init() {
        try {
            const saved = localStorage.getItem('appState');
            if (saved) {
                const parsed = JSON.parse(saved);
                this.interns = parsed.interns || [];
                this.tasks = parsed.tasks || [];
                this.logs = parsed.logs || [];
                this.internSequence = parsed.internSequence || 0;
                this.taskSequence = parsed.taskSequence || 0;
            }
        } catch (e) {
            console.error('Error loading state from localStorage:', e);
            this.interns = [];
            this.tasks = [];
            this.logs = [];
            this.internSequence = 0;
            this.taskSequence = 0;
        }
    },

    /**
     * Add a new intern
     */
    addIntern(internData) {
        const id = this.generateInternId();
        const intern = {
            id,
            name: internData.name,
            email: internData.email,
            department: internData.department || '',
            skills: internData.skills,
            status: 'ONBOARDING',
            tasksAssigned: [],
            createdAt: new Date().toISOString()
        };
        this.interns.push(intern);
        this.save();
        this.addLog('CREATE_INTERN', `Created intern ${id}`, 'success');
        return intern;
    },

    /**
     * Get intern by ID
     */
    getInternById(id) {
        return this.interns.find(i => i.id === id);
    },

    /**
     * Update intern status
     */
    updateInternStatus(internId, newStatus) {
        const intern = this.getInternById(internId);
        if (!intern) return false;
        intern.status = newStatus;
        this.save();
        this.addLog('UPDATE_STATUS', `Intern ${internId} status changed to ${newStatus}`, 'success');
        return true;
    },

    /**
     * Get all interns
     */
    getAllInterns() {
        return [...this.interns];
    },

    /**
     * Add a new task
     */
    addTask(taskData) {
        const id = this.generateTaskId();
        const task = {
            id,
            title: taskData.title,
            description: taskData.description || '',
            requiredSkills: taskData.requiredSkills,
            priority: taskData.priority || 'MEDIUM',
            estimatedHours: taskData.estimatedHours,
            status: 'TODO',
            assignedTo: null,
            dependencies: taskData.dependencies || [],
            createdAt: new Date().toISOString()
        };
        this.tasks.push(task);
        this.save();
        this.addLog('CREATE_TASK', `Created task ${id}`, 'success');
        return task;
    },

    /**
     * Get task by ID
     */
    getTaskById(id) {
        return this.tasks.find(t => t.id === id);
    },

    /**
     * Update task status
     */
    updateTaskStatus(taskId, newStatus) {
        const task = this.getTaskById(taskId);
        if (!task) return false;
        task.status = newStatus;
        this.save();
        this.addLog('UPDATE_STATUS', `Task ${taskId} status changed to ${newStatus}`, 'success');
        return true;
    },

    /**
     * Assign task to intern
     */
    assignTask(taskId, internId) {
        const task = this.getTaskById(taskId);
        const intern = this.getInternById(internId);

        if (!task || !intern) return false;

        task.assignedTo = internId;
        if (!intern.tasksAssigned.includes(taskId)) {
            intern.tasksAssigned.push(taskId);
        }
        this.save();
        this.addLog('ASSIGN_TASK', `Task ${taskId} assigned to ${internId}`, 'success');
        return true;
    },

    /**
     * Unassign task from intern
     */
    unassignTask(taskId, internId) {
        const task = this.getTaskById(taskId);
        const intern = this.getInternById(internId);

        if (!task || !intern) return false;

        task.assignedTo = null;
        intern.tasksAssigned = intern.tasksAssigned.filter(tid => tid !== taskId);
        this.save();
        this.addLog('UNASSIGN_TASK', `Task ${taskId} unassigned from ${internId}`, 'success');
        return true;
    },

    /**
     * Get all tasks
     */
    getAllTasks() {
        return [...this.tasks];
    },

    /**
     * Get tasks assigned to intern
     */
    getTasksForIntern(internId) {
        return this.tasks.filter(t => t.assignedTo === internId);
    },

    /**
     * Add activity log
     */
    addLog(action, details, status = 'info') {
        const logEntry = {
            timestamp: new Date().toISOString(),
            action,
            details,
            status
        };
        this.logs.push(logEntry);
        // Keep only last 500 logs
        if (this.logs.length > 500) {
            this.logs = this.logs.slice(-500);
        }
        this.save();
    },

    /**
     * Get all logs
     */
    getAllLogs() {
        return [...this.logs];
    },

    /**
     * Filter logs
     */
    getLogsByAction(action) {
        return this.logs.filter(log => log.action === action);
    },

    /**
     * Generate unique intern ID (year + sequence)
     */
    generateInternId() {
        const year = new Date().getFullYear();
        this.internSequence++;
        return `I${year}${String(this.internSequence).padStart(3, '0')}`;
    },

    /**
     * Generate unique task ID
     */
    generateTaskId() {
        const year = new Date().getFullYear();
        this.taskSequence++;
        return `T${year}${String(this.taskSequence).padStart(3, '0')}`;
    },

    /**
     * Get stats
     */
    getStats() {
        return {
            totalInterns: this.interns.length,
            activeInterns: this.interns.filter(i => i.status === 'ACTIVE').length,
            totalTasks: this.tasks.length,
            pendingTasks: this.tasks.filter(t => t.status !== 'DONE').length,
            completedTasks: this.tasks.filter(t => t.status === 'DONE').length
        };
    },

    /**
     * Check if email is already used
     */
    isEmailUsed(email) {
        return this.interns.some(i => i.email.toLowerCase() === email.toLowerCase());
    },

    /**
     * Save state to localStorage
     */
    save() {
        const dataToSave = {
            interns: this.interns,
            tasks: this.tasks,
            logs: this.logs,
            internSequence: this.internSequence,
            taskSequence: this.taskSequence
        };
        localStorage.setItem('appState', JSON.stringify(dataToSave));
    },

    /**
     * Clear all data
     */
    clear() {
        this.interns = [];
        this.tasks = [];
        this.logs = [];
        this.internSequence = 0;
        this.taskSequence = 0;
        localStorage.removeItem('appState');
        this.addLog('CLEAR', 'All data cleared', 'info');
    }
};

// Initialize state on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => AppState.init());
} else {
    AppState.init();
}
